package org.himinbi.ui;

import java.awt.*;
import java.awt.event.*;
import javax.swing.*;

public class PaintAttributesPropertiesPanel extends JPanel {
    PaintAttributes attributes;
    PaintOptionsPanel paintOptions;
    StrokeOptionsPanel strokeOptions;
   
    public PaintAttributesPropertiesPanel() {
	this(new PaintAttributes());
    }
    
    public PaintAttributesPropertiesPanel(PaintAttributes attributes) {
	this.attributes = attributes;
	
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);
	
	paintOptions = new PaintOptionsPanel(attributes.getPaint());
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.weightx = 1;
        gridbag.weighty = 1;
	layout.setConstraints(paintOptions, gridbag);
        add(paintOptions);

	strokeOptions = new StrokeOptionsPanel(attributes.getStroke());
        gridbag.weighty = 0;
	layout.setConstraints(strokeOptions, gridbag);
        add(strokeOptions);

	JButton button = new JButton("OK");
	button.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    commit();
		}
	    });
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	layout.setConstraints(button, gridbag);
	add(button);

	button = new JButton("Reset");
	button.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    reset();
		}
	    });
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	layout.setConstraints(button, gridbag);
	add(button);
    }
    
    public void setAttributes(PaintAttributes attributes) {
	this.attributes = attributes;
	reset();
    }

    public void reset() {
	paintOptions.setPaint(attributes.getPaint());
	strokeOptions.setStroke(attributes.getStroke());
    }

    public void commit() {
	attributes.setPaint(paintOptions.getPaint());
	attributes.setStroke(strokeOptions.getStroke());
    }
}
