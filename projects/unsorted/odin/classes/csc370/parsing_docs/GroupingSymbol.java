/**
 * This class represents a grouping symbol in an equation. Grouping symbols are used
 * to change the order in which an infix equation is solved. Normally an equation is solved
 * it is solved left to right and according to the order of operations. Grouping symbols
 * allow one to specify that certain operations which would normally occur later in the
 * solving can occur earlier.
 * <br><br>
 * Parentheses are common grouping symbols for instance. The use of parentheses allows one
 * to change the meaning of 3 + 4 / 14 from 3 2/7 to (3 + 4) / 14 which is 7.
 * <br><br>
 * I am thinking that no further explanation is needed. This should have been covered in
 * fifth grade or so.
 *
 * @author Will Holcomb
 */
public class GroupingSymbol
{
	/**
	 * Symbol representing that a group has been begun
	 */
	public String openToken;

	/**
	 * Symbol representing that a group has been closed
	 */
	public String closeToken;

	/**
	 * flag telling whether or not this particular symbol instance was
	 * an open or a close
	 */
	public boolean openingSymbol;

	/**
	 * Calls {@link #GroupingSymbol(String, String, boolean)} with a default
	 * of the symbol being an opening symbol.
	 *
	 * @param o the opening symbol
	 * @param c the closing symbol
	 */
	public GroupingSymbol(String o, String c)
	{
		this(o, c, true);
	} 

	/**
	 * A {@link GroupingSymbol} is created not simply with the actual token
	 * that it represents but also the close for that symbol. This is done
	 * because a grouping symbol is worthless unless its close is known.
	 *
	 * @param o the opening symbol
	 * @param c the closing symbol
	 * @param opening flag telling whether this particular symbol represents
	 *  the open or the close for this pair
	 */
	public GroupingSymbol(String o, String c, boolean opening)
	{
		openToken = o;
		closeToken = c;
		openingSymbol = opening;
	}

	/**
	 * Tells whether o is the opposing symbol for this symbol.
	 *
	 * @param o the grouping symbol to be checked against
	 */
	public boolean isCloseFor(GroupingSymbol o)
	{
		if(openToken.equalsIgnoreCase(o.openToken)
		 || closeToken.equalsIgnoreCase(o.closeToken))
			return openingSymbol != o.openingSymbol;
		else
			return false;
	}

	/**
	 * Returns the appropriate token representing this grouping symbol.
	 * If the symbol is an open then {@link #openToken} is returned
	 * otherwise {@link #closeToken} is returned.
	 *
	 * @return the token for the current symbol
	 */
	public String token()
	{
		if(openingSymbol)
			return openToken;
		else
			return closeToken;
	}

	/**
	 * @return returns the value of {@link #token}
	 */
	public String toString()
	{
		return token();
	}

	/**
	 * Tells if the current symbol represents the beginning of a group
	 *
	 * @return <code>true</code> if the symbol begins a group
	 */
	public boolean isAnOpeningSymbol()
	{
		return openingSymbol;
	}

	/**
	 * Tells if the current symbol represents the close of a group
	 *
	 * @return <code>true</code> if the symbol closes a group
	 */
	public boolean isAClosingSymbol()
	{
		return !openingSymbol;
	}
}	