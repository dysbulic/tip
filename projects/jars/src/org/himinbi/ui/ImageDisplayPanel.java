package org.himinbi.ui;

import java.io.File;
import java.net.*;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import java.awt.geom.*;
import java.util.*;
import java.beans.*;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.filechooser.FileFilter;
import org.himinbi.util.*;

public class ImageDisplayPanel extends JPanel implements ActionListener {
    EventListenerList listeners = new EventListenerList();
    String currentKey;
    Hashtable images = new Hashtable();
    ButtonGroup buttonGroup = new ButtonGroup();
    GridLayout layout = new GridLayout(1, 1, 0, 0);
    ConfigurableFileFilter fileFilter;
    JFileChooser chooser = new JFileChooser();
    MediaTracker tracker = new MediaTracker(this);
    int iconWidth = 200;
    int maxIconHeight = 200;
    double columnRatio = 2;

    public ImageDisplayPanel() {
	setLayout(layout);
	
	String[] fileTypes = {"png",
			      "jpg,jpeg",
			      "gif"};
	String[] fileDescriptions = {"Portable Net Graphics",
				     "Joint Photographic Experts Group",
				     "Graphics Interchange Format"};

	chooser.setFileSelectionMode(JFileChooser.FILES_AND_DIRECTORIES);
	chooser.setMultiSelectionEnabled(true);
	fileFilter = new ConfigurableFileFilter(fileTypes, fileDescriptions, "Image Files");
	chooser.addChoosableFileFilter(fileFilter);
	FileFilter[] filters = fileFilter.getSubFilters();
	for(int i = 0; i < filters.length; i++) {
	    chooser.addChoosableFileFilter(filters[i]);
	}
	chooser.setFileFilter(fileFilter);
    }

    public void setImageDirectory(File directory) {
        setImageDirectory(directory, false);
    }

    public void setImageDirectory(File directory, boolean replaceExisting) {
        File[] files = javax.swing.filechooser.FileSystemView.getFileSystemView().getFiles(directory, true);
        Vector fileList = new Vector();
        for(int i = 0, j = 0; i < files.length; i++) {
            if(fileFilter.accept(files[i]) && !files[i].isDirectory()) {
                try {
                    fileList.add(files[i].toURL());
                } catch(MalformedURLException e) {
                }
            }
        }
        setFiles(fileList, replaceExisting);
    }

    public void setFiles(Vector fileList) {
        setFiles(fileList, false);
    }

    public void setFiles(Vector fileList, boolean replaceExisting) {
        ProgressMonitor progressMonitor = new ProgressMonitor(this, "Loading Images", "", 0, fileList.size());
        progressMonitor.setMillisToDecideToPopup(0);
        URL file;
        for(int i = 0; i < fileList.size() && !progressMonitor.isCanceled(); i++) {
            file = (URL)fileList.get(i);
            progressMonitor.setNote("Loading: " + file);
            System.out.println("Loading: " + file);
            addFile(file, replaceExisting);
            progressMonitor.setProgress(i + 1);
            if(replaceExisting == true) {
                replaceExisting = false;
            }
        }
    }

    public void addFile(URL url) {
        addFile(url, false);
    }

    public void addFile(URL url, boolean replaceExisting) {
	if(replaceExisting) {
	    images.clear();
	}
        if(url != null) {
            System.out.println("Trying to load: " + url);
            Image image = Toolkit.getDefaultToolkit().getImage(url);

            /* I am not sure why, but if this is not here the first image loaded will
             *  have a size of <-1, -1> until after the JRadioButton constructor. This
             *  code is taken from the ImageIcon constructor.
             */
            tracker.addImage(image, 0);
            try {
                tracker.waitForID(0, 5000);
            } catch (InterruptedException e) {
                System.err.println("Error: " + url + " interrupted while loading");
            }
            tracker.removeImage(image, 0);
	    
	    System.out.println("Size: <" + image.getWidth(this) + ", " + image.getHeight(this) + ">");

            BufferedImage buffer =
                new BufferedImage(image.getWidth(this), image.getHeight(this), BufferedImage.TYPE_INT_RGB);
            buffer.createGraphics().drawImage(image, 0, 0, Color.white, this);
	    String key = url.toString();
            images.put(key, buffer);
	    addButton(key);
	    setKey(key);
	}
	resetLayout();
    }

    protected void addButton(String key) {
	BufferedImage buffer = (BufferedImage)images.get(key);
	double scale = Math.min((double)iconWidth / buffer.getWidth(),
				(double)maxIconHeight / buffer.getHeight());
	AffineTransformOp imageOp =
	    new AffineTransformOp(AffineTransform.getScaleInstance(scale, scale),
				  AffineTransformOp.TYPE_NEAREST_NEIGHBOR);
	JRadioButton button = new JRadioButton(new ImageIcon(imageOp.filter(buffer, null), key));
	button.setBorderPainted(true);
	button.setToolTipText(key);
	button.addActionListener(this);
	button.setActionCommand(key);
	buttonGroup.add(button);
	add(button);
    }

    protected void resetLayout() {
	layout.setColumns(Math.max((int)Math.ceil(Math.sqrt(images.size())), 1));
	layout.setRows(Math.max((int)Math.ceil(Math.sqrt(images.size())), 1));
	System.out.println("Setting layout to: [" + layout.getColumns() + ", " + layout.getRows() + "]");
	revalidate();
    }

    protected void redraw() {
	resetLayout();
	removeAll();
	Enumeration keys = images.keys();
	while(keys.hasMoreElements()) {
	    addButton((String)keys.nextElement());
	}
    }

    public void chooseFiles() {
	if(chooser.showDialog(this, null) == JFileChooser.APPROVE_OPTION) {
	    File[] files = chooser.getSelectedFiles();
	    for(int i = 0; i < files.length; i++) {
		if(files[i].isDirectory()) {
		    setImageDirectory(files[i]);
		} else {
		    try {
			addFile(files[i].toURL());
		    } catch(MalformedURLException ex) {
		    }
		}
	    }
	}
    }

    public BufferedImage getImage() {
	return getImage(currentKey);
    }

    public BufferedImage getImage(String key) {
	return (BufferedImage)images.get(key);
    }

    public String getKey() {
	return currentKey;
    }

    public void setKey(String key) {
	String oldKey = currentKey;
	currentKey = key;
	firePropertyChange(new PropertyChangeEvent(this, "key", oldKey, currentKey));
    }


    public void actionPerformed(ActionEvent e) {
	setKey(e.getActionCommand());
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
