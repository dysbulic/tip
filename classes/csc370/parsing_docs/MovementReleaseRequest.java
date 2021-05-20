import java.util.*;

/**
 * Requests that the listener put down any object that it may be holding
 *
 * @author Will Holcomb
 */
public class MovementReleaseRequest extends MovementRequestEvent
{
	/**
	 * Calls {@link #MovementReleaseRequest(Object, String)} with an empty
	 * {@link String}.
	 *
	 * @param creator the object which instantiated this class
	 */
	public MovementReleaseRequest(Object creator)
	{
		this(creator, new String(""));
	}

	/**
	 * This command takes no arguments.
	 *
	 * @param creator the object which instantiated this class
	 * @param arguments the arguments which are to be processed
	 */
	public MovementReleaseRequest(Object creator, String arguments)
	{
		super(creator, MovementRequestEvent.RELEASE);
		numberArguments = 0;
		arg = new int[numberArguments];
		StringTokenizer args = new StringTokenizer(arguments);

		if(args.countTokens() > numberArguments)
			System.out.println("Too many arguments in line \""
			 + arguments + "\"");
	}
}