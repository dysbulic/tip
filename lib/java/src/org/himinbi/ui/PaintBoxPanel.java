package org.himinbi.ui;

import java.awt.*;
import java.awt.event.*;
import java.util.*;
import javax.swing.*;

public class PaintBoxPanel extends JPanel {
    JComboBox paintNames = new JComboBox();
    PaintAttributesPropertiesPanel paintAttributes = new PaintAttributesPropertiesPanel();
    Hashtable paints = new Hashtable();

    public PaintBoxPanel() {
	GridBagLayout layout = new GridBagLayout();
        GridBagConstraints gridbag = new GridBagConstraints();
        setLayout(layout);
        gridbag.fill = GridBagConstraints.BOTH;
        gridbag.weightx = 1;
        gridbag.weighty = 0;
        gridbag.gridwidth = GridBagConstraints.REMAINDER;
	paintNames.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    PaintAttributes paint = (PaintAttributes)paints.get(paintNames.getSelectedItem());
		    if(paint != null) {
			paintAttributes.setAttributes(paint);
		    }
		}
	    });
        layout.setConstraints(paintNames, gridbag);
        add(paintNames);

	gridbag.weighty = 1;
        layout.setConstraints(paintAttributes, gridbag);
        add(paintAttributes);
    }

    public void addPaints(Hashtable paintBox) {
	Enumeration keys = paintBox.keys();
	Object key;
	while(keys.hasMoreElements()) {
	    key = keys.nextElement();
	    paintNames.addItem(key);
	    paints.put(key, paintBox.get(key));
	}
    }
}
