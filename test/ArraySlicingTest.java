import java.util.Arrays;

public class ArraySlicingTest {
    public static void main(String[] args) {
        int[] nums = {1, 2, 3, 4, 5};
        System.out.println(arrayToString(nums));
        System.out.println(arrayToString(new int[0]));
        System.out.println(arrayToString(Arrays.copyOfRange(nums, 2, 10)));
        System.out.println(arrayToString(Arrays.copyOfRange(nums, 2, nums.length)));

    }
    
    public static String arrayToString(int[] nums) {
        String out = "[";
        for(int num : nums) {
            out += String.valueOf(num) + ", ";
        }
        return out.substring(0, Math.max(1, out.length() - 2)) + "]";
    }
}
