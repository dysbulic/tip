import java.awt.Point;

public class HelloWorld {
    public static native void displayString(String string);
    public static native int getInt();
    public static native String getString();
    public static native double getDouble();
    public static native Point getPoint();
    public static native int[][] getArray(int length, int height);
    public static native void randomizeArray(int[][] array);

    static {
        System.loadLibrary("hello");
    }
    
    public static void main(String[] args) {
        String string = args.length > 0 ? args[0] : "Ça Va World?";
        HelloWorld.displayString(string);
        System.out.println("getInt: " + HelloWorld.getInt());
        System.out.println("getString: " + HelloWorld.getString());
        System.out.println("getDouble: " + HelloWorld.getDouble());
        Point point = HelloWorld.getPoint();
        System.out.println("getPoint: [" + point.x + ", " + point.y + "]");
        int[][] array = getArray(10, 10);
        printArray(array);
        randomizeArray(array);
    }
    
    public static void printArray(int[][] array) {
        for(int i = 0; i < array.length; i++) {
            System.out.print(i + ":");
            for(int j = 0; j < array[i].length; j++) {
                System.out.print(" " + array[i][j]);
            }
            System.out.println();
        }
    }
}
