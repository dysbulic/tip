public class TempTest
{
    public static void main(String[] args)
    {
        if(args.length == 0)
        {
            // Property misnamed in the jnlp faq
            printProperty("java.io.tempdir");
        }
        else
        {
            for(int i = 0; i < args.length; i++)
            {
                printProperty(args[i]);
            }
        }
    }

    public static void printProperty(String property)
    {
        System.out.println(property + " = " + System.getProperty(property));
    }
}
