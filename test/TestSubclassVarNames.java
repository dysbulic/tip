/**
 * Tests the effects of overriding a static private field in a
 * superclass.
 */
public class TestSubclassVarNames
{
    public static void main(String[] args)
    {
        System.out.println("two: " + Two.getText() + " : " + Two.getLength());
    }
}

class One
{
    static String text = "Hey";
    static int length = text.length();

    public static String getText()
    {
        return text;
    }

    public static int getLength()
    {
        return length;
    }
}

class Two extends One
{
    static String text = "Hello";
}
