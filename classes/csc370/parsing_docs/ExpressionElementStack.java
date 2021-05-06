import java.util.*;

/**
 * This is a simple extension of {@link java.util.Stack} which is designed specifically
 * to hold {@link ExpressionElement ExpressionElements}. The major additions are
 * {@link #nextElement}
 * which returns an {@link ExpressionElement} as opposed the {@link java.lang.Object}
 * that is usually returned. The same goes for {@link #top} which adds the same typecasting
 * for {@link java.util.Stack#peek}.
 * <br><br>
 * Other functionality added was the capacity to {@link #reverse} the elements in the stack
 * and to {@link #dump} the information in the stack to {@link System#out}.
 * 
 * @author Will Holcomb
 */
public class ExpressionElementStack extends java.util.Stack
{
	/**
	 * Removes the top element from the stack and returns it
	 *
	 * @return the top of the stack
	 */
	public ExpressionElement nextElement()
	{
		return (ExpressionElement)pop();
	}

	/**
	 * Check the top element from the stack and returns it; the top
	 * remains on the stack.
	 *
	 * @return the top of the stack
	 */
	public ExpressionElement top()
	{
		return (ExpressionElement)peek();
	}

	/**
	 * Reverses the order of the elements in the stack. The algorithm used is this:
	 * <code>
	 * <ul>
	 *	<li>n = the size of the stack</li>
	 *	<li>i = 1</li>
	 *	<li>while i < n - i</li>
	 *	<ul>
	 *		<li>swap elements i and n - i</li>
	 *		<li>i = i + 1</li>
	 *	</ul>
	 * </ul>
	 * </code>
	 * <br>
	 * This method is useful because sometimes when a stack is being created it ends
	 * up being created in the reverse order than what it needs to be in to be useful.
	 * (Like {@link ArithmeticEquation#ArithmeticEquation} for example.) A stack is
	 * a <b>F</b>irst <b>I</b>n <b>L</b>ast <b>O</b>ut structure and what is needed
	 * sometimes is a <b>L</b>ast <b>I</b>n <b>L</b>ast <b>O</b>ut structure. In order
	 * to get one I could either create a new queue type or add this method to stack.
	 * This was by far the more efficient solution.
	 */
	public void reverse()
	{
		Object tempObject;
		int i = 0;
		int n = size();

		while(i < n - i - 1)
		{
			tempObject = elementAt(i);
			setElementAt(elementAt(n - i - 1), i);
			setElementAt(tempObject, n - i - 1);
			i++;
		}
	}

	/**
	 * Prints the elements of the stack to {@link java.lang.System#out}. Each element is
	 * on its own line and includes the {@link ExpressionElement#typeString} and a 
	 * {@link java.lang.String string} representation of the element.
	 * <br><br>
	 * This function is useful for debugging.
	 */
	public void dump()
	{
		Enumeration tempEnumeration = elements();
		ExpressionElement currentElement;

		if(size() == 0)
			System.out.println("Stack is empty.");
		else
			System.out.println("Expression consists of:");

		while(tempEnumeration.hasMoreElements())
		{
			currentElement = (ExpressionElement)tempEnumeration.nextElement();
			System.out.print("\t" + currentElement.typeString());
			if(currentElement.type == ExpressionElement.VALUE)
				System.out.print(" [" + ((Integer)currentElement.content).intValue() + "]");
			else if(currentElement.type == ExpressionElement.OPERATOR)
				System.out.print(" [" + ((Operator)currentElement.content).token + "]");
			else if(currentElement.type == ExpressionElement.GROUPING_SYMBOL)
				System.out.print(" [" + ((GroupingSymbol)currentElement.content).token() + "]");
			else if(currentElement.type == ExpressionElement.VARIABLE)
				System.out.print(" [" + ((Variable)currentElement.content).name + "]"
				 + " [" + ((Variable)currentElement.content).valueOf() + "]");
			else
				System.out.print(" [" + "ERROR" + "]");

			System.out.println("");
		}
	}
}