package org.himinbi.j3d.test;

import javax.swing.*;
import javax.vecmath.*;
import java.awt.*;
import java.text.*;

public class Matrix4dPanel extends JPanel {
    JTextField[][] field = new JTextField[4][4];
    NumberFormat format = new DecimalFormat("###.00");

    public Matrix4dPanel(Matrix4d matrix) {
	setLayout(new GridLayout(4, 4));

	for(int i = 0, j = 0; i < field.length; i++) {
	    for(j = 0; j < field[i].length; j++) {
		field[i][j] = new JTextField();
		add(field[i][j]);
	    }
	}
	setMatrix(matrix);
    }

    public void setMatrix(Matrix4d matrix) {
	for(int i = 0, j = 0; i < field.length; i++) {
	    for(j = 0; j < field[i].length; j++) {
		field[i][j].setText
		    (format.format(matrix.getElement(i, j)));
		    //(format.format(matrix.getElement(j, i)));
	    }
	}
    }

    public Matrix4d getMatrix() {
	Matrix4d matrix = new Matrix4d();

	for(int i = 0, j = 0; i < field.length; i++) {
	    for(j = 0; j < field[i].length; j++) {
		try {
		    matrix.setElement
			(i, j, Double.parseDouble(field[i][j].getText()));
		    //(j, i, Double.parseDouble(field[i][j].getText()));
		} catch(NumberFormatException e) {
		    field[i][j].setText("0");
		}
	    }
	}
	return matrix;
    }
}
