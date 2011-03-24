package org.himinbi.ui;

import java.util.*;
import java.awt.*;
import java.awt.geom.*;
import java.awt.event.*;
import javax.swing.*;

public class StrokeOptionsPanel extends JPanel implements ActionListener {
    StrokePropertiesPanel strokeProperties = new StrokePropertiesPanel();
    DashPanel dashProperties = new DashPanel();
    StrokeViewPanel strokeView = new StrokeViewPanel();

    public StrokeOptionsPanel() {
	this(new BasicStroke());
    }

    public StrokeOptionsPanel(Stroke stroke) {
	JTabbedPane properties = new JTabbedPane();
        properties.add("Properties", strokeProperties);
	properties.add("Dashes", dashProperties);
	
	GridBagLayout layout = new GridBagLayout();
        GridBagConstraints gridbag = new GridBagConstraints();
        setLayout(layout);
	gridbag.fill = GridBagConstraints.BOTH;
        gridbag.weightx = 0;
        gridbag.weighty = 1;
        gridbag.gridwidth = GridBagConstraints.RELATIVE;
        layout.setConstraints(properties, gridbag);
	add(properties);

        gridbag.weightx = 1;
        gridbag.gridwidth = GridBagConstraints.REMAINDER;
        layout.setConstraints(strokeView, gridbag);
	add(strokeView);

	strokeProperties.addActionListener(this);
	dashProperties.addActionListener(this);
	
	setStroke(stroke);
    }
    
    public Stroke getStroke() {
	return new BasicStroke(dashProperties.getLineWidth(),
			       strokeProperties.getCapType(),
			       strokeProperties.getJoinType(),
			       strokeProperties.getMiterLimit(),
			       dashProperties.getDashes(),
			       dashProperties.getDashOffset());
    }

    public void setStroke(Stroke stroke) {
	if(stroke instanceof BasicStroke) {
	    BasicStroke basic = (BasicStroke)stroke;
	    strokeProperties.setCapType(basic.getEndCap());
	    strokeProperties.setJoinType(basic.getLineJoin());
	    strokeProperties.setMiterLimit(basic.getMiterLimit());
	    dashProperties.setDashes(basic.getDashArray());
	    dashProperties.setDashOffset(basic.getDashPhase());
	    dashProperties.setLineWidth(basic.getLineWidth());
	    strokeView.setStroke(stroke);
	} else {
	    JOptionPane.showMessageDialog(this,
					  "Stroke type: " + stroke.getClass().getName() + " is not implemented",
					  "Stroke Creation Error",
					  JOptionPane.ERROR_MESSAGE);
	}
    }

    public void actionPerformed(ActionEvent e) {
	strokeView.setStroke(getStroke());
    }
}

class StrokeViewPanel extends JPanel {
    GeneralPath path = new GeneralPath();
    Object antialiasHint = RenderingHints.VALUE_ANTIALIAS_ON;
    Stroke stroke;

    {
	setPreferredSize(new Dimension(100, 100));
	addComponentListener(new ComponentAdapter() {
		public void componentResized(ComponentEvent e) {
		    Dimension size = getSize();
		    path.reset();
		    path.moveTo(1 * size.width / 8, 1 * size.height / 8);
		    path.lineTo(7 * size.width / 8, 4 * size.height / 8);
		    path.lineTo(1 * size.width / 8, 7 * size.height / 8);
		    path.lineTo(4 * size.width / 8, 4 * size.height / 8);
		    path.lineTo(1 * size.width / 8, 1 * size.height / 8);
		    repaint();
		}
	    });
    }

    public void setStroke(Stroke stroke) {
	this.stroke = stroke;
	repaint();
    }

    public void paintComponent(Graphics g) {
	super.paintComponent(g);

	Graphics2D g2d = (Graphics2D)g;
	g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, antialiasHint);
	g2d.setStroke(stroke);
	g2d.draw(path);
    }
}

class DashPanel extends JPanel {
    NumberField dashes = new NumberField();
    NumberField dashOffset = new NumberField();
    NumberField width = new NumberField();
    
    {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);

	JLabel label = new JLabel("Dash Array:", SwingConstants.LEFT);
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.insets.left = 5;
        gridbag.weightx = 0;
        gridbag.weighty = 0;
        gridbag.gridwidth = GridBagConstraints.RELATIVE;
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.gridwidth = GridBagConstraints.REMAINDER;
        gridbag.weightx = 1;
	layout.setConstraints(dashes, gridbag);
	add(dashes);

        gridbag.gridwidth = GridBagConstraints.RELATIVE;
        gridbag.weightx = 0;
	label = new JLabel("Dash Phase:", SwingConstants.LEFT);
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.gridwidth = GridBagConstraints.REMAINDER;
        gridbag.weightx = 1;
	layout.setConstraints(dashOffset, gridbag);
	add(dashOffset);

        gridbag.gridwidth = GridBagConstraints.RELATIVE;
        gridbag.weightx = 0;
	label = new JLabel("Width:", SwingConstants.LEFT);
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.gridwidth = GridBagConstraints.REMAINDER;
        gridbag.weightx = 1;
	layout.setConstraints(width, gridbag);
	add(width);
    }

    public void setLineWidth(float value) {
	width.setValue(value);
    }

    public float getLineWidth() {
	return width.getValue();
    }

    public void setDashOffset(float value) {
	dashOffset.setValue(value);
    }

    public float getDashOffset() {
	return dashOffset.getValue();
    }

    public float[] getDashes() {
	float[] dashArray = new float[1];
	dashArray[0] = (float)Math.max(.5, dashes.getValue());
	return dashArray;
    }

    public void setDashes(float[] dashArray) {
	if(dashArray != null && dashArray.length > 0) {
	    dashes.setValue(dashArray[0]);
	}
    }

    public void addActionListener(ActionListener listener) {
	width.addActionListener(listener);
	dashes.addActionListener(listener);
	dashOffset.addActionListener(listener);
    }

    public void removeActionListener(ActionListener listener) {
	width.removeActionListener(listener);
	dashes.removeActionListener(listener);
	dashOffset.removeActionListener(listener);
    }    
}

class StrokePropertiesPanel extends JPanel {
    NumberField miterLimit = new NumberField();
    JComboBox capType;
    JComboBox joinType;
    Hashtable capNameToIdMap = new Hashtable();
    Hashtable capIdToNameMap = new Hashtable();
    Hashtable joinNameToIdMap = new Hashtable();
    Hashtable joinIdToNameMap = new Hashtable();

    {
	capNameToIdMap.put(PaintAttributes.BUTT_CAP, new Integer(BasicStroke.CAP_BUTT));
	capNameToIdMap.put(PaintAttributes.ROUND_CAP, new Integer(BasicStroke.CAP_ROUND));
	capNameToIdMap.put(PaintAttributes.SQUARE_CAP, new Integer(BasicStroke.CAP_SQUARE));

	Enumeration names = capNameToIdMap.keys();
	Object key;
	while(names.hasMoreElements()) {
	    key = names.nextElement();
	    capIdToNameMap.put(capNameToIdMap.get(key), key);
	}

	joinNameToIdMap.put(PaintAttributes.BEVEL_JOIN, new Integer(BasicStroke.JOIN_BEVEL));
	joinNameToIdMap.put(PaintAttributes.MITER_JOIN, new Integer(BasicStroke.JOIN_MITER));
	joinNameToIdMap.put(PaintAttributes.ROUND_JOIN, new Integer(BasicStroke.JOIN_ROUND));

	names = joinNameToIdMap.keys();
	while(names.hasMoreElements()) {
	    key = names.nextElement();
	    joinIdToNameMap.put(joinNameToIdMap.get(key), key);
	}

	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);

	JLabel label = new JLabel("Miter Limit:", SwingConstants.LEFT);
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.insets.right = 5;
        gridbag.weightx = 0;
        gridbag.weighty = 0;
        gridbag.gridwidth = GridBagConstraints.RELATIVE;
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.insets.right = 0;
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	layout.setConstraints(miterLimit, gridbag);
	add(miterLimit);
	
	gridbag.insets.right = 5;
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	label = new JLabel("Cap Type:", SwingConstants.LEFT);
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.insets.right = 0;
	capType = new JComboBox(capNameToIdMap.keySet().toArray());
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	layout.setConstraints(capType, gridbag);
	add(capType);

	gridbag.insets.right = 5;
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	label = new JLabel("Join Type:", SwingConstants.LEFT);
	layout.setConstraints(label, gridbag);
	add(label);

	gridbag.insets.right = 0;
	joinType = new JComboBox(joinNameToIdMap.keySet().toArray());
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	layout.setConstraints(joinType, gridbag);
	add(joinType);
    }

    public void setMiterLimit(float value) {
	miterLimit.setValue(value);
    }

    public float getMiterLimit() {
	return (float)Math.max(1, miterLimit.getValue());
    }

    public void setCapType(int type) {
	Enumeration types = capNameToIdMap.elements();
	boolean found = false;
	Integer value = null;
	while(types.hasMoreElements() && !found) {
	    value = (Integer)types.nextElement();
	    found = (value.intValue() == type);
	}
	if(found) {
	    capType.setSelectedItem(capIdToNameMap.get(value));
	}
    }

    public int getCapType() {
	return ((Integer)capNameToIdMap.get(capType.getSelectedItem())).intValue();
    }

    public void setJoinType(int type) {
	Enumeration types = joinNameToIdMap.elements();
	boolean found = false;
	Integer value = null;
	while(types.hasMoreElements() && !found) {
	    value = (Integer)types.nextElement();
	    found = (value.intValue() == type);
	}
	if(found) {
	    joinType.setSelectedItem(joinIdToNameMap.get(value));
	}
    }

    public int getJoinType() {
	return ((Integer)joinNameToIdMap.get(joinType.getSelectedItem())).intValue();
    }
    public void addActionListener(ActionListener listener) {
        miterLimit.addActionListener(listener);
        capType.addActionListener(listener);
        joinType.addActionListener(listener);
    }

    public void removeActionListener(ActionListener listener) {
        miterLimit.removeActionListener(listener);
        capType.removeActionListener(listener);
        joinType.removeActionListener(listener);
    }
}
