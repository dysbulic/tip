public class TestInitialization
{
    int integer;
    long longInteger;
    short shortInteger;
    float floatingPoint;
    double doubleFloat;
    byte computerByte;
    boolean logicalBoolean;

    public static void main(String[] args)
    {
        TestInitialization test = new TestInitialization();
        System.out.println("        int = " + test.integer);
        System.out.println("       long = " + test.longInteger);
        System.out.println("      short = " + test.shortInteger);
        System.out.println("      float = " + test.floatingPoint);
        System.out.println("     double = " + test.doubleFloat);
        System.out.println("       byte = " + test.computerByte);
        System.out.println("    boolean = " + test.logicalBoolean);
    }
}
