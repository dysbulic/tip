import java.util.regex.*;

/**
 * Doing some tests of the regular expression classes
 */
public class RegexTest
{
    public static void main(String[] args)
    {
        Pattern standardTimePattern = Pattern.compile
            ("((?:0?[1-9])|(?:1[0-2]))" +
             "(?::([0-5][0-9])(?::([0-5][0-9])(?::([0-9]{1,3}))?)?)?" +
             "(am|pm)?");
        Pattern militaryTimePattern = Pattern.compile
            ("((?:[01][0-9])|(?:2[0-3]))" +
             "(?::([0-5][0-9])(?::([0-5][0-9])(?::([0-9]{1,3}))?)?)?");
        for(int i = 0; i < args.length; i++)
        {
            Matcher matcher = militaryTimePattern.matcher(args[i]);
            System.out.println((i + 1) + ": " + args[i] + " " + 
                               (matcher.matches()
                                ? "matches" : "doesn't match"));
            if(matcher.matches())
            {
                for(int j = 1; j <= matcher.groupCount(); j++)
                {
                    System.out.println(" " + (j + 1) + ": " +
                                       matcher.group(j));
                }
            }
        }
    }
}
                                
