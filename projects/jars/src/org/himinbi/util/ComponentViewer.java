package org.himinbi.util;

import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.event.*;
import java.beans.*;

public class ComponentViewer extends JApplet implements ActionListener {
    public final static String EXIT_COMMAND = "exit";
    public final static String LOAD_COMMAND = "load";
    public final static String COMPONENT_PROPERTY = "components";

    GridBagLayout layout = new GridBagLayout();
    GridBagConstraints gridbag = new GridBagConstraints();
    JMenuItem loadItem = new JMenuItem("Load", KeyEvent.VK_L);
    JMenuItem exitItem = new JMenuItem("Exit", KeyEvent.VK_X);
    EventListenerList listeners = new EventListenerList();

    {
	JMenuBar menuBar = new JMenuBar();
	setJMenuBar(menuBar);

	JMenu optionsMenu = new JMenu("Options");
	optionsMenu.setMnemonic(KeyEvent.VK_O);
	optionsMenu.getAccessibleContext().setAccessibleDescription("Options");
	menuBar.add(optionsMenu);

	loadItem.setActionCommand(LOAD_COMMAND);
	loadItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_N, ActionEvent.ALT_MASK));
	loadItem.getAccessibleContext().setAccessibleDescription("Load");
	loadItem.addActionListener(this);
	optionsMenu.add(loadItem);

	exitItem.setActionCommand(EXIT_COMMAND);
	exitItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_X, ActionEvent.ALT_MASK));
	exitItem.getAccessibleContext().setAccessibleDescription("Exit");
	exitItem.addActionListener(this);
	optionsMenu.add(exitItem);

	getContentPane().setLayout(layout);
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
        gridbag.fill = GridBagConstraints.BOTH;

	gridbag.weightx = .5;
	gridbag.weighty = .5;
    }

    public ComponentViewer() {
    }

    public ComponentViewer(String[] components) {
      	loadComponents(components);
    }

    public void start() {
	/* This should only be called if this is run from an applet */
	exitItem.setEnabled(false);
    }

    public void init() {
	int i = 0;
	while(getParameter("component_" + (i + 1)) != null) {
	    i++;
	}
	String[] components = new String[i];
	for(int j = 1; j <= i; j++) {
	    components[j - 1] = getParameter("component_" + j);
	}
	loadComponents(components);
    }

    public void loadComponents(String[] components) {
	for(int i = 0; i < components.length; i++) {
	    loadComponent(components[i]);
	}
    }

    public void loadComponent(String componentName) {
	try {
	    Component component = (Component)Class.forName(componentName).newInstance();
	    layout.setConstraints(component, gridbag);
	    getContentPane().add(component);
	    firePropertyChange(new PropertyChangeEvent(this, COMPONENT_PROPERTY, null, null));
	} catch(ClassNotFoundException e) {
	    JOptionPane.showMessageDialog(this,
					  "No class found for \"" + componentName + "\"",
					  "Loading Error",
					  JOptionPane.ERROR_MESSAGE);
	} catch(ClassCastException e) {
	    JOptionPane.showMessageDialog(this,
					  "\"" + componentName + "\" could not be cast as a Component",
					  "Loading Error",
					  JOptionPane.ERROR_MESSAGE);
	} catch(InstantiationException e) {
	    JOptionPane.showMessageDialog(this,
					  "Error: " + e,
					  "Instantiation Error",
					  JOptionPane.ERROR_MESSAGE);
	    e.printStackTrace(System.err);
	} catch(IllegalAccessException e) {
	    JOptionPane.showMessageDialog(this,
					  "Error: " + e,
					  "Illegal Access Error",
					  JOptionPane.ERROR_MESSAGE);
	    e.printStackTrace(System.err);
	}
    }

    public void actionPerformed(ActionEvent e) {
	String command = e.getActionCommand();
	if(command == EXIT_COMMAND) {
	    System.exit(0);
	} else if(command == LOAD_COMMAND) {
	    String result = JOptionPane.showInputDialog(this,
							"Which component would you like to load?");
	    if(result != null) {
		loadComponent(result);
	    }
	} else {
	    JOptionPane.showInternalMessageDialog(this, "Unknown action command: " + command);
	}
    }
    
    public boolean isPropertyChangeListener(PropertyChangeListener listener) {
        Object[] listeners = this.listeners.getListenerList();
        boolean found = false;
        for(int i = listeners.length - 2; i >= 0 && !found; i -= 2) {
            found = listener == listeners[i + 1];
        }
        return found;
    }

    public void addPropertyChangeListener(PropertyChangeListener listener) {
        listeners.add(PropertyChangeListener.class, listener);
    }

    public void removePropertyChangeListener(PropertyChangeListener listener) {
        listeners.remove(PropertyChangeListener.class, listener);
    }

    protected void firePropertyChange(PropertyChangeEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == PropertyChangeListener.class) {
                ((PropertyChangeListener)listeners[i + 1]).propertyChange(e);
            }
        }
    }

    public static void main(String[] args) {
	final JFrame frame = new JFrame("Component Tester");
	frame.addWindowListener(new WindowAdapter() {
		public void windowClosing(WindowEvent e) {
		    System.exit(0);
		}
	    });
	ComponentViewer viewer = new ComponentViewer(args);
	viewer.addPropertyChangeListener(new PropertyChangeListener() {
		public void propertyChange(PropertyChangeEvent e) {
		    if(e.getPropertyName() == ComponentViewer.COMPONENT_PROPERTY) {
			frame.pack();
		    }
		}
	    });
	frame.setContentPane(viewer.getContentPane());
	frame.setJMenuBar(viewer.getJMenuBar());
	frame.pack();
	frame.setVisible(true);
    }
}
