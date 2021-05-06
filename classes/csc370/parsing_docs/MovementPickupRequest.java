import java.util.*;

/**
 * Requests that the listener pick up an object.
 *
 * @author Will Holcomb
 */
public class MovementPickupRequest extends MovementRequestEvent
{
	/**
	 * There is one argument, which is the identifier of the object
	 * to be picked up.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementPickupRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.PICKUP);
		numberArguments = 1;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments, ",");
		String currentString;

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
		else
			arg[0] = (new ArithmeticEquation(args.nextToken())).solution();;
	}
}