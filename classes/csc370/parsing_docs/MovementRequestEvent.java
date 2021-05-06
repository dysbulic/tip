import java.awt.AWTEvent;

/**
 * This is the class which serves to notify a {@link MovementRequestListener}
 * that a movement has been requested.
 */
public abstract class MovementRequestEvent extends java.awt.AWTEvent
{
	/**
	 * Simply a placeholder for the beginning of the identifiers
	 */
	public static final int MOVEMENT_FIRST = AWTEvent.RESERVED_ID_MAX + 1;

	/**
	 * Signifies that the event is a {@link MovementRotateRequest}.
	 */
	public static final int ROTATE = MOVEMENT_FIRST;

	/**
	 * Signifies that the event is a {@link MovementMovetoRequest}.
	 */
	public static final int MOVETO = MOVEMENT_FIRST + 1;

	/**
	 * Signifies that the event is a {@link MovementPickupRequest}.
	 */
	public static final int PICKUP = MOVEMENT_FIRST + 2;

	/**
	 * Signifies that the event is a {@link MovementReleaseRequest}.
	 */
	public static final int RELEASE = MOVEMENT_FIRST + 3;

	/**
	 * Signifies that the event is a {@link MovementClawchangeRequest}.
	 */
	public static final int CLAWCHANGE = MOVEMENT_FIRST + 4;

	/**
	 * Signifies that the event is a {@link MovementResetRequest}.
	 */
	public static final int RESET = MOVEMENT_FIRST + 5;

	/**
	 * Signifies that the event is a {@link MovementToggleaxisRequest}.
	 */
	public static final int TOGGLEAXIS = MOVEMENT_FIRST + 6;

	/**
	 * Simply a placeholder for the end of the identifiers
	 */
	public static final int MOVEMENT_LAST = MOVEMENT_FIRST + 6;

	/**
	 * The number of arguments that the command has
	 */
	public int numberArguments;

	/**
	 * The arguments to the command
	 */
	public int [] arg;

	/**
	 * Simply chains a call up to the superclass using the same information
	 *
	 * @param source the obsect which created this object
	 * @param id the identifier for the type of event
	 */
	public MovementRequestEvent(Object source, int id)
	{
		super(source, id);
	}
}
