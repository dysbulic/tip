package org.himinbi.ui;

import javax.swing.*;
import java.util.*;
import java.awt.*;
import java.awt.geom.*;
import java.awt.font.*;
import java.awt.image.*;
import java.awt.event.*;

public class OrientedJLabel extends JLabel {
    double angle;
    Font baseFont;
    boolean fontSet = true;

    Object antialiasHint = RenderingHints.VALUE_ANTIALIAS_ON;
    Object fractionalMetricsHint = RenderingHints.VALUE_FRACTIONALMETRICS_ON;

    {
	baseFont = getFont();
    }

    public OrientedJLabel() {
	this("", 0, SwingConstants.CENTER);
    }

    public OrientedJLabel(String text) {
	this(text, 0, SwingConstants.CENTER);
    }
    
    public OrientedJLabel(String text, double angle) {
	this(text, angle, SwingConstants.CENTER);
    }

    public OrientedJLabel(String text, double angle, int horizontalAlignment) {
	super(text, horizontalAlignment);
	setOrientation(angle);
    }

    public void setOrientation(double angle) {
	if(angle != this.angle) {
	    super.setFont(rotateFont(baseFont, angle));
	    fixBounds();
	    this.angle = angle;
	}
    }
    
    public double getOrientation() {
	return angle;
    }
    
    public void setFont(Font font) {
	baseFont = font;
	super.setFont(rotateFont(font, angle));
	fixBounds();
    }

    /**
     * The function to find the size does not work correctly with most rotated
     *  fonts. This fixes that.
     */
    protected void fixBounds() {
	if(fontSet) {
	    Rectangle2D bounds =
		getFont().createGlyphVector(((Graphics2D)getGraphics()).getFontRenderContext(),
					    getText()).getVisualBounds();
	    Dimension size = new Dimension((int)(bounds.getWidth()),
					   (int)(bounds.getHeight()));
	    setPreferredSize(size);
	    setMinimumSize(size);
	}
    }

    public void setText(String text) {
	super.setText(text);
	setFont(baseFont);
    }

    public Font rotateFont(Font font, double angle) {
	Graphics2D g = (Graphics2D)getGraphics();
	Font newFont = font;
	if(g != null) {
	    newFont = rotateFont(font, g.getFontRenderContext(), getText(), angle);
	    fontSet = true;
	} else {
	    fontSet = false;
	}
	return newFont;
    }
    
    public static Font rotateFont(Font font, FontRenderContext renderContext, String text, double angle) {
	AffineTransform position = new AffineTransform();
	GlyphVector glyph = font.createGlyphVector(renderContext, text);
	double translationPercent = -.5;
	angle %= 2 * Math.PI;
	if(angle < Math.PI / 2 || angle > 3 * Math.PI / 2) {
	    translationPercent *= 1 - Math.cos(angle);
	}
	Rectangle2D bounds = glyph.getVisualBounds();
	position.translate((bounds.getWidth() + bounds.getHeight()) * translationPercent, 0);
	position.rotate(angle, bounds.getWidth() * .5, 0);
	return font == null ? font : font.deriveFont(position);
    }

    public void paintComponent(Graphics g) {
	if(!fontSet) {
	    setFont(baseFont);
	}
	((Graphics2D)g).setRenderingHint(RenderingHints.KEY_ANTIALIASING, antialiasHint);
	((Graphics2D)g).setRenderingHint(RenderingHints.KEY_FRACTIONALMETRICS, fractionalMetricsHint);
	super.paintComponent(g);
    }

    public Object getAntialiasKey() {
	return antialiasHint;
    }

    public void setAntiaiasHint(Object antialiasHint) {
	this.antialiasHint = antialiasHint;
    }

    public Object getFractionalMetricsHint() {
	return fractionalMetricsHint;
    }

    public void setFractionalMetricsHint(Object fractionalMetricsHint) {
	this.fractionalMetricsHint = fractionalMetricsHint;
    }
}
