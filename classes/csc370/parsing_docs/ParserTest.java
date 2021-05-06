import java.awt.*;
import com.sun.j3d.utils.applet.MainFrame;
import java.awt.event.*;

/**
 * This is a driver program to test the {@link VALIIParser} class.
 *
 * @author Will Holcomb
 */
public class ParserTest extends java.applet.Applet implements MovementRequestListener
{
	protected Button parseButton = new Button("Parse");
	protected VALIIParser parser = new VALIIParser();

	public void init()
	{
		parseButton.addActionListener(new ActionListener()
		  {
			public void actionPerformed(ActionEvent e)
			{
				parser.processProgram();
			}
		  });

		parser.addMovementRequestListener(this);

		add(parser);
		add(parseButton);
	}

	public static void main(String[] args)
	{
		new MainFrame(new ParserTest(), 700, 700);
	}

	/**
	 * Required for the implementation of {@link MovementRequestListener}. All that this
	 * does is dump the nature and arguments of <code>e</code> to {@link System#out}.
	 */
	public void movementRequested(MovementRequestEvent e)
	{
		if(e.getID() == MovementRequestEvent.ROTATE)
		{
			System.out.print("Rotate request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else if(e.getID() == MovementRequestEvent.MOVETO)
		{
			System.out.print("Moveto request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else if(e.getID() == MovementRequestEvent.PICKUP)
		{
			System.out.print("Pickup request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else if(e.getID() == MovementRequestEvent.RELEASE)
		{
			System.out.print("Release request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else if(e.getID() == MovementRequestEvent.CLAWCHANGE)
		{
			System.out.print("Clawchange request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else if(e.getID() == MovementRequestEvent.RESET)
		{
			System.out.print("Reset request for ");
			for(int i = 0; i < e.numberArguments; i++)
				System.out.print(e.arg[i] + " ");
			System.out.println("");
		}
		else
			System.out.println("Request for unknown movement type");
	}
}