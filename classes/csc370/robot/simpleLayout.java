import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;
import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.universe.*;
import FactoryFloor;
import VALIIParser;

public class simpleLayout extends Applet {
/*Written by Andy Trent
	Variables: floor:  this is the floor of the factory where the robot sits and does
							its thing...
This is the layout...although I don't like it very much.  For some weird reason,
(I think having to do with a bug in awt) I can only get things to draw in the center
panel of border layouts so this program is just a border layout.  I think if I were to
redo it in swing, it would work better, but I don't know swing right now.*/

	private FactoryFloor floor = new FactoryFloor();

	public simpleLayout() {
	/*Written by Andy Trent
		Input: none
		Output: none
		Variables:  progInterface:  the object that will parse code and send commands
										wherever they need to go.
	This is the layout.  It just adds the floor and the parser, then adds a listener for
	the buttons and the necessary code to get any events (buttons being pushed) to the 
	correct place*/

		setLayout(new BorderLayout());
		add("Center", floor);
		final VALIIParser progInterface = new VALIIParser();
		progInterface.addMovementRequestListener(floor.George);
		add("West", progInterface);
		Button ParseButton = new Button("ParseButton");
		add("South", ParseButton);
		ParseButton.addActionListener(new ActionListener()
		{
			public void actionPerformed(ActionEvent e)
			{
				progInterface.processProgram();
			}
		});

	}
	public static void main(String[] blah) {
		new MainFrame(new simpleLayout(), 750, 500);
	}
}