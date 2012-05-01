/**
 * Driver and test program for {@link GenericGroupingSymbolTable}.
 *
 * @author Will Holcomb
 */
public class GroupingSymbolTest
{
	public static void main(String[] args)
	{
		GroupingSymbolTable g = new GenericGroupingSymbolTable();
		String s;

		System.out.println("");

		for(int i = 0; i < args.length; i++)
		{
			System.out.println("g.isBalanced(\"" + args[i] + "\") = " + g.isBalanced(args[i]));
			System.out.println("");
		}

		s = new String("((x + 3) * 5)");
		System.out.println("g.isBalanced(\"" + s + "\") = " + g.isBalanced(s));
		System.out.println("");

		s = new String("(({x} + 3) * [5 + 7])");
		System.out.println("g.isBalanced(\"" + s + "\") = " + g.isBalanced(s));
		System.out.println("");

		s = new String("(({x} + 3) * [[5 + 7])");
		System.out.println("g.isBalanced(\"" + s + "\") = " + g.isBalanced(s));
		System.out.println("");

		s = new String("((x) + 3) * 5)");
		System.out.println("g.isBalanced(\"" + s + "\") = " + g.isBalanced(s));
		System.out.println("");
	}
}