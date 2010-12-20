import java.lang.reflect.Field;

public class TestStaticInitializer
{
    public static void main(String[] args)
        throws Exception
    {
        System.out.println("Main");
        ClassTwo two = new ClassTwo();
        System.out.println("oneData = " + ClassOne.oneData);
    }
}

class ClassOne
{
    public static String oneData;
    public static boolean initialized = false;

    public ClassOne()
    {
        System.out.println("ClassOne constructor");
        synchronized(this)
        {
            if(!initialized)
            {
                initialize(this, "twoData");
            }
        }
    }

    public static void initialize(ClassOne one, String fieldName)
    {
        try
        {
            Field field = one.getClass().getField(fieldName);
            oneData = (String)field.get(one);
            System.out.println("ClassOne initilization run");
        }
        catch(Exception e)
        {
            System.out.println("Error: " + e.getClass().getName() +
                               ": " + e.getMessage());
        }
        initialized = true;
    }
}

class ClassTwo extends ClassOne
{
    public static String twoData;
    public final static ClassTwo singleton = new ClassTwo();

    static {
        System.out.println("Static initializer run");
        twoData = "two";
    }

    {
        System.out.println("Normal initializer run");
    }

    public ClassTwo()
    {
        System.out.println("ClassTwo constructor");
    }
}
