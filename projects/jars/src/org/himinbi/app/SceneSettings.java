package org.himinbi.app;

import java.util.*;
import java.awt.*;
import java.awt.image.*;
import java.awt.geom.*;
import java.awt.event.*;
import java.beans.*;
import javax.swing.*;
import javax.swing.filechooser.FileFilter;
import javax.swing.event.*;
import java.io.File;
import java.net.*;
import javax.media.j3d.*;
import javax.media.j3d.ImageComponent2D;
import com.sun.j3d.utils.image.TextureLoader;
import org.himinbi.ui.*;
import org.himinbi.util.*;

public class SceneSettings extends JFrame implements ActionListener, PropertyChangeListener {
    final static int BACKGROUND = 0;
    final static int TEXTURE = 1;
    EventListenerList listeners = new EventListenerList();
    Component observer;
    ImageDisplayPanel imagePanel = new ImageDisplayPanel();
    JComboBox typesList;


    float earthRadius = 6378137 / 1000000f;
    int speed = 14000;
    
    public SceneSettings(Component observer) {
	this.observer = observer;
	
	addWindowListener(new WindowAdapter() {
		public void windowClosing(WindowEvent e) {
		    setVisible(false);
		}
	    });
	
	setTitle("Settings");
	
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	getContentPane().setLayout(layout);
	gridbag.fill = GridBagConstraints.BOTH;
	
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	JLabel label = new JLabel("Pictures:");
	layout.setConstraints(label, gridbag);
	getContentPane().add(label);

	String[] types = {"Background", "Texture"};
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	typesList = new JComboBox(types);
	layout.setConstraints(typesList, gridbag);
        getContentPane().add(typesList);
	
	gridbag.insets.left = 5;
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	JScrollPane scrollPane = new JScrollPane(imagePanel);
	layout.setConstraints(scrollPane, gridbag);
	getContentPane().add(scrollPane);	

	URL url;
	Vector files = new Vector();
	String[] textureName = {"org.himinbi_blend_earth_map.jpg",
				"earthobservatory.nasa.gov_earth-lights.jpg",
				"images.jsc.nasa.gov_earth-paper-map.jpg",
				"maps.jpl.nasa.gov_hipparcos_starmap.png",
				"www.arcscience.com_one-world.jpg",
				"www.btinternet.com_~consty_bluemars.jpg"};
	// The final / is an url separator and not a path one, so it alwaws be a /
	String textureDirectory = "/images/textures/";

	for(int i = 0; i < textureName.length; i++) {
	    url = getClass().getResource(textureDirectory + textureName[i]);
	    files.add(url);
	}
	imagePanel.setFiles(files);
	
	imagePanel.addPropertyChangeListener(this);

	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	gridbag.weightx = 0;
	gridbag.weighty = 0;
	JButton fileButton = new JButton("Add Files");
	fileButton.addActionListener(this);
	fileButton.setActionCommand("imageDir");
	layout.setConstraints(fileButton, gridbag);
	getContentPane().add(fileButton);
	
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	JButton okButton = new JButton("Ok");
	okButton.addActionListener(this);
	okButton.setActionCommand("close");
	layout.setConstraints(okButton, gridbag);
	getContentPane().add(okButton);
	
	pack();
    }
    
    public Texture getTexture() {
	return new TextureLoader(imagePanel.getImage(), observer).getTexture();
    }
    
    public Texture getTexture(String key) {
	return new TextureLoader(imagePanel.getImage(key), observer).getTexture();
    }
    
    public ImageComponent2D getImage() {
	return new TextureLoader(imagePanel.getImage(), observer).getImage();
    }

    public ImageComponent2D getImage(String key) {
	return new TextureLoader(imagePanel.getImage(key), observer).getImage();
    }
    
    public float getEarthRadius() {
	return earthRadius;
    }
    
    public void setEarthRadius(float earthRadius) {
	firePropertyChange(new PropertyChangeEvent
	    (this, "earthRadius", new Float(this.earthRadius), new Float(earthRadius)));
	this.earthRadius = earthRadius;
    }
    
    public int getSpeed() {
	return speed;
    }
    
    public void setSpeed(int speed) {
	firePropertyChange(new PropertyChangeEvent(this, "speed", new Integer(this.speed), new Integer(speed)));
	this.speed = speed;
    }
    
    public void actionPerformed(ActionEvent e) {
	String command = e.getActionCommand();
	if(command.equals("close")) {
	    setVisible(false);
	} else if(command.equals("imageDir")) {
	    imagePanel.chooseFiles();
	} else {
	    System.err.println("\"" + command + "\": huh?");
	}
    }
    
    public void propertyChange(PropertyChangeEvent e) {
	if(typesList.getSelectedIndex() == BACKGROUND) {
	    firePropertyChange
		(new PropertyChangeEvent
		    (this, "background", null, new TextureLoader(imagePanel.getImage()).getImage()));
	} else if(typesList.getSelectedIndex() == TEXTURE) {
	    firePropertyChange
		(new PropertyChangeEvent
		    (this, "texture", null, new TextureLoader(imagePanel.getImage()).getTexture()));
	}
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
}
