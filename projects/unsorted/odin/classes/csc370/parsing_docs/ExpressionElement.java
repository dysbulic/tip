/**
 * This class represents an element of an expression. The possible elements
 * which this class may act for a wrapper for are: {@link java.lang.Integer},
 * {@link Variable}, {@link Operator}, and {@link GroupingSymbol}.
 *
 * @author Will Holcomb
 */
public class ExpressionElement
{
	/**
	 * Identifier for the beginning of the identifiers
	 */
	public final static int START = 3;

	/**
	 * Identifier for an {@link java.lang.Integer} element in the wrapper
	 */
	public final static int VALUE = START;

	/**
	 * Identifier for a {@link Variable} element in the wrapper
	 */
	public final static int VARIABLE = START + 1;

	/**
	 * Identifier for a {@link Operator} element in the wrapper
	 */
	public final static int OPERATOR = START + 2;

	/**
	 * Identifier for a {@link GroupingSymbol} element in the wrapper
	 */
	public final static int GROUPING_SYMBOL = START + 3;

	/**
	 * Identifier for an unknown element in the wrapper
	 */
	public final static int OTHER = START + 4;

	/**
	 * Identifier for the end of the identifiers
	 */
	public final static int END = OTHER;

	/**
	 * Holds one of the identifiers and specifies the type of the object wrapped
	 */
	public int type;

	/**
	 * The object which is being wrapped
	 */
	Object content;

	/**
	 * Sets the contents and establishes the type with a series of <b>instanceof</b> checks
	 *
	 * @param o the object to be wrapped may be an {@link java.lang.Integer Integer}, {@link Variable},
	 * {@link Operator}, or {@link GroupingSymbol}.
	 */
	public ExpressionElement(Object o)
	{
		content = o;

		if(o instanceof Integer)
			type = VALUE;
		else if(o instanceof Variable)
			type = VARIABLE;
		else if(o instanceof Operator)
			type = OPERATOR;
		else if(o instanceof GroupingSymbol)
			type = GROUPING_SYMBOL;
		else
			type = OTHER;
	}

	public String typeString()
	{
		switch(type)
		{
			case (VALUE):
				return new String("Value");
			case (VARIABLE):
				return new String("Variable");
			case (OPERATOR):
				return new String("Operator");
			case (GROUPING_SYMBOL):
				return new String("Grouping Symbol");
			case (OTHER):
				return new String("Other [" + content.getClass().getName() + "]");
			default:
				return new String("Unknown [" + content.getClass().getName() + "]");
		}
	}

	public String toString()
	{
		switch(type)
		{
			case (VALUE):
				return new String("Value: " + ((Integer)content).toString());
			case (VARIABLE):
				return new String("Variable: " + ((Variable)content).toString());
			case (OPERATOR):
				return new String("Operator: " + ((Operator)content).toString());
			case (GROUPING_SYMBOL):
				return new String("Grouping symbol: " + ((GroupingSymbol)content).toString());
			case (OTHER):
				return new String("Other [" + content.getClass().getName() + "]");
			default:
				return new String("Unknown [" + content.getClass().getName() + "]");
		}
	}
}

