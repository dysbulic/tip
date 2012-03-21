import java.util.*;

/**
 * This class represents an arithmetic equation. For information on the storage method
 * see the {@link #ArithmeticEquation constructor} and
 * {@link PostfixExpression#setEquation(ExpressionElementStack)}. For information about
 * how the solution is found, see {@link PostfixExpression#solution}.
 *
 * @author Will Holcomb
 */
public class ArithmeticEquation extends PostfixExpression
{
	/**
	 * The calculating for the initialization of this class is fairly complicated.
	 * It recieves a string representing an infix equation which is then parsed using
	 * {@link AdaptedStringTokenizer}. The delimiters used in
	 * the parse are the tokens from {@link ArithmeticOperatorTable}
	 * and {@link GenericGroupingSymbolTable}. The algorithm used
	 * then is this:
	 * <br><br>
	 * <code>
	 * <ul>
	 * 	<li>create a new stack of expression elements SE</li>
	 * 	<li>while(there are more tokens)</li>
	 *	<ul>
	 *		<li>
	 *		 if the current token is an {@link Operator Operator}
	 *		 (as determined by the {@link OperatorTable#isAnOperator(String, boolean) isAnOperator}
	 *		 function in {@link OperatorTable})
	 * 		<br>
	 *		 push the appropriate {@link Operator} onto SE
	 * 		</li><li>
	 *		 else if the current token is a {@link GroupingSymbol}
	 *	 	 (as determined by the {@link GroupingSymbolTable#isAGroupingSymbol(String)}
	 *		 function in {@link GroupingSymbolTable GroupingSymbolTable})
	 *		<br>
	 *		 push the appropriate {@link GroupingSymbol GroupingSymbol} onto SE
	 *		</li><li>
	 * 		 else if the current token is a {@link java.lang.Integer Integer}
	 *	 	 (as determined by the {@link java.lang.Integer#parseInt(String) parseInt} function
	 *	 	 in {@link java.lang.Integer Integer})
	 *		<br>
	 *		 push the appropriate {@link java.lang.Integer Integer} onto SE
	 *		</li><li>
	 *		 else consider the current token to be a {@link Variable Variable}
	 *		<br>
	 *		 push the approprate {@link Variable Variable} onto SE
	 *		</li>
	 *	</ul>
	 *	<li>Reverse SE</li>
	 *	<li>
	 *	 Call {@link PostfixExpression#setEquation(ExpressionElementStack) setEquation} of
	 *	 {@link PostfixExpression PostfixExpression} with SE
	 *	</li>
	 * </ul>
	 * </code>
	 * After the while loop is through SE now contains all the information that was in the initial string,
	 * but all of the information has been identified and pushed into a stack. The only problem is that in
	 * the process of pushing it on the stack the order has been reversed, so the
	 * {@link ExpressionElementStack#reverse()} method
	 * is called on SE and SE is sent to the {@link PostfixExpression#setEquation(ExpressionElementStack)} of
	 * the superclass to be converted and stored in postfix form.
	 * <br><br>
	 * @see PostfixExpression#setEquation(ExpressionElementStack)
	 * @param i string representing an infox arithmetic equation
	 */
	public ArithmeticEquation(String i)
	{
		op = new ArithmeticOperatorTable();
		gt = new GenericGroupingSymbolTable();
		infixString = i;
		ExpressionElementStack tempStack = new ExpressionElementStack();
		int index = 0;

		String [] temp1 = op.getTokens();
		String [] temp2 = gt.getTokens();
		String [] tokens = new String [temp1.length + temp2.length];

		for(index = 0; index < temp1.length; index++)
			tokens[index] = temp1[index];
		for(index = 0; index < temp2.length; index++)
			tokens[index + temp1.length] = temp2[index];

		StringTokenizer iTokens = new AdaptedStringTokenizer(i, tokens);
		boolean lastWasAnOperand = false;
		String currentToken;

		while(iTokens.hasMoreTokens())
		{
			currentToken = iTokens.nextToken();

			if(op.isAnOperator(currentToken, lastWasAnOperand))
			{
				tempStack.push(new ExpressionElement(op.operatorFor(currentToken, lastWasAnOperand)));
				lastWasAnOperand = false;
			}
			else if(gt.isAGroupingSymbol(currentToken))
			{
				tempStack.push(new ExpressionElement(gt.symbolFor(currentToken)));
				
				if(gt.isAnOpeningSymbol(currentToken))
					lastWasAnOperand = false;
				else
					lastWasAnOperand = true;
			}
			else
			{
				try
				{
					tempStack.push(new ExpressionElement(
					 new Integer(Integer.parseInt(currentToken))));
				}
				catch(NumberFormatException e)
				{
					tempStack.push(new ExpressionElement(
					 new Variable(currentToken)));
				}
				lastWasAnOperand = true;
			}
		}

		tempStack.reverse();

		setEquation(tempStack);
	}
}