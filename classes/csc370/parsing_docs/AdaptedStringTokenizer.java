/**
 * The <code>AdaptedStringTokenizer</code> class extends {@link java.util.StringTokenizer StringTokenizer}
 * to allow the user to use {@link java.lang.String strings} as tokens. The StringTokenizer takes a
 * {@link java.lang.String string} and a set of tokens and returns a set of {@link java.lang.String strings}
 * representing the original string divided according the the tokens. For example, a common set of tokens
 * is the whitespace characters. The string "the quick	brown  fox" would tokenize to: "the", "quick",
 * "brown", "fox". When the next token is called for any preceeding delimiters are skipped then from
 * the first character that isn't a delimiter characters are added to the out string until another token
 * is hit.
 *
 * @author Will Holcomb
 */
public class AdaptedStringTokenizer extends java.util.StringTokenizer
{
	/**
	 * boolean flag representing whether or not whitespace should be included in the strings
	 * that are returned
	 */
	public boolean allowWhitespace;

	/**
	 * boolean flag representing whether or not tokens should be included in the strings
	 * that are returned
	 */
	public boolean returnTokens;

	/**
	 * the current position of the StringTokenizer in the sting being processed
	 */
	protected int currentIndex;

	/**
	 * the maximum index of the string being processed
	 */
	protected int maxIndex;

	/**
	 * the string which is being tokenized
	 */
	protected String tokenString;

	/**
	 * the delimiters that determine where the string is divided
	 */
	protected String [] tokens;

	/**
	 * Constructs a tokenization of the given string.
	 *
	 * @param s the string to be toknized
	 * @param t the delimiters that are to be used to separate the tokens
	 */
	public AdaptedStringTokenizer(String s, String [] t)
	{
		this(s, t, false, true);
	}

	/**
	 * Constructs a tokenization of the given string.
	 *
	 * @param s the string to be toknized
	 * @param t the delimiters that are to be used to separate the tokens
	 * @param whitespace flag indicating whether or not to include whitespace in the returned tokens
	 */
	public AdaptedStringTokenizer(String s, String [] t, boolean whitespace)
	{
		this(s, t, whitespace, true);
	}

	/**
	 * Constructs a tokenization of the given string.
	 *
	 * @param s the string to be toknized
	 * @param t the delimiters that are to be used to separate the tokens
	 * @param whitespace flag indicating whether or not to include whitespace in the returned tokens
	 * @param tok flag indicating whether or not the delimiters should be returned as tokens
	 */
	public AdaptedStringTokenizer(String s, String [] t, boolean whitespace, boolean tok)
	{
		super("");
		tokenString = s;
		tokens = t;
		allowWhitespace = whitespace;
		returnTokens = tok;
		currentIndex = 0;
		maxIndex = tokenString.length();
	}

	/**
	 * Internal method to skip over delimiters in the string being tokenized until a
	 * non-delimiter is reached.
	 */
	protected void skipDelimiters()
	{
		if(!returnTokens)
		{
			int i = 0;
			int maxLength = 0;
			
			do
			{
				for(i = 0; i < tokens.length; i++)
					if(tokenString.startsWith(tokens[i], currentIndex))
						maxLength = Math.max(maxLength, tokens[i].length());

				currentIndex += maxLength;
			}
			while(maxLength > 0);
		}
	}

	/**
	 * Internal method to skip over whitespace (as defined in {@link
	 * Character#isWhitespace(char) Charater.isWhitespace}) characters in the string
	 * being tokenized until a non-whitespace character is reached.
	 */
	protected void skipWhitespace()
	{
		if(!allowWhitespace)
		{
			boolean finished = false;

			while((currentIndex < maxIndex) && !finished)
				if(Character.isWhitespace(tokenString.charAt(currentIndex)))
					currentIndex++;
				else
					finished = true;
		}
	}

	/**
	 * Internal method representing the state of the current substring left from the
	 * tokenization to this point.
	 *
	 * @return whether or not the current substring begins with a delimiter
	 */
	protected boolean startsWithToken()
	{
		boolean tokenFound = false;

		for(int i = 0; i < tokens.length && !tokenFound; i++)
			if(tokenString.startsWith(tokens[i], currentIndex))
				tokenFound = true;

		return tokenFound;
	}
	
	/**
	 * @return whether or not a subsequent call to {@link #nextToken() nextToken} will return
	 * an element
	 */
	public boolean hasMoreTokens()
	{
		skipDelimiters();
		skipWhitespace();
		return (currentIndex < maxIndex);
	}

	/**
	 * There are several different cases for what can be considered tokens depending on the
	 * states of different flags. The string tokenizer is represented internally as a string
	 * and then an index into the string representing the point to which it has been
	 * tokenized thus far. Under the default conditions the tokenizer is set not to return
	 * whitespace and it is set to return the delimiters. For this case the next token will
	 * forward the internal index while the current character is whitespace then if the
	 * substring begins with a delimiter then all the delimiters are checked and the longest
	 * is returned as the next token. Otherwise the index is forwarded until a delimiter is
	 * hit or more whitespace is hit. Changing the allow whitespace flag will keep the
	 * preceeding whitespace from being skipped and it will also make it so that tokens are
	 * only delimited by the delimiters. If the return tokens is turned off then at the
	 * beginning in addition to skipping whitespace any initial delimiters are also skipped.
	 *
	 * @return the next substring of the original string which meets the criteria as a token
	 */
	public String nextToken()
	{
		if(!hasMoreTokens())
		{
			System.out.println("Ran off the end of the string in AdaptedStringTokenizer");
			return null;
		}

		int start = currentIndex;
		boolean tokenCompleted = false;
		int maxLength = 0;

		for(int i = 0; i < tokens.length && returnTokens; i++)
			if(tokenString.startsWith(tokens[i], currentIndex))
			{
				maxLength = Math.max(maxLength, tokens[i].length());
				tokenCompleted = true;
			}

		currentIndex += maxLength;

		while(currentIndex < maxIndex && !tokenCompleted && !startsWithToken())
			if(!allowWhitespace
			 && Character.isWhitespace(tokenString.charAt(currentIndex)))
				tokenCompleted = true;
			else
				currentIndex++;

		return tokenString.substring(start, currentIndex);
	}

	/**
	 * Operates the same as {@link #nextToken() nextToken()} but before the processing
	 * is begun the set of delimiters is replaced.
	 *
	 * @param t the new set of delimiters
	 * @return the next substring of the original string which meets the criteria as a token
	 */
	public String nextToken(String [] t)
	{
		tokens = t;
		return nextToken();
	}
}