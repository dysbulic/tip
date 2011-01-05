package org.himinbi.app;

import java.awt.*;
import java.awt.event.*;
import java.awt.geom.*;
import java.awt.font.*;
import javax.swing.*;
import javax.swing.text.*;
import javax.swing.border.*;
import javax.swing.event.*;
import org.himinbi.ui.*;
import org.himinbi.util.*;

public class RotateTest extends JPanel {
    OrientedJLabel label;
    int numMajorTicks = 12;
    int numMinorTicks = 36;
    JSlider thetaSlider = new JSlider(SwingConstants.VERTICAL, 0, 360, 0);
    int sliderLength = thetaSlider.getMaximum() - thetaSlider.getMinimum();
    int stepSize = sliderLength / 360;
    JTextField textField;
    Runner runner;

    public RotateTest() {
	try {
	    runner = new Runner(this, getClass().getMethod("step", (Class[])null), 50);
	} catch(NoSuchMethodException e) {
	    e.printStackTrace(System.err);
	}

	GridBagLayout layout = new GridBagLayout();
        GridBagConstraints gridbag = new GridBagConstraints();
        setLayout(layout);

	label = new OrientedJLabel("Spinning Text", 0, SwingConstants.CENTER) {
		{
		    setBorder(BorderFactory.createBevelBorder(BevelBorder.LOWERED));
		    addMouseMotionListener(new MouseMotionAdapter() {
			    public void mouseDragged(MouseEvent e) {
				setAngle(e.getPoint());
			    }
			});
		    addMouseListener(new MouseAdapter() {
			    public void mouseDown(MouseEvent e) {
				setAngle(e.getPoint());
			    }
			});
		}
		
		protected void setAngle(Point2D point) {
		    Point2D center = new Point2D.Double(getWidth() / 2, getHeight() / 2);
		    int angle = 0;
		    if(point.getY() > center.getY()) {
			angle = (int)Math.toDegrees(Math.atan2(point.getY() - center.getY(),
							       point.getX() - center.getX()));
		    } else {
			angle = (int)Math.toDegrees(Math.atan2(center.getY() - point.getY(),
							       center.getX() - point.getX())) + 180;
		    }
		    thetaSlider.setValue(angle);
		}
	    };

        gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	gridbag.gridheight = 3;
	layout.setConstraints(label, gridbag);
	add(label);

	JLabel elementLabel = new JLabel("Angle:");
	gridbag.weightx = 0;
	gridbag.weighty = 0;
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.gridheight = 1;
        layout.setConstraints(elementLabel, gridbag);
        add(elementLabel);

	thetaSlider.addChangeListener(new ChangeListener() {
		public void stateChanged(ChangeEvent e) {
		    label.setOrientation(Math.toRadians(thetaSlider.getValue()));
		}
	    });
	thetaSlider.setMajorTickSpacing((int)(sliderLength / numMajorTicks));
	thetaSlider.setMinorTickSpacing((int)(sliderLength / numMinorTicks));
	thetaSlider.setPaintTicks(true);
	thetaSlider.setPaintLabels(true);

	gridbag.weightx = 0;
	gridbag.weighty = 1;
        layout.setConstraints(thetaSlider, gridbag);
	add(thetaSlider);

	JButton button = new JButton("Animate") {
		{
		    addActionListener(new ActionListener() {
			    public void actionPerformed(ActionEvent e) {
				runner.setRunning(!runner.isRunning());
				if(runner.isRunning()) {
				    setText("Stop");
				} else {
				    setText("Animate");
				}
			    }
			});
		}
	    };
	
	gridbag.weighty = 0;
        layout.setConstraints(button, gridbag);
	add(button);

	textField = new JTextField(label.getText());
	textField.getDocument().addDocumentListener(new DocumentListener() {
		public void changedUpdate(DocumentEvent e) {
		    label.setText(textField.getText());
		}
		
		public void insertUpdate(DocumentEvent e) {
		    label.setText(textField.getText());
		}
		
		public void removeUpdate(DocumentEvent e) {
		    label.setText(textField.getText());
		}
	    });

        layout.setConstraints(textField, gridbag);
	add(textField);
    }

    public void step() {
	thetaSlider.setValue(thetaSlider.getMinimum() +
			     (thetaSlider.getValue() + stepSize) % sliderLength);
    }
}
