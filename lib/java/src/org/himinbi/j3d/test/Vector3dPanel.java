package org.himinbi.j3d.test;

import javax.swing.*;
import java.awt.*;
import javax.vecmath.*;

public class Vector3dPanel extends JPanel {
    JTextField[] field = new JTextField[3];

    public Vector3dPanel(Vector3d vector) {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	setLayout(layout);

	gridbag.fill = GridBagConstraints.HORIZONTAL;
	gridbag.weightx = 1;

	int i = 0;
	for(i = 0; i < field.length  - 1; i++) {
	    field[i] = new JTextField();
	    layout.setConstraints(field[i], gridbag);
	    add(field[i]);
	}
	field[i] = new JTextField();
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	layout.setConstraints(field[i], gridbag);
	add(field[i]);

	JLabel label = new JLabel("Angles are in degrees");
	layout.setConstraints(label, gridbag);
	add(label);

	setVector(vector);
    }

    public void setVector(Vector3d vector) {
	field[0].setText(Double.toString(vector.x));
	field[1].setText(Double.toString(vector.y));
	field[2].setText(Double.toString(vector.z));
    }

    public Vector3d getVector() {
	Vector3d vector = new Vector3d();
	try {
	    vector.x = Math.toRadians
		(Double.parseDouble(field[0].getText()));
	} catch(NumberFormatException e) {
	    field[0].setText("0");
	}
	try {
	    vector.y = Math.toRadians
		(Double.parseDouble(field[1].getText()));
	} catch(NumberFormatException e) {
	    field[1].setText("0");
	}
	try {
	    vector.z = Math.toRadians
		(Double.parseDouble(field[2].getText()));
	} catch(NumberFormatException e) {
	    field[3].setText("0");
	}
	return vector;
    }
}
