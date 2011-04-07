package org.himinbi.app;

import org.himinbi.media.protocol.c3d.*;
import org.himinbi.media.util.*;
import com.sun.j3d.utils.applet.*;
import javax.media.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import com.sun.j3d.utils.universe.*;

public class SceneViewer extends JApplet implements ActionListener {
    Scene scene;
    StreamingCanvas3D canvas = new StreamingCanvas3D(SimpleUniverse.getPreferredConfiguration());
    DataSource source = new DataSource(canvas);
    MovieMaker movieMaker = new MovieMaker(source);
    JCheckBoxMenuItem recordingOption;
    JCheckBoxMenuItem animateOption;
    SceneSettings settings;
    JFileChooser chooser = new JFileChooser();
    
    {
	View view = new View();
	view.setPhysicalBody(new PhysicalBody());
	view.setPhysicalEnvironment(new PhysicalEnvironment());
	view.setBackClipDistance(1000000);
	view.addCanvas3D(canvas);
	
	settings = new SceneSettings(canvas);
	scene = new Scene(canvas, settings);
	scene.makeLive();
	view.attachViewPlatform(scene.getViewPlatform());
	
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	getContentPane().setLayout(layout);
	
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	
	JPopupMenu.setDefaultLightWeightPopupEnabled(false);
	
	JMenuBar menuBar = new JMenuBar();
	setJMenuBar(menuBar);
	
	JMenu menu = new JMenu("Options");
	menu.setMnemonic(KeyEvent.VK_O);
	menu.getAccessibleContext().setAccessibleDescription("Change stuff");
	menuBar.add(menu);

	JMenuItem menuItem;

	animateOption = new JCheckBoxMenuItem("Animate", true);
	animateOption.setMnemonic(KeyEvent.VK_A);
	animateOption.setActionCommand("Animate");
	animateOption.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_A, ActionEvent.ALT_MASK));
	animateOption.getAccessibleContext().setAccessibleDescription("Animate the earth");
	animateOption.addActionListener(this);
	menu.add(animateOption);

	menuItem = new JMenuItem("Settings", KeyEvent.VK_S);
	menuItem.setActionCommand("Settings");
	menuItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_S, ActionEvent.ALT_MASK));
	menuItem.getAccessibleContext().setAccessibleDescription("Change the world");
	menuItem.addActionListener(this);
	menu.add(menuItem);

	recordingOption = new JCheckBoxMenuItem("Record", false);
	recordingOption.setMnemonic(KeyEvent.VK_R);
	recordingOption.setActionCommand("Record");
	recordingOption.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_R, ActionEvent.ALT_MASK));
	recordingOption.getAccessibleContext().setAccessibleDescription("Record the scene");
	recordingOption.addActionListener(this);
	menu.add(recordingOption);

	menuItem = new JMenuItem("Exit", KeyEvent.VK_X);
	menuItem.setActionCommand("Exit");
	menuItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_X, ActionEvent.ALT_MASK));
	menuItem.getAccessibleContext().setAccessibleDescription("Leave");
	menuItem.addActionListener(this);
	menu.add(menuItem);

	layout.setConstraints(canvas, gridbag);
	getContentPane().add(canvas);
    }
    
    public void actionPerformed(ActionEvent e) {
	String actionCommand = e.getActionCommand();
	if(actionCommand.equals("Exit")) {
	    System.exit(0);
	} else if(actionCommand.equals("Settings")) {
	    settings.setVisible(true);
	} else if(actionCommand.equals("Record")) {
	    if(recordingOption.getState() == true) {
		if(chooser.showDialog(this, null) == JFileChooser.APPROVE_OPTION) {
		    //movieMaker.setOutputLocator(new MediaLocator("file://" + chooser.getSelectedFile().getPath()));
		} else {
		    recordingOption.setState(false);
		}
	    }
	    movieMaker.setOutputLocator(new MediaLocator("file://c:/temp/test.mov"));
	    movieMaker.setRecording(recordingOption.getState());
	} else if(actionCommand.equals("Animate")) {
	    scene.setAnimated(animateOption.getState());
	} else {
	    System.out.println("Action: " + actionCommand);
	}
    }
    
    public static void main(String[] args) {
	new MainFrame(new SceneViewer(), 400, 400);
    }
}
