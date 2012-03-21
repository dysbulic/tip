/**
 * This is a driver program to test the {@link ArithmeticEquation} class.
 *
 * @author Will Holcomb
 */
public class ExpressionTest
{
	protected static ArithmeticEquation e;

	public static void main(String[] agrs)
	{
		Variable [] x = new Variable[4];
		x[0] = new Variable("x", 3);
		x[1] = new Variable("y", 52);
		x[2] = new Variable("z", 5);
		x[3] = new Variable("alpha", -4);

		System.out.println("Testing a single digit equation");
		singleDigitTest();

		System.out.println("");

		System.out.println("Testing a multiple digit equation");
		multipleDigitTest();

		System.out.println("");

		System.out.println("Testing each of the operators");
		simpleAdditionTest();
		simpleSubtractionTest();
		simpleMultiplicationTest();
		simpleDivisionTest();
		simpleNegationTest();

		System.out.println("");

		System.out.println("Testing combinations of operators");
		operatorComboOne();
		operatorComboTwo();
		operatorComboThree();
		operatorComboFour();
		
		System.out.println("");

		System.out.println("Grouping symbol tests");
		groupingSymbolOne();
		groupingSymbolTwo();

		System.out.println("");
		for(int i = 0; i < x.length; i++)
			System.out.println("The value of " + x[i].name + " is " + x[i].valueOf());
		System.out.println("");

		e = new ArithmeticEquation("(-x*((3+y)-(2*alpha*-(y-z)))+z)*x");
		System.out.println(e.infixEquation() + " = " + e.solution());

		x[0].setValue(-27);
		x[1].setValue(0);
		x[2].setValue(22);
		x[3].setValue(-1);

		System.out.println("");
		for(int i = 0; i < x.length; i++)
			System.out.println("The value of " + x[i].name + " is " + x[i].valueOf());
		System.out.println("");

		e = new ArithmeticEquation("(-x*((3+y)-(2*alpha*-(y-z)))+z)*x");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Checks to see if an arithmetic equation can be created with a single numeric digit.
	 */
	public static void singleDigitTest()
	{
		e = new ArithmeticEquation("5");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Checks to see if an arithmetic equation can be created with multiple numeric digits.
	 */
	public static void multipleDigitTest()
	{
		e = new ArithmeticEquation("56");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("5656");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Does a simple addition
	 */
	public static void simpleAdditionTest()
	{
		e = new ArithmeticEquation("5 + 4");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Does a simple subtraction
	 */
	public static void simpleSubtractionTest()
	{
		e = new ArithmeticEquation("5 - 4");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Does a simple multiplication
	 */
	public static void simpleMultiplicationTest()
	{
		e = new ArithmeticEquation("5 * 4");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Does a simple division
	 */
	public static void simpleDivisionTest()
	{
		e = new ArithmeticEquation("6 / 2");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("6 / 51");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("51 / 6");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Does a simple negation
	 */
	public static void simpleNegationTest()
	{
		e = new ArithmeticEquation("-4");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Tests the combination of multiples of the same operator
	 */
	public static void operatorComboOne()
	{
		e = new ArithmeticEquation("16 + 2 + 14 + 61 + 2");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("6 * 62 * 24 * 6 * 22");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Tests multiples of operators with the same precedence
	 */
	public static void operatorComboTwo()
	{
		e = new ArithmeticEquation("6 + 12 - 44 - 6 + 23 + 2");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("6 / 21 * 42 * 6 / 23");
		System.out.println(e.infixEquation() + " throws a divide by zero exception");
		System.out.println("Error");

		e = new ArithmeticEquation("6 * 21 * 42 * 6 / 23");
		System.out.println(e.infixEquation() + " = " + e.solution());
		System.out.println("Error");

		e = new ArithmeticEquation("---345");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Tests multiples of different operators where the correct solution is
	 * found by simply solving left to right
	 */
	public static void operatorComboThree()
	{
		e = new ArithmeticEquation("68 * 42 * 13 + -47");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("62 / 2 - 3");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}

	/**
	 * Tests multiples of different operators where the correct solution is
	 * only found by using the order of operations
	 */
	public static void operatorComboFour()
	{
		e = new ArithmeticEquation("65 + 2 + 43 * 4 * -18");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("12 + 52 + -14 / 3");
		System.out.println(e.infixEquation() + " = " + e.solution());
		System.out.println("Error");
	}

	public static void groupingSymbolOne()
	{
		e = new ArithmeticEquation("(31 + 14) * 61");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("-(34 - 12) * [19 + -2] - -{2 * 3}");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("-(34 - 12) * (19 + -2) - -(2 * 3)");
		System.out.println(e.infixEquation() + " = " + e.solution());

		e = new ArithmeticEquation("-(34 - 12) * [19 + -2] / -{2 * 3}");
		System.out.println(e.infixEquation() + " = " + e.solution());
		System.out.println("Error");

		e = new ArithmeticEquation("-(34 - 12) * (19 + -2) / -(2 * 3)");
		System.out.println(e.infixEquation() + " = " + e.solution());
		System.out.println("Error");
	}

	public static void groupingSymbolTwo()
	{
		e = new ArithmeticEquation("(-(3)*((3+(52))-(2*(-4)*-((52)-(5))))+(5))*3");
		System.out.println(e.infixEquation() + " = " + e.solution());
	}
}