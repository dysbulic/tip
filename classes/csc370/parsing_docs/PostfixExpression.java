import java.util.*;

/**
 * Represents a postfix equation. Equations are commonly of one of three types:
 * infix, postfix, or prefix. The different types have to do with the placement
 * of the operators in relation to the operands. In infix, X + Y, the operator +
 * is in between the operands that it operates on X and Y. In prefix, + X Y, the
 * operator + preceedes the operands X and Y. In postfix, which this class represents,
 * in X Y + the operator + postcedes the operators X and Y. So, + X Y (prefix) is the
 * same as X + Y (infix) is the same as X Y + (postfix).
 * <br><br>
 * Postfix notation, unlike infix does not use any grouping symbols. Any equation may
 * be represented because also unlike infix notation there is no order of operations;
 * an operation is preformed when it is reached.
 * <br><br>
 * For more information on postfix equations, specifically how to solve them, see the
 * documentation for the {@link #solution} method.
 *
 * @author Will Holcomb
 */
public abstract class PostfixExpression implements Equation
{
	/**
	 * Stack representing a postfix equation. The top element is the beginning of the
	 * equation.
	 */
	protected ExpressionElementStack expression;

	/**
	 * Table of the allowed operators for this equation. This class is used both to
	 * parse the incoming infix expression in {@link #setEquation} and then to find
	 * the solution in {@link #solution}.
	 * <br><br>
	 * The most commonly used subclass that is used here is {@link ArithmeticOperatorTable}
	 * though {@link ConditionalOperatorTable} does as well.
	 */
	OperatorTable op;

	/**
	 * Table of the allowed grouping symbols for the equation.
	 * <br><br>
	 * The most common subclass used here is {@link GenericGroupingSymbolTable}.
	 */
	GroupingSymbolTable gt;

	/**
	 * flag which toggles a set of dumps of the different stacks. This information can
	 * be used for debugging. The dumps go to {@link java.lang.System#out}.
	 */
	protected boolean TESTING = false;

	/**
	 * String representing the infix form of the current expression.
	 */
	String infixString;

	/**
	 * This constructor does nothing whatsoever. It is necessary so that subclasses
	 * may call it, the class will not function however unless the subclass generates
	 * an {@link ExpressionElementStack} and calls {@link #setEquation}.
	 */
	protected PostfixExpression()
	{
	}

	/**
	 * This method sets the content of the expression. The algorithm used to translate
	 * an infix expression (which the {@link ExpressionElementStack} infixExpression
	 * represents) to a postfix one is this: (for an explanation of the difference
	 * between infix and postfix see the introduction to this class.)
	 * <br><br>
	 * <code>
	 * <ul>
	 *	<li>
	 *	 input stack SI which is a stack of {@link ExpressionElement ExpressionElements}
	 *	 representing an infix equation
	 *	</li><li>
	 *	 create a new {@link ExpressionElementStack} SP representing the new postfix
	 *	 expression.
	 *	</li><li>
	 *	 Create a new {@link ExpressionElementStack} SO to hold operators temporarily
	 *	</li><li>
	 *	 while SI is not empty
	 *	 <ul>
	 *		<li>
	 *		 if the top of SI is an operator
	 *		 <ul>
	 *			<li>
	 *			 while SO is not empty and the top of SO is of equal or higher
	 *			 precedence than the top of SI
	 *			 <ul>
	 *				<li>pop SO and place the operator on SP</li>
	 *			 </ul>
	 *			</li>
	 *			<li>pop SI and place the operator on SO</li>
	 *		 </ul>
	 *		</li>
	 *		<li>
	 *		 else if the top of SI is a gropuping symbol
	 *		 <ul>
	 *			<li>
	 *			 if the top of SI is an opening symbol
	 *			 <ul><li>pop SI and push the grouping symbol on SO</li></ul>
	 *			</li>
	 *			<li>
	 *			 else
	 *			 <ul>
	 *				<li>
	 *				 while SO is not empty and the top of SO is not the closing symbol
	 *				 for the top of SI
	 *				</li>
	 *				<ul><li>pop SO and place the operator on SP</li></ul>
	 *				<li>pop SI and throw away the closing symbol</li>
	 *
	 *			 </ul>
	 *			</li>
	 *		 </ul>
	 *		</li>
	 *		<li>
	 *		 else
	 *		 <ul><li>pop SI and place the variable or value in SP</li></ul>
	 *		</li>
	 *	 </ul>
	 *	</li>
	 *	 <ul>
	 *		<li>while SO is not empty</li>
	 *		<ul><li>pop SO and push the operator on SP</li></ul>
	 *	 </ul>
	 * </ul>
	 * <br>
	 * SP now contains the expression elements in the proper order for a postfix version of SI
	 * </code>
	 * <br>
	 * For information on how an infix expression stack is generated see
	 * {@link ArithmeticEquation#ArithmeticEquation}.
	 * <br>
	 * For information about how a postfix expression is solved by a computer see the {@link #solution}
	 * method of this class.
	 *			
	 * @param infixExpression stack representing an infix expression
	 */
	public void setEquation(ExpressionElementStack infixExpression)
	{
		expression = new ExpressionElementStack();
		ExpressionElement currentElement;
		ExpressionElement tempElement;
		ExpressionElementStack operatorStack = new ExpressionElementStack();

		if(TESTING)
			infixExpression.dump();

		while(!infixExpression.empty())
		{
			currentElement = infixExpression.nextElement();

			if(TESTING)
				System.out.println("I get here; processing: \"" + currentElement.toString() + "\"");

			switch(currentElement.type)
			{
				case (ExpressionElement.OPERATOR):
					while(!operatorStack.empty()
					 && operatorStack.top().type != ExpressionElement.GROUPING_SYMBOL
					 && ((Operator)currentElement.content).isLowerPrecedence((Operator)operatorStack.top().content))
						expression.push(operatorStack.nextElement());

					operatorStack.push(currentElement);
					break;
				case (ExpressionElement.GROUPING_SYMBOL):
					if(((GroupingSymbol)currentElement.content).isAnOpeningSymbol())
					{
						operatorStack.push(currentElement);
					}
					else
					{
						boolean closed = false;

						while(!operatorStack.empty() && !closed)
						{
							tempElement = operatorStack.nextElement();

							if(tempElement.type == ExpressionElement.OPERATOR)
							{
								expression.push(tempElement);
							}
							else if(tempElement.type == ExpressionElement.GROUPING_SYMBOL)
							{
								if(((GroupingSymbol)tempElement.content).isCloseFor
								 (((GroupingSymbol)currentElement.content)))
									closed = true;
								else
									System.out.println("All is not well in the grouping world.");
							}
						}
					}
					break;
				case (ExpressionElement.VALUE): case (ExpressionElement.VARIABLE):
					expression.push(currentElement);
					break;
				case (ExpressionElement.OTHER): default:
					System.out.println("Error in postfix parsing: " + currentElement.typeString());
					break;
			}
		}

		while(!operatorStack.empty())
		{
			currentElement = operatorStack.nextElement();

			if(currentElement.type == ExpressionElement.OPERATOR)
			{
				expression.push(currentElement);
			}
			else
			{
				System.out.println("Error in operator stack of postfix parsing:");
				System.out.println("\t" + currentElement.typeString());
			}
		}
	}

	/**
	 * This method returns the solution for the equation. The method that it used to compute the
	 * solution is this:
	 * <code>
	 * <ul>
	 *	<li>
	 *	 input the expression (in this case, the expression is an {@link Enumeration}
	 *	 of {@link ExpressionElementStack} {@link #expression};) called SI
	 *	</li>
	 *	<li>
	 *	 begin a new temporary {@link ExpressionElementStack} to hold the work on the
	 *	 solution; called SO
	 *	</li>
	 *	<li>while the input stack (SI) has more elements</li>
	 *	 <ul>
	 *		<li>if the top of SI is a {@link Variable}</li>
	 *		 <ul>
	 *			<li>pop SI</li>
	 *			<li>find the value for the variable</li>
	 *			<li>push that value on SO</li>
	 *		 </ul>
	 *		<li>else if the top of SI is a {@link java.lang.Integer}</li>
	 *		 <ul>
	 *			<li>pop SI</li>
	 *			<li>push that value on SO</li>
	 *		 </ul>
	 *		<li>else if the top of SI is an {@link Operator}</li>
	 *		 <ul>
	 *			<li>pop SI</li>
	 *			<li>
	 *			 pop SO enough times to get the number of operands needed
	 *			 to allow the operator taken off of SI to process
	 *			</li>
	 *			<li>operate on the operands popped</li>
	 *			<li>push the solution on SO</li>
	 *		 </ul>
	 *	</ul>
	 * </ul>
	 * </code>
	 * <br>
	 * Once this code is through processing then SO should contain a single value which is
	 * equal to the solution to the expression. If there is more than one value or if at any
	 * time an attempt is made to pop an empty stack then there was an error in the format
	 * of the equation.
	 * <br><br>
	 * The function currently returns 0 in the case of an error though soon it will throw
	 * an {@link InvalidExpressionException}.
	 *
	 * @return the integer solution to the postfix expression
	 */
	public int solution()
	{
		Enumeration elements = expression.elements();
		ExpressionElement currentElement;
		ExpressionElementStack numberStack = new ExpressionElementStack();

		while(elements.hasMoreElements())
		{
			currentElement = (ExpressionElement)elements.nextElement();

			switch(currentElement.type)
			{
				case (ExpressionElement.VALUE):
					numberStack.push(currentElement);
					break;
				case (ExpressionElement.VARIABLE):
					numberStack.push(new ExpressionElement(new Integer(
					 ((Variable)currentElement.content).valueOf())));
					break;
				case (ExpressionElement.OPERATOR):
					if(numberStack.size() >= ((Operator)currentElement.content).numberOperands)
					{
						int [] x = new int[((Operator)currentElement.content).numberOperands];

						for(int i = ((Operator)currentElement.content).numberOperands - 1; i >= 0; i--)
							x[i] = ((Integer)numberStack.nextElement().content).intValue();

						numberStack.push(new ExpressionElement(new Integer(
						 ((Operator)currentElement.content).operate(x))));
						break;
					}
				case (ExpressionElement.OTHER): default:
					System.out.println("Class is " + currentElement.getClass().getName());
					System.out.println("Error in postfix solution -- unknown element type: "
					 + currentElement.typeString());
					break;
			}
			if(TESTING)
				numberStack.dump();
		}

		if(numberStack.size() != 1)
		{
			System.out.print("Error dump (wrong stack size) -- ");
			numberStack.dump();
			return 0;
		}
		else
			return ((Integer)numberStack.nextElement().content).intValue();
	}

	/**
	 * @return a string representing the infix form of the postfix equation
	 */
	public String infixEquation()
	{
		return infixString;
	}

	/**
	 * @return a string representing the infix form of the postfix equation
	 */
	public String toString()
	{
		return infixString;
	}		
}
