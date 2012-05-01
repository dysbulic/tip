import java.util.*;

/**
 * Requests that the listener change the openness of the claw.
 *
 * @author Will Holcomb
 */
public class MovementClawchangeRequest extends MovementRequestEvent
{
	/**
	 * Calls {@link #MovementClawchangeRequest(Object, String)} with an empty
	 * {@link String}. This is essentially a request for inaction.
	 *
	 * @param creator the object which instantiated this class
	 */
	public MovementClawchangeRequest(Object creator)
	{
		this(creator, new String(""));
	}

	/**
	 * There is one argument, which is positive for opening the claw and
	 * negative to close.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementClawchangeRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.CLAWCHANGE);
		numberArguments = 1;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments, ",");
		String currentString;

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
		else
			arg[0] = (new ArithmeticEquation(args.nextToken())).solution();
	}
}