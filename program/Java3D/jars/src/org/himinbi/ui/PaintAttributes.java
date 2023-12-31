package org.himinbi.ui;

import java.awt.*;
import java.awt.geom.*;
import java.awt.font.*;
import java.awt.image.*;
import java.awt.event.*;
import javax.swing.event.*;
import java.beans.*;

public class PaintAttributes {
    public final static String NO_PAINT = "None";
    public final static String SOLID_PAINT = "Solid";
    public final static String GRADIENT_PAINT = "Gradient";
    public final static String TEXTURE_PAINT = "Texture";
    public final static String BUTT_CAP = "Butt";
    public final static String ROUND_CAP = "Round";
    public final static String SQUARE_CAP = "Square";
    public final static String BEVEL_JOIN = "Bevel";
    public final static String MITER_JOIN = "Miter";
    public final static String ROUND_JOIN = "Round";

    public final static String PAINT_PROPERTY = "paint";
    public final static String STROKE_PROPERTY = "stroke";

    boolean doPaint = true;
    Paint paint;
    Stroke stroke;
    EventListenerList listeners = new EventListenerList();

    public PaintAttributes() {
	this(Color.black, new BasicStroke());
    }

    public PaintAttributes(Paint paint) {
	this(paint, new BasicStroke());
    }
    
    public PaintAttributes(Stroke stroke) {
	this(Color.black, stroke);
    }

    public PaintAttributes(Paint paint, Stroke stroke) {
	this.paint = paint;
	this.stroke = stroke;
    }

    public void set(Graphics2D g) {
	if(doPaint) {
	    if(paint != null) {
		g.setPaint(paint);
	    }
	    if(stroke != null) {
		g.setStroke(stroke);
	    }
	}
    }

    public void setPaint(Paint paint) {
	this.paint = paint;
	firePropertyChange(new PropertyChangeEvent(this, PAINT_PROPERTY, null, paint));
    }

    public Paint getPaint() {
	return paint;
    }

    public void setStroke(Stroke stroke) {
	this.stroke = stroke;
	firePropertyChange(new PropertyChangeEvent(this, STROKE_PROPERTY, null, stroke));
    }

    public Stroke getStroke() {
	return this.stroke;
    }

    public void setPainting(boolean painting) {
	doPaint = painting;
    }

    public boolean shouldPaint() {
	return doPaint;
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
}
