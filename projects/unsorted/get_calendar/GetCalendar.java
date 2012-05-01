import java.net.*;
import java.io.*;
import java.util.*;
import java.text.*;
import gnu.getopt.*;

/**
 * Retrieves the pages from a page-a-day calendar.
 */
public class GetCalendar {
    public final static int NONE = 0;
    public final static int FRONT = Integer.parseInt("1", 2);
    public final static int BACK = Integer.parseInt("10", 2);

    public final String[] month = {
        "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
        "JUL", "AUG", "SEP", "OCT", "NOV", "DEC" };
    
    String username;
    String password;
    String calendar;
    int sides = NONE;
    Calendar time = Calendar.getInstance();
    int fileCount = 0;
    int imageCount = 0;
    
    public File getCalendars() 
        throws IOException {
        fileCount = 0;
        
        URL index = new URL("http://page-a-day.com" +
                            "/pub-bin/paduserlogin.pl?rm=setuser" +
                            "&target_uri=/index.pl" +
                            "&email=" + username +
                            "&password=" + password);

        HttpURLConnection connection =
            (HttpURLConnection)index.openConnection();
        connection.setDoOutput(true);
        connection.setAllowUserInteraction(false);
        connection.setUseCaches(false);

        String cookie = connection.getHeaderField("Set-Cookie");
    
        DateFormat dirFormat =
            new SimpleDateFormat("yyyy.MM.dd_hh:mm:ss");
        
        File outputDirectory = new File(System.getProperty("java.io.tmpdir") +
                                        File.separator +
                                        calendar + "-" +
                                        dirFormat.format(time.getTime()) +
                                        File.separator);
        outputDirectory.mkdir();
        
        if((sides & FRONT) != 0) {
            getPage("front", cookie, outputDirectory);
        }
        if((sides & BACK) != 0) {
            getPage("back", cookie, outputDirectory);
        }
        return outputDirectory;
    }

    public String getPage(String side, String cookie, File outputDirectory)
        throws IOException {
        
        URL page = new URL("http://page-a-day.com" +
                           "/pub-bin/paduserlogin.pl?rm=setuser" +
                           "&email=" + username +
                           "&password=" + password +
                           "&target_uri=/pad/editorial/" +
                           side + "/" + calendar + "/" +
                           getURLDate() + "/");
        
        HttpURLConnection connection = 
            (HttpURLConnection)page.openConnection();
        connection.setDoOutput(true);
        connection.setRequestProperty("Cookie", cookie);
        connection.setAllowUserInteraction(false);
        connection.setUseCaches(false);

        imageCount = 0;
        fileCount++;
            
        String html = null;
        int responseCode = connection.getResponseCode();
        if(responseCode == 200) {
            // OK
            InputStreamReader in =
                new InputStreamReader(connection.getInputStream());
            BufferedReader content = new BufferedReader(in);
            StringWriter string = new StringWriter();
            PrintWriter out = new PrintWriter(string);
            while(content.ready()) {
                out.println(content.readLine());
            }
            html = pullImageFiles(string.toString(), outputDirectory);
            FileWriter index = new FileWriter(new File(outputDirectory,
                                                       fileCount +
                                                       "_0_index.html"));
            index.write(html, 0, html.length());
            index.flush();
        } else {
            System.err.println("Error: http response: " + responseCode +
                               " " + connection.getResponseMessage());
        }
        return html;
    }

    /**
     * Extracts the image filenames from a html document.
     */
    String pullImageFiles(String html, File outputDirectory)
        throws IOException {
        
        StringBuffer output = new StringBuffer();
        int index = 0;
        while(index >= 0) {
            int startIndex = html.indexOf("<IMG", index);
            if(startIndex > 0) {
                output.append(html.substring(index, startIndex));
                int endIndex = html.indexOf(">", startIndex) + 1;
                String tag = html.substring(startIndex, endIndex);
                index = endIndex;

                startIndex = tag.indexOf("SRC=\"") + 5;
                String filename =
                    tag.substring(startIndex,
                                  tag.indexOf("\"", startIndex));
                getImage(filename, outputDirectory);
            } else {
                output.append(html.substring(index));
                index = startIndex;
            }
        }
        return output.toString();
    }

    public void getImage(String imageFilename,
                         File outputDirectory)
        throws IOException {

        URL image = new URL("http://page-a-day.com" +
                           "/PAD_ASSETS" +
                           "/" + calendar +
                           "/" + getURLDate() +
                           "/" + imageFilename);
        
        HttpURLConnection connection = 
            (HttpURLConnection)image.openConnection();
        connection.setDoOutput(true);
        connection.setAllowUserInteraction(false);
        connection.setUseCaches(false);
        
        String filename =
            imageFilename.substring(Math.max(imageFilename.lastIndexOf('/') + 1,
                                             0));

        String html = null;
        int responseCode = connection.getResponseCode();
        if(responseCode == 200) {
            // OK
            InputStreamReader in =
                new InputStreamReader(connection.getInputStream());
            FileWriter file =
                new FileWriter(new File(outputDirectory,
                                        fileCount + "_"  +
                                        (++imageCount) + "_" +
                                        filename));
            while(in.ready()) {
                file.write(in.read());
            }
            file.flush();
        } else {
            System.err.println("Error: http response: " + responseCode +
                               " " + connection.getResponseMessage());
        }
    }
        
    public String getURLDate() {
        NumberFormat monthNumber = new DecimalFormat("00");
        DateFormat urlFormat =
            new SimpleDateFormat(monthNumber.format(time.get(Calendar.MONTH) + 1) +
                                 "/" + month[time.get(Calendar.MONTH)] +
                                 "/dd");
        return urlFormat.format(time.getTime());
    }
    

    public static void main(String[] args) throws IOException {
        LongOpt[] options = new LongOpt[] {
            new LongOpt("front", LongOpt.NO_ARGUMENT, null, 'f'),
            new LongOpt("back", LongOpt.NO_ARGUMENT, null, 'b'),
            new LongOpt("help", LongOpt.NO_ARGUMENT, null, 'h'),
            new LongOpt("username", LongOpt.REQUIRED_ARGUMENT, null, 'u'),
            new LongOpt("password", LongOpt.REQUIRED_ARGUMENT, null, 'p'),
            new LongOpt("month", LongOpt.REQUIRED_ARGUMENT, null, 'm'),
            new LongOpt("day", LongOpt.REQUIRED_ARGUMENT, null, 'd') };

        Getopt arguments = new Getopt("GetCalender", args,
                                      "fbhu:p:m:d:", options);
        GetCalendar calendarGetter = new GetCalendar();

        int c;
        while((c = arguments.getopt()) != -1) {
            switch(c) {
            case 'f':
                calendarGetter.sides = calendarGetter.sides | GetCalendar.FRONT;
                break;
            case 'b':
                calendarGetter.sides = calendarGetter.sides | GetCalendar.BACK;
                break;
            case 'h':
                System.out.println("Help!");
                break;
            case 'u':
                calendarGetter.username = arguments.getOptarg();
                break;
            case 'p':
                calendarGetter.password = arguments.getOptarg();
                break;
            case 'd':
                int day = Integer.parseInt(arguments.getOptarg());
                calendarGetter.time.set(Calendar.DAY_OF_MONTH, day);
                break;
            case 'm':
                int month = Integer.parseInt(arguments.getOptarg());
                calendarGetter.time.set(Calendar.MONTH, month);
                break;
            }
        }
        
        for(int i = arguments.getOptind(); i < args.length; i++) {
            calendarGetter.calendar = args[i];
        }

        File outputDirectory = calendarGetter.getCalendars();
        System.out.print(outputDirectory);
    }
}
