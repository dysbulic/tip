import java.util.*;

/**
 * Requests that the listener move to a specific (X, Y, Z) coordinate.
 *
 * @author Will Holcomb
 */
public class MovementMovetoRequest extends MovementRequestEvent
{
	/**
	 * There are three arguments (contained in <code>arguments</code>)
	 * which is parsed using {@link java.util.StringTokenizer} using comma as
	 * the delimiter.
	 * <br><br>
	 * The arguments are a position in relation to the X-axis, Y-axis and Z-axis
	 * in cartesion coordinates.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementMovetoRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.MOVETO);
		numberArguments = 3;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments, ",");
		String currentString;

		int count = 0;

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
		else
			while(args.hasMoreElements())
			{
				arg[count] = (new ArithmeticEquation(args.nextToken())).solution();;
				count++;
			}

		for(;count < numberArguments; count++)
			arg[count] = 0;
	}
}