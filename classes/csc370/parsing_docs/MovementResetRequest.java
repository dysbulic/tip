import java.util.*;

/**
 * Requests that the listener reset to a default position
 *
 * @author Will Holcomb
 */
public class MovementResetRequest extends MovementRequestEvent
{
	/**
	 * Calls {@link #MovementResetRequest(Object, String)} with an empty
	 * {@link String}.
	 *
	 * @param creator the object which instantiated this class
	 */
	public MovementResetRequest(Object creator)
	{
		this(creator, new String(""));
	}

	/**
	 * This command takes no arguments.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementResetRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.RESET);
		numberArguments = 0;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments);

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
	}
}