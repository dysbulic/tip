/**
 * Much like {@link OperatorTable} this call serves as a holder for a set of other objects
 * and acts as an interface to allow use of them. In this case the objects are
 * {@link GroupingSymbol GroupingSymbols}.
 * <br><br>
 * The most common usage of {@link GroupingSymbolTable} is {@link GenericGroupingSymbolTable}.
 *
 * @author WillHolcomb
 */
public class GroupingSymbolTable
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
	protected GroupingSymbol [] symbols;

	/**
	 * Creates a new empty table with a capacity of 20 elements
	 */
	public GroupingSymbolTable()
	{
		this(20);
	}

	/**
	 * Creates a new empty table with a capacity of i elements.
	 * If i is 0 then the capacity is set to 1.
	 */
	public GroupingSymbolTable(int i)
	{
		capacity = i;

		if(capacity == 0)
			capacity++;

		symbols = new GroupingSymbol[capacity];
	}

	/**
	 * Adds a {@link GroupingSymbol} to the table. If there is not room in the table for
	 * the symbol then (in the fashion of {@link java.util.Vector}) the capacity is
	 * doubled thereby making space.
	 *
	 * @param o symbol to be added
	 */
	public void add(GroupingSymbol o)
	{
		if(size == capacity)
		{
			GroupingSymbol[] tempTable = new GroupingSymbol[capacity * 2];
			for(int i = 0; i < size; i++)
				tempTable[i] = symbols[i];
			symbols = tempTable;
			capacity *= 2;
		}

		symbols[size] = o;
		size++;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> as being a symbol in the table or not.
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return <code>true</code> if <code>token</code> is in the table
	 */
	public boolean isAGroupingSymbol(String token)
	{
		boolean found = false;

		for(int i = 0; i < size && !found; i++)
			if(symbols[i].openToken.equals(token)
			 || symbols[i].closeToken.equals(token))
				found = true;

		return found;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> as being an opening symbol or not.
	 * If <code>token</code> is not in the table then a {@link NotATableElementExcepetion}
	 * will be thrown (once I get that running.)
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return <code>true</code> if <code>token</code> may begin a group
	 */
	public boolean isAnOpeningSymbol(String token)
	{
		boolean found = false;

		for(int i = 0; i < size && !found; i++)
			if(symbols[i].openingSymbol
			 && symbols[i].openToken.equals(token))
				found = true;

		return found;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> as being an closing symbol or not.
	 * If <code>token</code> is not in the table then a {@link NotATableElementExcepetion}
	 * will be thrown (once I get that running.)
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return <code>true</code> if <code>token</code> may end a group
	 */
	public boolean isAClosingSymbol(String token)
	{
		boolean found = false;

		for(int i = 0; i < size && !found; i++)
			if(!symbols[i].openingSymbol
			 && symbols[i].closeToken.equals(token))
				found = true;

		return found;
	}

	/**
	 * Identifies {@link java.lang.String} <code>token</code> and returns the appropriate
	 * {@link GroupingSymbol}.
	 * If <code>token</code> is not in the table then a {@link NotATableElementExcepetion}
	 * will be thrown (once I get that running.)
	 *
	 * @param token {@link java.lang.String} to be identified
	 * @return the {@link GroupingSymbol} for <code>token</code>.
	 */
	public GroupingSymbol symbolFor(String token)
	{
		int index = 0;
		boolean found = false;

		for(index = 0; index < size && !found; index++)
			if(symbols[index].openingSymbol
			 && symbols[index].openToken.equals(token))
				found = true;
			else if(!symbols[index].openingSymbol
			 && symbols[index].closeToken.equals(token))
				found = true;

		if(found)
			return symbols[index - 1];
		else
			return null;
	}

	/**
	 * @return a {@link java.lang.String} array contianing the tokens for the
	 * {@link GroupingSymbol GroupingSymbols} in the table.
	 */
	public String [] getTokens()
	{
		String [] out = new String [size];

		for(int i = 0; i < size; i++)
			if(symbols[i].openingSymbol)
				out[i] = symbols[i].openToken;
			else
				out[i] = symbols[i].closeToken;

		return out;
	}

	/**
	 * Checks if an infix expression is balanced. An expression has balanced grouping symbols
	 * if every grouping symbol that is opened is closed and each closing symbol closes the most
	 * recent opening symbol.
	 * <br><br>
	 * For example, ((x + y) / 3) * 5 is balanced because there are two open parens '(' and two
	 * closes and each close works on the most recently opened symbol. ([x + y] / 3) * 5 is
	 * balanced for the same reason, but ([x + y) / 3] * 5 is not because when the closing
	 * symbol ')' is reached the current open symbol is ']' and the two don't match. Similarly
	 * ([x + y] / 3 * 5 is not valid because there is no close for the initial '('.
	 *
	 * @param s infix equation to check the balance of
	 * @return whether <code>s</code> is balanced or not
	 */
	public boolean isBalanced(String s)
	{
		AdaptedStringTokenizer tokens = new AdaptedStringTokenizer(s, getTokens());
		String currentToken;
		boolean balanced = true;
		ExpressionElementStack parens = new ExpressionElementStack();

		while(tokens.hasMoreTokens() && balanced)
		{
			currentToken = tokens.nextToken();

			if(isAnOpeningSymbol(currentToken))
				parens.push(symbolFor(currentToken));
			else if(isAClosingSymbol(currentToken))
				if(parens.empty()
				 || !((GroupingSymbol)parens.nextElement().content).closeToken.equals(currentToken))
					balanced = false;
		}

		if(!parens.empty())
			balanced = false;

		return balanced;
	}

/*	public boolean balance(String s)
	{
		AdaptedStringTokenizer tokens = new AdaptedStringTokenizer(s, getTokens());
		String currentToken;
		boolean balanced = true;
		ExpressionElementStack parens = new ExpressionElementStack();
		StringBuffer out = new StringBuffer();
		StringBuffer holding = new StringBuffer();
		GroupingSymbol currentOpenSymbol;

		while(tokens.hasMoreTokens() && balanced)
		{
			currentToken = tokens.nextToken();

			if(isAnOpeningSymbol(currentToken))
			{
				if(currentOpenSymbol != null)
					out.append(currentOpenSymbol.token());

				currentOpenSymbol = symbolFor(currentToken);
				out.append(holding.toString());
				holding.clear();
			}
			else if(isAClosingSymbol(currentToken))
			{
				if(parens.empty()
				 || !currentOpenSymbol.closeToken.equals(currentToken))
					;
			}
		}

		if(!parens.empty())
			balanced = false;

		return balanced;
	}
*/}