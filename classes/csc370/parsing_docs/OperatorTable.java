/**
 * Much like {@link GroupingSymbolTable} this call serves as a holder for a set of other objects
 * and acts as an interface to allow use of them. In this case the objects are
 * {@link Operator Operators}.
 * <br><br>
 * The most common usage of {@link OperatorTable} is {@link ArithmeticOperatorTable}.
 *
 * @author WillHolcomb
 */
public class OperatorTable
{
	/**
	 * The current maximum number of elements possible
	 */
	protected int capacity = 0;

	/**
	 * The current number of elements
	 */
	protected int size = 0;

	/**
	 * The elements contained in the table
	 */
	protected Operator [] operators;

	/**
	 * Creates a new empty table with a capacity of 20 elements
	 */
	public OperatorTable()
	{
		this(20);
	}

	/**
	 * Creates a new empty table with a capacity of i elements.
	 * If i is 0 then the capacity is set to 1.
	 */
	public OperatorTable(int i)
	{
		capacity = i;

		if(capacity == 0)
			capacity++;

		operators = new Operator[capacity];
	}

	/**
	 * Adds an {@link Operator} to the table. If there is not room in the table for
	 * the symbol then (in the fashion of {@link java.util.Vector}) the capacity is
	 * doubled thereby making space.
	 *
	 * @param o symbol to be added
	 */
	public void add(Operator o)
	{
		if(size == capacity)
		{
			Operator[] tempTable = new Operator[capacity * 2];
			for(int i = 0; i < size; i++)
				tempTable[i] = operators[i];
			operators = tempTable;
			capacity *= 2;
		}

		operators[size] = o;
		size++;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> as being an
	 * {@ Operator} in the table or not based on the context given by
	 * <code>preceededByAnOperand</code>.
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return <code>true</code> if <code>token</code> is in the table
	 */
	public boolean isAnOperator(String token, boolean preceededByAnOperand)
	{
		boolean found = false;

		for(int i = 0; i < size && !found; i++)
		{
			if(operators[i].token.equals(token)
			   && operators[i].preceededByAnOperand == preceededByAnOperand)
				found = true;
		}

		return found;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> and returns the appropriate
	 * {@link Operator}.
	 * If <code>token</code> is not in the table then a {@link NotATableElementExcepetion}
	 * will be thrown (once I get that running.)
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return the {@link Operator} for <code>token</code>.
	 */
	public Operator operatorFor(String token, boolean preceededByAnOperand)
	{
		int index = 0;
		boolean found = false;

		for(index = 0; index < size && !found; index++)
			if(operators[index].token.equals(token)
			   && operators[index].preceededByAnOperand == preceededByAnOperand)
				found = true;

		if(found)
			return operators[index - 1];
		else
			return null;
	}

	/**
	 * @return a {@link java.lang.String} array contianing the tokens for the
	 * {@link Operator Operators} in the table.
	 */
	public String [] getTokens()
	{
		String [] out = new String [size];

		for(int i = 0; i < size; i++)
			out[i] = operators[i].token;

		return out;
	}
}