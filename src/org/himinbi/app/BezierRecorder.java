package org.himinbi.app;

import org.himinbi.media.protocol.c2d.*;
import org.himinbi.media.util.*;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import javax.swing.*;
import javax.media.*;
import java.io.IOException;

public class BezierRecorder extends JApplet implements ActionListener {
    BezierCanvas canvas = new BezierCanvas();
    DataSource source = new DataSource(canvas);
    MovieMaker movieMaker = new MovieMaker(source);

    {
	org.apache.log4j.BasicConfigurator.configure();
    }

    public BezierRecorder() {
	JMenuBar menuBar = new JMenuBar();
	setJMenuBar(menuBar);

	JMenu optionsMenu = new JMenu("Options");
	optionsMenu.setMnemonic(KeyEvent.VK_O);
	optionsMenu.getAccessibleContext().setAccessibleDescription("Canvas Options");
	menuBar.add(optionsMenu);

	JMenuItem menuItem;

	menuItem = new JCheckBoxMenuItem("Record", false);
	menuItem.setActionCommand("record");
	menuItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_S, ActionEvent.ALT_MASK));
	menuItem.getAccessibleContext().setAccessibleDescription("Toggle the canvas recording");
	optionsMenu.add(menuItem);
	menuItem.addActionListener(this);

	optionsMenu.addSeparator();

	menuItem = new JMenuItem("Exit", KeyEvent.VK_X);
	menuItem.setActionCommand("exit");
	menuItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_X, ActionEvent.ALT_MASK));
	menuItem.getAccessibleContext().setAccessibleDescription("Exit");
	optionsMenu.add(menuItem);
	menuItem.addActionListener(this);

	getContentPane().add("Center", canvas);
    }

    public void actionPerformed(ActionEvent e) {
	String command = e.getActionCommand();
	if(command.equals("record")) {
	    if(movieMaker.isRecording()) {
		movieMaker.stop();
	    } else {
		movieMaker.setOutputLocator(new MediaLocator("file://c:/temp/test.mov"));
		movieMaker.start();
	    }
	} else if(command.equals("exit")) {
	    System.exit(0);
	} else {
	    JOptionPane.showInternalMessageDialog(this, "Unknown action command: " + command);
	}
    }

    public static void main(String[] args) {
	JFrame f = new JFrame("Canvas Image Stream");
	f.addWindowListener(new WindowAdapter() {
		public void windowClosing(WindowEvent e) {
		    System.exit(0);
		}
	    });
	JApplet applet = new BezierRecorder();
	f.setContentPane(applet.getContentPane());
	f.setJMenuBar(applet.getJMenuBar());
	f.pack();
	f.setVisible(true);
    }
}
