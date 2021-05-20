import java.util.EventListener;

/**
 * An event multicaster allows for multiple listeners to be registered for a single
 * broadcaster. The way that it works is this: each event multicaster is itself an
 * event listener and each  multicaster sends objects to two listeners. When a
 * listener is added to the multicaster the multicaster adds a second multicaster as
 * its second listener and then that multicaster sets the listenere being added as
 * its first listener (leaving the second slot open for the addition of another
 * multicaster so as to add another listener.
 * <br><br>
 * Essentially the process is the creation of a chain. When a new link is added to
 * the chain it travels to the end.
 * <br><br>
 * When an event is broadcast it simply travels down the chain being sent to each listener
 * along the way.
 *
 * @author Will Holcomb
 */
public class MovementRequestEventMulticaster extends java.awt.AWTEventMulticaster
	implements MovementRequestListener
{
	/**
	 * Creates a new multicaster. Recall from the introduction that a is
	 * an object wishing to recieve events and the second is simply another
	 * multicaster existing to allow chaining.
	 *
	 * @param a object listening
	 * @param b multicaster to chain
	 */
	protected MovementRequestEventMulticaster(EventListener a, EventListener b)
	{
		super(a, b);
	}

	/**
	 * Adds a listener <code>a</code> and multicaster <code>b</code> to the list.
	 * Remember that a {@link MovementRequestEventMulticaster} is a {@link MovementRequestListener}
	 *
	 * @param a object listening
	 * @param b multicaster to chain
	 */
	public static MovementRequestListener add
		(MovementRequestListener a, MovementRequestListener b)
	{
		return (MovementRequestListener)(addInternal(a, b));
	}

	/**
	 * Removes a listener <code>a</code> and multicaster <code>b</code> from the list.
	 * Remember that a {@link MovementRequestEventMulticaster} is a {@link MovementRequestListener}
	 *
	 * @param a object listening
	 * @param b multicaster
	 */
	public static MovementRequestListener remove
		(MovementRequestListener a, MovementRequestListener b)
	{
		return (MovementRequestListener)(removeInternal(a, b));
	}

	protected static EventListener addInternal
		(EventListener a, EventListener b)
	{
		if(a == null)
			return b;
		else if (b == null)
			return a;
		else
			return new MovementRequestEventMulticaster(a, b);
	}

	protected EventListener remove(EventListener l)
	{
		if(l == a)
			return b;
		else if (l == b)
			return a;
		else
		{
			EventListener a2 = removeInternal(a, l);
			EventListener b2 = removeInternal(b, l);

			if(a == a2 && b == b2)
				return this;
			else
				return addInternal(a2, b2);
		}
	}

	/**
	 * This is the implementation which allows this class to implement {@link MovementRequestListener}.
	 * A call to this begins the chain.
	 *
	 * @param e the event to broadcast
	 */
	public void movementRequested(MovementRequestEvent e)
	{
		if(a != null)
			((MovementRequestListener)a).movementRequested(e);
		if(b != null)
			((MovementRequestListener)b).movementRequested(e);
	}
}
