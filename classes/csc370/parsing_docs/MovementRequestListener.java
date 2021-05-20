/**
 * Represents that a class has the capacity to process {@link MovementRequestEvent MovementRequestEvents}.
 *
 * @author Will Holcomb
 */
public interface MovementRequestListener extends java.util.EventListener
{
	public abstract void movementRequested(MovementRequestEvent e);
}
