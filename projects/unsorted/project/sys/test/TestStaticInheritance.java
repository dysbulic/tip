public class TestStaticInheritance
{
    public static void main(String[] args)
    {
        ClassTwo two = new ClassTwo();
        ClassOne one = (ClassOne)two;

        System.out.println("      " +
                           "one.static [" + one.staticMember() + "] " +
                           (one.staticMember() == two.staticMember() ? "==" : "!=") +
                           " two.static [" + two.staticMember() + "]");

        System.out.println("one.memberStatic [" + one.memberStatic() + "] " +
                           (one.memberStatic() == two.memberStatic() ? "==" : "!=") +
                           " two.memberStatic [" + two.memberStatic() + "]");

        System.out.println("      " +
                           "one.member [" + one.member() + "] " +
                           (one.member() == two.member() ? "==" : "!=") +
                           " two.member [" + two.member() + "]");

        System.out.println("      " +
                           "one.number [" + one.number + "] " +
                           (one.number == two.number ? "==" : "!=") +
                           " two.number [" + two.number + "]");
        
        System.out.println("      " +
                           "one.NUMBER [" + one.NUMBER + "] " +
                           (one.NUMBER == two.NUMBER ? "==" : "!=") +
                           " two.NUMBER [" + two.NUMBER + "]");

        System.out.println("one.memberNumber [" + one.memberNumber + "] " +
                           (one.memberNumber == two.memberNumber ? "==" : "!=") +
                           " two.memberNumber [" + two.memberNumber + "]");

        System.out.println("      " +
                           "one.NUMBER [" + one.NUMBER + "] " +
                           (one.NUMBER == ClassOne.NUMBER ? "==" : "!=") +
                           " ClassOne.NUMBER [" + ClassOne.NUMBER + "]");

        System.out.println("                 " +
                           "one " + (one == two ? "==" : "!=") + " two");

        System.out.println("one.class [" + one.getClass().getName() + "] " +
                           (one.getClass().equals(two.getClass()) ? "==" : "!=") +
                           " two.class [" + two.getClass().getName() + "]");
    }
}

abstract class ClassOne
{
    static int NUMBER = 1;
    int number = NUMBER;
    int memberNumber = 1;

    public static int staticMember()
    {
        return NUMBER;
    }

    public int memberStatic()
    {
        return NUMBER;
    }

    public int member()
    {
        return number;
    }

    public abstract int abstractMember();
}

class ClassTwo extends ClassOne
{
    static int NUMBER = 2;
    int number = NUMBER;
    int memberNumber = 2;

    public static int staticMember()
    {
        return NUMBER;
    }

    public int memberStatic()
    {
        return NUMBER;
    }

    public int member()
    {
        return number;
    }

    // This is not allowed to be static; why?
    public int abstractMember()
    {
        return NUMBER;
    }
}
