import java.awt.geom.Point2D;
import java.text.DecimalFormat;

/**
 * The problem is, given a slope and the length of the
 * hypotenuse of a right triangle, find the lengths of the
 * legs.
 *
 * This is  program to test the performance difference
 * between using trig to compute those values versus
 * using identities drived using a similar triangle.
 */
public class SlopePerformanceTest
{
    public static final String[] TEST_NAMES = { "Trigonometric Test #1",
                                                "Trigonometric Test #2",
                                                "Identity Test",
                                                "Countdown Identity Test",
                                                "Empty Loop Test" };
    public static final int NUM_TESTS = TEST_NAMES.length;
    public static final int MAX_SLOPE = 100;
    public static final double LENGTH = 100;

    public static void main(String[] args)
    {
        if(args.length == 0)
        {
            System.out.println("Usage SlopePerformanceTest <iterations>");
            System.out.println(" iterations is the number of slopes to generate");
            return;
        }
        double[] slopes = new double[Integer.parseInt(args[0])];

        for(int i = 0; i < slopes.length; i++)
        {
            slopes[i] = Math.random() * 2 * MAX_SLOPE - MAX_SLOPE;
        }

        System.out.println("Generated " + slopes.length + " random slope" +
                           (slopes.length == 1 ? "" : "s"));

        int test = (int)(Math.random() * NUM_TESTS);
        long[] times = new long[NUM_TESTS];
        for(int count = 0; count < NUM_TESTS; count++)
        {
            test = (test + 1) % NUM_TESTS;
            System.out.println("Running: " + TEST_NAMES[test]);
            long startTime = System.currentTimeMillis();
            switch(test)
            {
            case 0:
                for(int i = 0; i < slopes.length; i++)
                {
                    double theta = Math.atan(slopes[i]);
                    double x = LENGTH * Math.cos(theta);
                    double y = LENGTH * Math.sin(theta);
                }
                break;
            case 1:
                for(int i = 0; i < slopes.length; i++)
                {
                    double theta = Math.atan(slopes[i]);
                    double x = LENGTH * Math.cos(theta);
                    double y = slopes[i] * x;
                }
                break;
            case 2:
                for(int i = 0; i < slopes.length; i++)
                {
                    double x = LENGTH / Math.sqrt(1 + slopes[i] * slopes[i]);
                    double y = slopes[i] * x;
                }
                break;
            case 3:
                for(int i = slopes.length - 1; i >= 0; i--)
                {
                    double x = LENGTH / Math.sqrt(1 + slopes[i] * slopes[i]);
                    double y = slopes[i] * x;
                }
                break;
            case 4:
                for(int i = 0; i < slopes.length; i++)
                {
                }
            }
            times[test] = System.currentTimeMillis() - startTime;
        }

        int fastestIndex = 0;
        for(int i = 1; i < NUM_TESTS; i++)
        {
            if(times[i] < times[fastestIndex])
            {
                fastestIndex = i;
            }
        }
        
        System.out.println("The fastest time was \"" + TEST_NAMES[fastestIndex] + "\"");
        
        DecimalFormat format = new DecimalFormat("##.0000");

        System.out.println("The times for each test were:");
        for(int i = 0; i < NUM_TESTS; i++)
        {
            long delta = times[i] - times[fastestIndex];
            double factor = (double)times[i] / times[fastestIndex];
            System.out.print("  " + times[i] + "ms ");
            System.out.print("[+" + delta + "ms] ");
            if(factor != 1)
            {
                System.out.print("(" + format.format(factor) + "x) ");
            }
            System.out.println(TEST_NAMES[i]);
        }
    }
}
            
                
