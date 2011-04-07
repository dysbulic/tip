package org.himinbi.ui;

import java.awt.*;
import java.awt.event.*;
import javax.swing.*;

public class TypeSelectionPanel extends JPanel {
    JComboBox typeCombo;
    TypedComponent component;

    public TypeSelectionPanel(String typeTitle, String[] types, TypedComponent typedComponent) {
	component = typedComponent;

	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);

	JLabel panelLabel = new JLabel(typeTitle);
	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weightx = 0;
	gridbag.weighty = 1;
	gridbag.gridwidth = 1;
	layout.setConstraints(panelLabel, gridbag);
	add(panelLabel);
	
	typeCombo = new JComboBox(types);
	typeCombo.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    component.setType(getType());
		}
	    });
	gridbag.insets.left = 10;
	gridbag.gridwidth = GridBagConstraints.RELATIVE;
	layout.setConstraints(typeCombo, gridbag);
	add(typeCombo);

	JLabel blankLabel = new JLabel();
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.weightx = 1;
	layout.setConstraints(blankLabel, gridbag);
	add(blankLabel);
    }

    public String getType() {
	return (String)typeCombo.getSelectedItem();
    }

    public void setType(String id) {
	typeCombo.setSelectedItem(id);
    }
}

