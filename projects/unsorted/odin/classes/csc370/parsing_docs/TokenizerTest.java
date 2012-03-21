import java.util.*;

/**
 * This is a driver program to test the {@link AdaptedStringTokenizer} class.
 *
 * @author Will Holcomb
 */
public class TokenizerTest
{
	public static void main(String[] agrs)
	{
		String i = new String("5 <= 3");
		StringTokenizer s = new StringTokenizer(i, "<<=>>==! ", true);

		while(s.hasMoreTokens())
			System.out.println("\"" + s.nextToken() + "\"");

		String [] tokens = new String [7];
		tokens[0] = new String("<");
		tokens[1] = new String("<=");
		tokens[2] = new String(">");
		tokens[3] = new String(">=");
		tokens[4] = new String("==");
		tokens[5] = new String("!=");
		tokens[6] = new String("!");

		s = new AdaptedStringTokenizer(i, tokens, true);

		while(s.hasMoreTokens())
			System.out.println("\"" + s.nextToken() + "\"");
	}
}