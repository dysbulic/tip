package com.xith.java3d.overlay;

import java.awt.*;
import java.awt.image.*;
import java.awt.event.*;
import javax.swing.event.*;

/**
 * @author Will Holcomb
 */

public class ComponentMouseManager implements MouseListener, MouseMotionListener {
    EventListenerList listenerList = new EventListenerList();
    ScreenComponent subComponent;
    
    boolean mouseOver = false;
    boolean stuck = false;
    boolean clicked = false;

    public ComponentMouseManager( Component primaryComponent, ScreenComponent subComponent ) {
	primaryComponent.addMouseListener(this);
	primaryComponent.addMouseMotionListener(this);
	this.subComponent = subComponent;
    } 

    public void mouseMoved(MouseEvent e) {
	if (subComponent.getBounds().contains(e.getPoint())) {
	    if (!mouseOver) {
		mouseOver = true;
		fireMouseEntered(e);
	    }
	    fireMouseMoved(e);
	} else if (mouseOver) {
	    mouseOver = false;
	    fireMouseExited(e);
	}
    }
	
    public void mouseDragged(MouseEvent e) {
	if (subComponent.getBounds().contains(e.getPoint())) {
	    if (!mouseOver) {
		mouseOver = true;
		fireMouseEntered(e);
	    }
	    fireMouseDragged(e);
	} else if (mouseOver) {
	    mouseOver = false;
	    fireMouseExited(e);
	}
    }
	
    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
	if (mouseOver) {
	    mouseOver = false;
	    fireMouseExited(e);
	}
    }
    
    public void mousePressed(MouseEvent e) {
	if (mouseOver) {
	    clicked = true;
	    fireMousePressed(e);
	}
    }
    
    public void mouseReleased(MouseEvent e) {
	if (mouseOver) {
	    fireMouseReleased(e);
	    if (clicked) {
		clicked = false;
		fireMouseClicked(e);
	    }
	}
    }

    public void mouseClicked(MouseEvent e) {
    }

    public void addMouseListener(MouseListener listener) {
	listenerList.add(MouseListener.class, listener);
    }

    public void removeMouseListener(MouseListener listener) {
	listenerList.remove(MouseListener.class, listener);
    }

    public void addMouseMotionListener(MouseMotionListener listener) {
	listenerList.add(MouseMotionListener.class, listener);
    }

    public void removeMouseMotionListener(MouseMotionListener listener) {
	listenerList.remove(MouseMotionListener.class, listener);
    }

    public void fireMouseMoved(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseMotionListener.class) {
		((MouseMotionListener)listeners[i + 1]).mouseMoved(e);
	    }
	}
    }

    public void fireMouseDragged(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseMotionListener.class) {
		((MouseMotionListener)listeners[i + 1]).mouseDragged(e);
	    }
	}
    }

    public void fireMouseEntered(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseListener.class) {
		((MouseListener)listeners[i + 1]).mouseEntered(e);
	    }
	}
    }

    public void fireMouseExited(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseListener.class) {
		((MouseListener)listeners[i + 1]).mouseExited(e);
	    }
	}
    }

    public void fireMouseClicked(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseListener.class) {
		((MouseListener)listeners[i + 1]).mouseClicked(e);
	    }
	}
    }

    public void fireMousePressed(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseListener.class) {
		((MouseListener)listeners[i + 1]).mousePressed(e);
	    }
	}
    }

    public void fireMouseReleased(MouseEvent e) {
	Rectangle bounds = subComponent.getBounds();
	//e.translatePoint(-bounds.x, -bounds.y);
	Object[] listeners = listenerList.getListenerList();
	for (int i = listeners.length - 2; i >= 0; i -= 2) {
	    if (listeners[i] == MouseListener.class) {
		((MouseListener)listeners[i + 1]).mouseReleased(e);
	    }
	}
    }
}
