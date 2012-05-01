import java.io.*;
import java.util.StringTokenizer;
import java.util.Iterator;
import java.util.ArrayList;

public class FileIO
{
   public final static int FOR_READING=1;
   public final static int FOR_WRITING=2;
   private int setting;
   BufferedReader br;
   PrintWriter pw;
   private boolean EOF=false;
   private boolean tokens;
   private String delimiter;

   public FileIO(String fileName, int operation) throws IOException
   {

      if (operation==FOR_READING || operation==FOR_WRITING)
      {
         setting=operation;
      }
      else
      {
         throw new IOException("Must specify reading or writing.");
      }
  
      if (setting==FOR_READING)
      {
         FileReader fr=new FileReader(fileName);
         br=new BufferedReader(fr);
      }
      else if (setting==FOR_WRITING)
      {
         FileWriter fw=new FileWriter(fileName);
         BufferedWriter bw=new BufferedWriter(fw);
         pw=new PrintWriter(bw);
      }

   }

   public FileIO(String fileName, String delimiter) throws IOException
   {

      setting=FOR_READING;
      tokens=true;
      this.delimiter=delimiter;

      FileReader fr=new FileReader(fileName);
      br=new BufferedReader(fr);

   }

   public Iterator getTokens() throws IOException
   {
      ArrayList list=new ArrayList();

      if (tokens && !EOF)
      {
         String temp=readLine();
         if (!EOF)
         {
            StringTokenizer tok=new StringTokenizer(temp,delimiter);
            list=new ArrayList();

            while(tok.hasMoreTokens())
            {
               list.add(tok.nextToken());
            }
         }
      }
      else
      {
         throw new IOException("Tokens not available.");
      }

      return list.iterator();
   }

   public FileIO(String fileName, String path, int operation) throws IOException
   {

      File file=new File(path,fileName);

      if (operation==FOR_READING || operation==FOR_WRITING)
      {
         setting=operation;
      }
      else
      {
         throw new IOException("Must specify reading or writing.");
      }
  
      if (setting==FOR_READING)
      {
         FileReader fr=new FileReader(file);
         br=new BufferedReader(fr);
      }
      else if (setting==FOR_WRITING)
      {
         FileWriter fw=new FileWriter(file);
         BufferedWriter bw=new BufferedWriter(fw);
         pw=new PrintWriter(bw);
      }

   }

   public boolean EOF()
   {
      return EOF;
   }

   public String readLine() throws IOException
   {
      String temp=null;

      if (setting==FOR_READING)
      {
         temp=br.readLine();
         if (temp==null)
         {
            EOF=true;
         }
      }
      else
      {
         throw new IOException("File is not open for reading.");
      }

      return temp;
   }

   public void writeLine(String line) throws IOException
   {
      if (setting==FOR_WRITING)
      {
         pw.println(line);
      }
      else
      {
         throw new IOException("File is not open for writing.");
      }
   }

   public void close() throws IOException
   {
      if (setting==FOR_READING)
      {
         br.close();
         setting=-1;
         br=null;
      }
      else if (setting==FOR_WRITING)
      {
         pw.close();
         setting=-1;
         pw=null;
      }
   }

}

