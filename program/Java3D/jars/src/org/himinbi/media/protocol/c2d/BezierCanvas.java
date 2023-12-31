package org.himinbi.media.protocol.c2d;

import javax.swing.*;
import javax.swing.event.*;
import javax.swing.text.*;
import javax.swing.border.*;
import javax.swing.colorchooser.*;
import javax.swing.filechooser.*;
import javax.accessibility.*;
import org.apache.log4j.*;
import java.awt.*;
import java.awt.font.*;
import java.awt.geom.*;
import java.awt.image.*;
import java.awt.event.*;
import org.himinbi.util.*;

public class BezierCanvas extends CanvasImageStream {
    final static int NONE = 0;
    final static int BACKGROUND = 1;
    final static int PATH = 2;
    final static int GRADIENT_TOP = 3;
    final static int GRADIENT_BOTTOM = 4;
    final static int CONTROL_LINE = 5;
    final static int GENERATED_LINE = 6;
    final static int END_POINT = 7;
    final static int CONTROL_POINT = 8;
    Color[] color =  {new Color(0,     0, 153),
		      new Color(255, 255, 255),
		      new Color(255,   0, 101),
		      new Color(255, 255,  10),
		      new Color(255,   0,   0),
		      new Color(255,   0, 255),
		      new Color(255,   0, 100),
		      new Color(  0, 255,   0)};
    int[] paintTypes;
    int paintType = 0;

    final static int ORIGIN = Integer.valueOf("1", 2).intValue();
    final static int DESTINATION = Integer.valueOf("10", 2).intValue();

    Dimension pointSize = new Dimension(3, 3);

    BasicStroke lineStroke = new BasicStroke(2.0f, BasicStroke.CAP_BUTT, BasicStroke.JOIN_ROUND, 2.0f);
    BasicStroke pointStroke = new BasicStroke(1.0f, BasicStroke.CAP_BUTT, BasicStroke.JOIN_ROUND, 6.0f);
    BasicStroke pathStroke = new BasicStroke(8.0f, BasicStroke.CAP_BUTT, BasicStroke.JOIN_ROUND, 8.0f);
    Runner runner = null;
    static Logger log = Logger.getLogger("c2d.BC");
    float speedFactor = .01f;
    
    int numPoints = 4;

    Point2D.Float[] origin = new Point2D.Float[numPoints * 2];
    Point2D.Float[] destination = new Point2D.Float[numPoints * 2];
    float[] distance = new float[numPoints * 2];

    {
	String num = "1";
	paintTypes = new int[color.length + 1];
	for(int i = 1; i <= color.length; i++) {
	    paintTypes[i] = Integer.valueOf(num, 2).intValue();
	    num = num + "0";
	    addPaintType(i);
	}
        log.debug("BezierCanvas created: " + paintType);
	addHierarchyListener
	    (new HierarchyListener() {
		    public void hierarchyChanged(HierarchyEvent e) {
			log.info("Hierarchy changed: " + e);
			runner.setRunning(isShowing());
		    }
		});
	addComponentListener
	    (new ComponentAdapter() {
		    public void componentResized(ComponentEvent e) {
			log.info("Resized: <" + getSize().width + ", " + getSize().height + ">");
			Dimension size = getSize();
			for(int i = 0; i < origin.length; i++) {
			    if(destination[i] == null ||
			       destination[i].x >= size.width ||
			       destination[i].y >= size.height) {
				regeneratePoint(DESTINATION, i);
			    }
			    if(origin[i] == null ||
			       origin[i].x >= size.width ||
			       origin[i].y >= size.height) {
				regeneratePoint(ORIGIN, i);
			    }
			}
		    }
		});
	addMouseListener
	    (new MouseAdapter() {
		    public void mouseReleased(MouseEvent e) {
			if(!e.isPopupTrigger()) {
			    if(e.isAltDown()) {
				for(int i = 0; i < destination.length; i++) {
				    destination[i].x = e.getX();
				    destination[i].y = e.getY();
				}
			    } else if(e.isControlDown()) {
				int index = (int)(Math.random() * origin.length);
				origin[index].x = e.getX();
				origin[index].y = e.getY();
			    } else if(e.isShiftDown()) {
				regenerateAll(ORIGIN | DESTINATION);
			    } else {
				runner.setRunning(!runner.isRunning());
			    }
			    repaint();
			}
		    }
		});
	addMouseListener
	    (new MouseAdapter() {
		    JPopupMenu popup = new JPopupMenu();
		    
		    {
			popup.add(new JMenuItem("This little popup"));
			popup.add(new JMenuItem("Needs some things in it"));
		    }

		    public void mousePressed(MouseEvent e) {
			maybeShowPopup(e);
		    }
		    
		    public void mouseReleased(MouseEvent e) {
			maybeShowPopup(e);
		    }
		    
		    private void maybeShowPopup(MouseEvent e) {
			if(e.isPopupTrigger()) {
			    popup.show(e.getComponent(), e.getX(), e.getY());
			}
		    }
		});
	try {
	    runner = new Runner(this, getClass().getMethod("repaint", (Class[])null), 10);
	} catch(NoSuchMethodException e) {
	}
	runner.setPriority(Thread.MIN_PRIORITY);
    }

    public BezierCanvas() {
    }
    
    public BezierCanvas(Dimension size) {
        setSize(size);
    }
    
    public Color getColor(int type) {
	return color[type - 1];
    }
    
    public void setColor(int type, Color color) {
	if(color != null) {
	    this.color[type] = color;
	}
    }

    public void setSpeedFactor(float speedFactor) {
	this.speedFactor = speedFactor;
	regenerateAll(NONE);
    }

    public float getSpeedFactor() {
	return speedFactor;
    }

    public void addPaintType(int type) {
	paintType |= paintTypes[type];
    }

    public void removePaintType(int type) {
	paintType &= ~paintTypes[type];
    }

    public void setPaintType(int paintType) {
	this.paintType = paintType;
    }

    public boolean isPainting(int type) {
	return (paintType & paintTypes[type]) != 0;
    }

    public void regenerateAll(int type) {
	for(int i = 0; i < origin.length; i++) {
	    regeneratePoint(type, i);
	}
    }

    public void regeneratePoint(int type, int index) {
	Dimension size = getSize();
	log.info("Regenerating point " + index + " with type: " + type + ": <" + size.width + ", " + size.height + ">");
	if(origin[index] == null || (type & ORIGIN) != 0) {
	    origin[index] = new Point2D.Float((float)(Math.random() * size.width),
					      (float)(Math.random() * size.height));
	    /* It is necessary to check not only that control points are in the canvas
	     *  but also that their mirror is also since their mirror will be the
	     *  second control point of the previous curve and if it is out of the
	     *  canvas then the curve will be as well.
	     */
	}
	if(destination[index] == null || (type & DESTINATION) != 0) {
	    destination[index] = new Point2D.Float((float)(Math.random() * size.width),
						   (float)(Math.random() * size.height));
	}
	float dx = destination[index].x - origin[index].x;
	float dy = destination[index].y - origin[index].y;
	distance[index] = (float)(Math.random() * Math.sqrt(dx * dx + dy * dy) * speedFactor);
    }

    GeneralPath path = new GeneralPath(GeneralPath.WIND_NON_ZERO);
    
    public void paintBuffer(Graphics2D g) {
	Dimension size = getSize();
	while(g == null || size.width + size.height <= 0) {
	    log.error("Started with no size");
	    runner.sleep(10);
        }

	for(int i = 0; i < origin.length; i++) {
	    float dx = destination[i].x - origin[i].x;
	    float dy = destination[i].y - origin[i].y;
	    double length = Math.sqrt(dx * dx + dy * dy);
	    if(distance[i] >= length) {
		origin[i].x = destination[i].x;
		origin[i].y = destination[i].y;
		regeneratePoint(DESTINATION, i);
	    } else {
		origin[i].x += dx * distance[i] / length;
		origin[i].y += dy * distance[i] / length;
	    }
	}
	
	if(isPainting(BACKGROUND)) {
	    g.setComposite(AlphaComposite.Src);
	    g.setBackground(getColor(BACKGROUND));
	    g.setRenderingHint(RenderingHints.KEY_ANTIALIASING,
			       RenderingHints.VALUE_ANTIALIAS_OFF);
	    g.clearRect(0, 0, size.width, size.height);    
	    g.setRenderingHint(RenderingHints.KEY_ANTIALIASING,
			       RenderingHints.VALUE_ANTIALIAS_ON);
	}

	path.reset();
	path.moveTo(origin[0].x, origin[0].y);

	/* The points are in sets of 2. One endpoint and one control point.
	 * A given curve is made up of:
	 *  p1 -> End of the last curve.
	 *  p2 -> Next endpoint in the list. The list wraps around to make the
	 *         curve complete.
	 *  c1 -> Control point for the last endpoint in the curve.
	 *  c2 -> Mirror of the next control point around p2.
	 */
	for(int i = 0; i < origin.length; i += 2) {
	    Point2D.Float nextP = origin[(i + 2) % origin.length];
	    Point2D.Float nextC = origin[(i + 3) % origin.length];
	    Point2D.Float mirrorC = new Point2D.Float(nextP.x + nextP.x - nextC.x, nextP.y + nextP.y - nextC.y);
	    
	    path.curveTo(origin[i + 1].x, origin[i + 1].y,
			 mirrorC.x, mirrorC.y,
			 nextP.x, nextP.y);
	}

	if(isPainting(PATH)) {
	    g.setComposite(AlphaComposite.SrcOver);
	    g.setColor(getColor(PATH));
	    g.setStroke(pathStroke);
	    g.draw(path);
	}

	if(isPainting(GRADIENT_TOP | GRADIENT_BOTTOM)) {
	    Rectangle bounds = path.getBounds();
	    GradientPaint gradient = new GradientPaint(bounds.x, bounds.y,
						       getColor(GRADIENT_TOP),
						       bounds.x + bounds.width, bounds.y + bounds.height,
						       getColor(GRADIENT_BOTTOM),
						       true);
	    g.setComposite(AlphaComposite.getInstance(AlphaComposite.SRC_OVER, 0.9f));
	    g.setPaint(gradient);
	    g.fill(path);
	}
	
	for(int i = 0; i < origin.length; i += 2) {
	    Point2D.Float nextP = origin[(i + 2) % origin.length];
	    Point2D.Float nextC = origin[(i + 3) % origin.length];
	    Point2D.Float mirrorC = new Point2D.Float(nextP.x + nextP.x - nextC.x, nextP.y + nextP.y - nextC.y);
	    
	    g.setStroke(lineStroke);
	    if(isPainting(CONTROL_LINE)) {
		g.setPaint(getColor(CONTROL_LINE));
		g.drawLine((int)origin[i].x, (int)origin[i].y, (int)origin[i + 1].x, (int)origin[i + 1].y);
	    }

	    if(isPainting(GENERATED_LINE)) {
		g.setPaint(getColor(GENERATED_LINE));
		g.drawLine((int)nextP.x, (int)nextP.y, (int)mirrorC.x, (int)mirrorC.y);
	    }

	    g.setStroke(pointStroke);
	    if(isPainting(END_POINT)) {
		g.setPaint(getColor(END_POINT));
		g.fillOval((int)(origin[i].x - pointSize.width / 2), (int)(origin[i].y - pointSize.height / 2),
		    pointSize.width, pointSize.height);
	    }
	    
	    if(isPainting(CONTROL_POINT)) {
		g.setPaint(getColor(CONTROL_POINT));
		g.fillOval((int)(origin[i + 1].x - pointSize.width / 2), (int)(origin[i + 1].y - pointSize.height / 2),
			   pointSize.width, pointSize.height);
		g.fillOval((int)(mirrorC.x - pointSize.width / 2), (int)(mirrorC.y - pointSize.height / 2),
			   pointSize.width, pointSize.height);
	    }
	}
    }
}
