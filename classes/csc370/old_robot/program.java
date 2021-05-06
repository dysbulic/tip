/*

JBOT Web-Based Robot Arm simulator

By Andy Trent, Michael Sandt, and Lewis Baumstark

Problem Statement
---------------------------

	This Java Applet allows the user to manipulate a 2-dimensional
robot arm by typing in a script of assembly-style commands.  The arm has 3
degrees of movement:  2 joints on the arm itself and a 360-degree swivel
at its base.  At the end of the arm is a claw that can be opened and
closed.  In one window is the side view of the arm, consisting of the
two arm lengths and the claw.  In the second graphics winder, the base of
the arm is represented as a circle with a marker denoting which direction
it is facing.  Two text boxes, one for typing in commands and another for
displaying tracing information about the executing script, will be
available.  A "Submit" button will be pressed, via mouse, when a series of
commands is to be executed.  A "Clear" button is provided for each box
to clear out the text contained therein.  Finally, a "Reset" button is
provided to return the applet to its starting state.


Commands
---------------

	A complete description of the commands can be found in the file
JBOTlang.html, available on the JBOT distribution disk or at
http://www.csc.tntech.edu/~lbbaumst/doc/JBOTlang.html.


Member Responsibilities
----------------------------

Michael Sandt:  Interface design and programming
Andy Trent:  Graphics engine design and programming
Lewis Baumstark:  Command parsing outines and language design

*/

import java.applet.Applet;
import java.awt.*;
import java.net.*;
import base;
import robot;

public class program extends Applet
/* program.class
	written by Michael Sandt
This is the class that holds interface, and all the other components are
tied in through it. */

{

	private CommandInterpreter CI = new CommandInterpreter();
		//this takes care of processing the code

	private TextArea ta1,ta2;
	private TextField tf1,tf2,tf3,tf4;
	private Button b1,b2,b4,b5;
	private Panel p1;
	private GridBagLayout gbLayout;
	private GridBagConstraints gbConstraints;
  	public URL docs; 
	

	public void init()
	// sets up the interface initially and adds all the componemts

	{
		gbLayout =new GridBagLayout();
		setLayout(gbLayout);

		gbConstraints=new GridBagConstraints();

		ta1=new TextArea("Text Area 1",8,50);
		ta2=new TextArea("Text Area 2",8,50);
		ta2.setEditable(false);

		p1=new Panel();
		p1.setLayout (new GridLayout(1,6));
		

		tf1=new TextField("Type your code here:");
		tf1.setEditable(false);
		tf2=new TextField("Output occurs here:");
		tf2.setEditable(false);
		tf3=new TextField("Front view of arm:");
		tf3.setEditable(false);
		tf4=new TextField("Top view of arm:");
		tf4.setEditable(false);

		b1=new Button("Submit");
		b2=new Button("Clear");
		
		b4=new Button("Clear");
		b5=new Button("Reset JBOT");
		
		p1.add(b1);
		p1.add(b2);
		
		p1.add(b4);
		p1.add(b5);
		

		//c1=new robot();
		CI.r.init();
		CI.r.setBackground(Color.black);
		CI.r.resize(100,200);

		//c2=new base();
		CI.b.setBackground(Color.black);
		CI.b.resize(100,200);

		gbConstraints.fill=GridBagConstraints.BOTH;
		addComponent(tf3,gbLayout,gbConstraints,0,0,3,1);
		addComponent(tf4,gbLayout,gbConstraints,0,3,3,1);
		addComponent(CI.r,gbLayout,gbConstraints,1,0,3,1);
		addComponent(CI.b,gbLayout,gbConstraints,1,3,3,1);
		addComponent(tf1,gbLayout,gbConstraints,2,0,3,1);
		addComponent(tf2,gbLayout,gbConstraints,2,3,3,1);
		addComponent(ta1,gbLayout,gbConstraints,3,0,3,1);
		addComponent(ta2,gbLayout,gbConstraints,3,3,3,1);
		addComponent(p1,gbLayout,gbConstraints,4,0,6,1);
		}

	private void addComponent(Component c, GridBagLayout g,
							GridBagConstraints gc, int row,
							int column,int width,int height)
	//function to add a component to the applet.  It takes a component
	//object, layout, set of grid-bag constraints, row, column, width
	//and height.

	{
		gc.gridx=column;
		gc.gridy=row;
		gc.gridwidth=width;
		gc.gridheight=height;
		g.setConstraints(c,gc);
		add(c);
	}

	public boolean action(Event e, Object o)
	//method to process events for the applet.  Takes an event and an
	//object.

	{
		if(e.target == b1)
		{
			showStatus("Submitting your code.");
			CI.loadScript(ta1.getText());
			CI.parseScript();

			ta2.appendText(CI.tracer.toString());
			CI.tracer.setLength(0);
		}
		else if(e.target==b2)
		{
			showStatus("Clearing the text area....");
			ta1.setText("");
		}
		else if(e.target==b4)
		{
			showStatus("Clearing output area...");
			ta2.setText("");
		}
		else if(e.target==b5)
		{
			showStatus("Reset JBOT");
			CI.reset();
		}
		return true;
	}
}

