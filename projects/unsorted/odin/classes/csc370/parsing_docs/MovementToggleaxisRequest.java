import java.util.*;

/**
 * Requests that a listener toggle a coordinate axis for one of its joints.
 *
 * @author Will Holcomb
 */
public class MovementToggleaxisRequest extends MovementRequestEvent
{
	/**
	 * There is one argument, which is the joint to be toggle the
	 * axis on.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementToggleaxisRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.TOGGLEAXIS);
		numberArguments = 1;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments);

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
		else
			arg[0] = (new ArithmeticEquation(args.nextToken())).solution();;
	}
}