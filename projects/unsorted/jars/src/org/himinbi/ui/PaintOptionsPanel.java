package org.himinbi.ui;

import java.awt.*;
import java.awt.geom.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.border.*;
import java.beans.*;

public class PaintOptionsPanel extends JPanel
    implements TypedComponent, ActionListener {
    String paintType;
    boolean typeChanged = false;
    boolean colorChanged = false;
    Paint paint;

    JButton solidColor = new JButton();
    GradientOptionsPanel gradientOptions;
    TypeSelectionPanel typeSelectionPanel;
    JPanel optionsPanel;
    CardLayout optionsLayout;
    Dimension bounds = new Dimension(300, 300);

    public PaintOptionsPanel() {
	this(new Color((float)Math.random(),
		       (float)Math.random(),
		       (float)Math.random()));
    }

    public PaintOptionsPanel(Paint paint) {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);

	String[] types = {PaintAttributes.NO_PAINT,
			  PaintAttributes.SOLID_PAINT,
			  PaintAttributes.GRADIENT_PAINT,
			  PaintAttributes.TEXTURE_PAINT};
	typeSelectionPanel = new TypeSelectionPanel("Paint Type:", types, this);
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.weightx = 0;
	gridbag.weighty = 0;
	layout.setConstraints(typeSelectionPanel, gridbag);
	add(typeSelectionPanel);

	optionsPanel = new JPanel();
	optionsLayout = new CardLayout();
	optionsPanel.setLayout(optionsLayout);

	optionsPanel.add(PaintAttributes.NO_PAINT, new JPanel());
	
	solidColor.addActionListener(this);
	optionsPanel.add(PaintAttributes.SOLID_PAINT, solidColor);

	gradientOptions = new GradientOptionsPanel();
	gradientOptions.addActionListener(this);
	optionsPanel.add(PaintAttributes.GRADIENT_PAINT, gradientOptions);

	JLabel label = new JLabel("Not Implemented", SwingConstants.CENTER);
	optionsPanel.add(PaintAttributes.TEXTURE_PAINT, label);

	gridbag.weightx = 1;
        gridbag.weighty = 1;
	layout.setConstraints(optionsPanel, gridbag);
        add(optionsPanel);
	
	setPaint(paint);
    }

    public void actionPerformed(ActionEvent e) {
	AbstractButton button = (AbstractButton)e.getSource();
	Color newColor = JColorChooser.showDialog(button, button.getText(), button.getBackground());
	if(newColor != null) {
	    button.setBackground(newColor);
	    colorChanged = true;
	}
    }

    public Paint getPaint() {
	if(typeChanged || colorChanged) {
	    if(paintType == PaintAttributes.NO_PAINT) {
		paint = null;
	    } else if(paintType == PaintAttributes.SOLID_PAINT) {
		paint = solidColor.getBackground();
	    } else if(paintType == PaintAttributes.GRADIENT_PAINT) {
		paint = new GradientPaint(0, 0, gradientOptions.getColor(gradientOptions.COLOR_ONE),
					  1, 1, gradientOptions.getColor(gradientOptions.COLOR_TWO));
	    } else {
		JOptionPane.showMessageDialog(this,
					      "Paint type: " + paintType + " is not implemented",
					      "Paint Creation Error",
					      JOptionPane.ERROR_MESSAGE);
		paint = null;
	    }
	}
	return paint;
    }

    public void setPaint(Paint paint) {
	this.paint = paint;
	if(paint == null) {
	    setType(PaintAttributes.NO_PAINT);
	} else if(paint instanceof Color) {
	    solidColor.setBackground((Color)paint);
	    setType(PaintAttributes.SOLID_PAINT);
	} else if(paint instanceof GradientPaint) {
	    gradientOptions.setColor(gradientOptions.COLOR_ONE, ((GradientPaint)paint).getColor1());
	    gradientOptions.setColor(gradientOptions.COLOR_TWO, ((GradientPaint)paint).getColor2());
	    setType(PaintAttributes.GRADIENT_PAINT);
	} else {
	    JOptionPane.showMessageDialog(this,
					  "Paint type: " + paint.getClass().getName() + " is not implemented",
					  "Paint Creation Error",
					  JOptionPane.ERROR_MESSAGE);
	    paint = null;
	    setType(PaintAttributes.NO_PAINT);
	}
    }
    
    public String getType() {
	return paintType;
    }

    public void setType(String paintType) {
	if(this.paintType != paintType) {
	    optionsLayout.show(optionsPanel, paintType);
	    this.paintType = paintType;
	    typeChanged = true;
	    typeSelectionPanel.setType(paintType);
	}
    }
}

class GradientOptionsPanel extends JPanel {
    public final static int COLOR_ONE = 0;
    public final static int COLOR_TWO = 1;

    final String[] id = {"Color One", "Color Two"};
    JLabel[] label = new JLabel[id.length];
    JButton[] color = new JButton[id.length];
    GradientPointsPanel pointsPanel;

    {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);
	
	pointsPanel = new GradientPointsPanel();

	for(int i = 0; i < id.length; i++) {
	    label[i] = new JLabel(id[i] + ":");
	    color[i] = new JButton();
	    color[i].setBackground(new Color((float)Math.random(),
					     (float)Math.random(),
					     (float)Math.random()));
	    pointsPanel.setColorComponent(i, color[i]);
	    color[i].setToolTipText(id[i]);
	}

	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weightx = 0;
        gridbag.weighty = 0;

	for(int i = 0; i < id.length; i++) {
	    if(i == id.length - 2) {
		gridbag.gridwidth = GridBagConstraints.RELATIVE;
	    } else if(i == id.length - 1) {
		gridbag.gridwidth = GridBagConstraints.REMAINDER;
	    } else {
		gridbag.gridwidth = 1;
	    }
	    layout.setConstraints(label[i], gridbag);
	    add(label[i]);
	}

	gridbag.insets.left = 10;
	gridbag.insets.right = 10;
	gridbag.weightx = .5;
        gridbag.weighty = .5;

	for(int i = 0; i < id.length; i++) {
	    if(i == id.length - 2) {
		gridbag.gridwidth = GridBagConstraints.RELATIVE;
	    } else if(i == id.length - 1) {
		gridbag.gridwidth = GridBagConstraints.REMAINDER;
	    } else {
		gridbag.gridwidth = 1;
	    }
	    layout.setConstraints(color[i], gridbag);
	    add(color[i]);
	}

	int inset = 5;
	gridbag.insets.left = inset;
	gridbag.insets.right = inset;
	gridbag.insets.top = inset;
	gridbag.insets.bottom = inset;
	gridbag.weightx = 1;
        gridbag.weighty = 1;
	layout.setConstraints(pointsPanel, gridbag);
	add(pointsPanel);
    }
    
    public void addActionListener(ActionListener listener) {
	for(int i = 0; i < id.length; i++) {
	    color[i].addActionListener(listener);
	}
    }
    
    public Color getColor(int index) {
	return color[index].getBackground();
    }

    public void setColor(int index, Color newColor) {
	color[index].setBackground(newColor);
    }
}

class GradientPointsPanel extends JPanel implements PropertyChangeListener {
    int internalSize = 3;
    int externalSize = 15;
    Object antialiasHint = RenderingHints.VALUE_ANTIALIAS_ON;
    
    Color[] paint = {Color.black,  // normal
		     Color.yellow, // hover
		     Color.red};   // selected
    
    final static int NORMAL = 0;
    final static int HOVER = 1;
    final static int SELECTED = 2;
    
    int[] state = {NORMAL, NORMAL};
    Point2D.Float[] point = {new Point2D.Float(),
			     new Point2D.Float()};
    PointRatio[] ratio = {new PointRatio(.25f, .25f),
			  new PointRatio(.75f, .75f)};
    Component[] colorComponent = new Component[2];
    Stroke stroke = new BasicStroke(3);
    
    {
	setBorder(BorderFactory.createEtchedBorder(EtchedBorder.RAISED));
	setPreferredSize(new Dimension(75, 75));
	addMouseMotionListener(new MouseMotionAdapter() {
		public void mouseMoved(MouseEvent e) {
		    Point p = e.getPoint();
		    boolean changed = false;
		    for(int i = 0; i < point.length; i++) {
			if(Math.abs(point[i].x - p.x) <= externalSize &&
			   Math.abs(point[i].y - p.y) <= externalSize) {
			    if(state[i] == NORMAL) {
				state[i] = HOVER;
				changed = true;
			    }
			} else if(state[i] != NORMAL) {
			    state[i] = NORMAL;
			    changed = true;
			}
		    }
		    if(changed) {
			repaint();
		    }
		}
		
		public void mouseDragged(MouseEvent e) {
		    Point p = e.getPoint();
		    boolean changed = false;
		    for(int i = 0; i < point.length; i++) {
			if(state[i] == SELECTED) {
			    ratio[i].x = (float)p.x / getWidth();
			    ratio[i].y = (float)p.y / getHeight();
			    changed = true;
			}
		    }
		    if(changed) {
			repaint();
		    }
		}
	    });

	addMouseListener(new MouseAdapter() {
		public void mousePressed(MouseEvent e) {
		    boolean changed = transitionState(HOVER, SELECTED, 1);
		    if(changed) {
			transitionState(HOVER, NORMAL, -1);
			repaint();
		    }
		}
		
		public void mouseReleased(MouseEvent e) {
		    boolean changed = transitionState(SELECTED, HOVER, -1);
		    if(changed) {
			repaint();
		    }
		}

		public void mouseExited(MouseEvent e) {
		    boolean changed = transitionState(HOVER, NORMAL, -1);
		    if(changed) {
			repaint();
		    }
		}

		private boolean transitionState(int sourceState, int destinationState, int transitions) {
		    boolean changed = false;
		    for(int i = 0; i < point.length && transitions != 0; i++) {
			if(state[i] == sourceState) {
			    state[i] = destinationState;
			    transitions--;
			    changed = true;
			}
		    }
		    return changed;
		}
	    });
    }
				
    private void updatePoints() {
	for(int i = 0; i < point.length; i++) {
	    point[i].x = ratio[i].x * getWidth();
	    point[i].y = ratio[i].y * getHeight();
	}
    }

    public void paintComponent(Graphics g) {
	updatePoints();
	Graphics2D g2d = (Graphics2D)g;
	g2d.setPaint(new GradientPaint(point[0], colorComponent[0].getBackground(),
				       point[1], colorComponent[1].getBackground()));
	g2d.fillRect(0, 0, getWidth(), getHeight());
	g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, antialiasHint);
	g2d.setStroke(stroke);
	for(int i = 0; i < point.length; i++) {
	    g2d.setPaint(paint[state[i]]);
	    g2d.fillOval((int)(point[i].x - internalSize / 2),
			 (int)(point[i].y - internalSize / 2),
			 internalSize + 1, internalSize + 1);
	    g2d.drawOval((int)(point[i].x - externalSize / 2),
			 (int)(point[i].y - externalSize / 2),
			 externalSize, externalSize);
	}
    }

    public void setColorComponent(int index, JComponent component) {
	component.addPropertyChangeListener(this);
	colorComponent[index] = component;
	repaint();
    }

    public void propertyChange(PropertyChangeEvent e) {
	if(e.getPropertyName().equals("background")) {
	    repaint();
	}
    }
}

class PointRatio {
    float x;
    float y;
    
    public PointRatio(float x, float y) {
	this.x = x;
	this.y = y;
    }
}
