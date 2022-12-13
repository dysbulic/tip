package org.himinbi.ui.test;

import javax.swing.*;
import java.awt.*;
import java.awt.geom.*;
import java.awt.event.*;
import org.himinbi.util.*;
import org.himinbi.geom.*;

public class ScaleTest extends JPanel {
    final int NUM_SQUARES = 4;
    int minSize = 20;
    boolean antialias = true;

    Bounds armNumberBounds = new Bounds(2, 9);
    Bounds dashLengthBounds = new Bounds(.5, 4.5);
    Bounds dashNumberBounds = new Bounds(2, 8);
    Bounds hueBounds = new Bounds(0, 1);
    Bounds brightnessBounds = new Bounds(.5, .75);
    Bounds saturationBounds = new Bounds(.5, 1);
    Bounds strokeWidthBounds = new Bounds(1, 3);
    Bounds speedBounds = new Bounds(50, 100);
    Bounds angleIncrementBounds = new Bounds(1, 10);
    Bounds hueIncrementBounds = new Bounds(.01, .05);
    Bounds scaleRateBounds = new Bounds(.005, .01);
    Bounds scaleBounds = new Bounds(0, 1);
    
    final int UP = 1;
    final int DOWN = 0;

    Rectangle[] box = new Rectangle[NUM_SQUARES];
    float[] hue = new float[NUM_SQUARES];
    float[] brightness = new float[NUM_SQUARES];
    int[] armCount = new int[NUM_SQUARES];
    BasicStroke[] stroke = new BasicStroke[NUM_SQUARES];
    boolean[] spinning = new boolean[NUM_SQUARES];
    int[] count = new int[NUM_SQUARES];
    double[] angleIncrement = new double[NUM_SQUARES];
    float[] hueIncrement = new float[NUM_SQUARES];
    double[] scale = new double[NUM_SQUARES];
    double[] scaleRate = new double[NUM_SQUARES];
    int[] direction = new int[NUM_SQUARES];

    Runner runner;

    {
	addMouseListener(new MouseAdapter() {
		public void mouseClicked(MouseEvent e) {
		    int index = 0;
		    boolean found = false;
		    for(index = 0; index < box.length && !found;) {
			if(box[index].contains(e.getPoint())) {
			    found = true;
			} else {
			    index ++;
			}
		    }
		    if((e.getModifiers() & MouseEvent.CTRL_MASK) != 0) {
			generateRandomArmCount(index);
		    } else if((e.getModifiers() & MouseEvent.ALT_MASK) != 0) {
			generateRandomStroke(index);
		    } else if((e.getModifiers() & MouseEvent.SHIFT_MASK) != 0) {
			generateRandomAngleIncrement(index);
		    } else {
			toggleSpin(index);
		    }
		}
	    });
	addComponentListener(new ComponentAdapter() {
		int pad = 20;
		public void componentResized(ComponentEvent e) {
		    int pad = Math.max(0,
				       Math.min(this.pad,
						Math.min(getWidth() - 2 * minSize,
							 getHeight() - 2 * minSize)));
		    Dimension boxSize = new Dimension((getWidth() - 3 * pad) / 2,
						      (getHeight() - 3 * pad) / 2);
		    for(int i = 0; i < box.length; i++) {
			if(i == 0 || i == 2) {
			    box[i].x = pad;
			} else {
			    box[i].x = 2 * pad + boxSize.width;
			}
			if(i == 0 || i == 1) {
			    box[i].y = pad;
			} else {
			    box[i].y = 2 * pad + boxSize.height;
			}
			box[i].width = boxSize.width;
			box[i].height = boxSize.height;
		    }
		}
	    });
	for(int i = 0; i < NUM_SQUARES; i++) {
	    box[i] = new Rectangle();
	    hue[i] = hueBounds.boundedFloat();
	    hueIncrement[i] = hueIncrementBounds.boundedFloat();
	    brightness[i] = brightnessBounds.boundedFloat();
	    spinning[i] = Math.random() < .6;
	    scale[i] = scaleBounds.boundedDouble();
	    scaleRate[i] = scaleRateBounds.boundedDouble();
	    direction[i] = Math.random() < .5 ? UP : DOWN;
	}
	generateRandomArmCount(-1);
	generateRandomStroke(-1);
	generateRandomAngleIncrement(-1);
	try {
	    runner = new Runner(this, getClass().getMethod("step", (Class[])null), speedBounds.boundedInt());
	    runner.setRunning(true);
	} catch(NoSuchMethodException e) {
	    e.printStackTrace(System.err);
	}
	setPreferredSize(new Dimension(300, 300));
    }      
    

    public void generateRandomArmCount(int index) {
	if(index >= 0 && index < armCount.length) {
	    armCount[index] = armNumberBounds.boundedInt();
	} else {
	    for(int i = 0; i < armCount.length; i++) {
		generateRandomArmCount(i);
	    }
	}
    }    

    public void generateRandomAngleIncrement(int index) {
	if(index >= 0 && index < angleIncrement.length) {
	    angleIncrement[index] = angleIncrementBounds.boundedDouble();
	} else {
	    for(int i = 0; i < angleIncrement.length; i++) {
		generateRandomAngleIncrement(i);
	    }
	}
    }

    public void generateRandomStroke(int index) {
	if(index >= 0 && index < stroke.length) {
	    float[] dashArray = new float[dashNumberBounds.boundedInt()];
	    for(int i = 0; i < dashArray.length; i++) {
		dashArray[i] = dashLengthBounds.boundedFloat();
	    }
	    stroke[index] = new BasicStroke(strokeWidthBounds.boundedFloat(), // line width
					    BasicStroke.CAP_BUTT,             // cap type
					    BasicStroke.JOIN_BEVEL,           // join type
					    .5f,                              // miter limit
					    dashArray,                        // dash array
					    0);                               // dash offset
	} else {
	    for(int i = 0; i < stroke.length; i++) {
		generateRandomStroke(i);
	    }
	}
    }

    public void toggleSpin(int index) {
	if(index >= 0 && index < spinning.length) {
	    spinning[index] = !spinning[index];
	} else {
	    for(int i = 0; i < spinning.length; i++) {
		toggleSpin(i);
	    }
	}
    }

    public void step() {
	for(int i = 0; i < NUM_SQUARES; i++) {
	    if(spinning[i]) {
		count[i]++;
		if(scaleBounds.upperBound() <= scale[i] && direction[i] == UP) {
		    scale[i] = scaleBounds.upperBound();
		    direction[i] = DOWN;
		} else if(scaleBounds.lowerBound() >= scale[i] && direction[i] == DOWN) {
		    scale[i] = scaleBounds.lowerBound();
		    direction[i] = UP;
		} else if(direction[i] == UP) {
		    scale[i] = Math.min(scale[i] + scaleRate[i], scaleBounds.upperBound());
		} else if(direction[i] == DOWN) {
		    scale[i] = Math.max(scale[i] - scaleRate[i], scaleBounds.lowerBound());
		} else {
		    System.err.println("We oughtn't get here: direction = " + direction[i]);
		}
	    }
	}
	repaint();
    }

    public void paint(Graphics g) {
	Graphics2D g2d = (Graphics2D)g;
	g2d.setPaint(Color.white);
	g2d.fillRect(0, 0, getWidth(), getHeight());

	if(antialias) {
	    g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING,
				 RenderingHints.VALUE_ANTIALIAS_ON);
	}

	for(int i = 0; i < NUM_SQUARES; i++) {
	    g2d.setStroke(stroke[i]);
	    float size = 100;
	    GeneralPath star = createStar(new Point2D.Float(size / 2, size / 2),
					  armCount[i],
					  size / 2,
					  count[i] * angleIncrement[i]);
	    star.moveTo(0   ,    0);
	    star.lineTo(size,    0);
	    star.lineTo(size, size);
	    star.lineTo(0   , size);
	    star.lineTo(0   ,    0);
	    AffineTransform transform = new AffineTransform();
	    transform.translate(box[i].x, box[i].y);
	    double widthRatio = box[i].width / size;
	    double heightRatio = box[i].height / size;
	    switch(i + 1) {
	    case 1:
		transform.scale(widthRatio, heightRatio);
		break;
	    case 2:
		transform.scale(scale[i] * widthRatio, heightRatio);
		break;
	    case 3:
		transform.scale(widthRatio, scale[i] * heightRatio);
		break;
	    case 4:
		transform.scale(scale[i] * widthRatio, scale[i] * heightRatio);
		break;
	    default:
		transform.scale(Math.random() * scale[i] * widthRatio, Math.random() * scale[i] * heightRatio);
		break;
	    }	    
	    hue[i] = (hue[i] + hueIncrement[i]) % 1;
	    g2d.setPaint(Color.getHSBColor(hue[i],
					    saturationBounds.boundedFloat(),
					    brightness[i]));
	    g2d.draw(star.createTransformedShape(transform));
	    g2d.setPaint(Color.getHSBColor(hue[i],
					    saturationBounds.boundedFloat(),
					    brightness[i]));
	    g2d.draw(box[i]);
	}
    }

    public GeneralPath createStar(Point2D center, int numArms, double radius, double initialAngle) {
	GeneralPath star = new GeneralPath();
	for(int i = 0; i < numArms; i++) {
	    star.moveTo((float)center.getX(), (float)center.getY());
	    double angle = Math.toRadians(initialAngle + i * 360 / numArms);
	    star.lineTo((float)(center.getX() + radius * Math.cos(angle)),
			(float)(center.getY() + radius * Math.sin(angle)));
	}
	return star;
    }

    public static void main(String[] args) {
	JFrame frame = new JFrame("Scale Tester");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setContentPane(new ScaleTest());
	frame.pack();
	frame.setVisible(true);
    }
}
