import java.util.*;

/**
 * Requests that the listener rotate its joints.
 *
 * @author Will Holcomb
 */
public class MovementRotateRequest extends MovementRequestEvent
{
	/**
	 * Calls {@link #MovementRotateRequest(Object, String)} with an empty
	 * {@link String}.  This is essentially a request for inaction.
	 *
	 * @param creator the object which instantiated this class
	 */
	public MovementRotateRequest(Object creator)
	{
		this(creator, new String(""));
	}

	/**
	 * There are six arguments (contained in <code>arguments</code>)
	 * which is parsed using {@link StringTokenizer} using comma as
	 * the delimiter.
	 * <br><br>
	 * The arguments are a series of rotations for each of 6 joints. If
	 * there are not enough arguments then the remaining ones are filled
	 * with zeroes.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementRotateRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.ROTATE);
		numberArguments = 6;
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
				arg[count] = (new ArithmeticEquation(args.nextToken())).solution();
				count++;
			}

		for(;count < numberArguments; count++)
			arg[count] = 0;
	}
}