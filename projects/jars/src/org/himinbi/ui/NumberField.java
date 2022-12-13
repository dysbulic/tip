package org.himinbi.ui;

import javax.swing.*; 
import javax.swing.text.*; 
import java.awt.*;
import java.text.*;
import java.util.*;

public class NumberField extends JTextField {
    private Toolkit toolkit;
    private NumberFormat integerFormat = NumberFormat.getNumberInstance(Locale.US);

    public NumberField() {
	this(0, 10);
    }

    public NumberField(int value) {
	this(value, 10);
    }

    public NumberField(int value, int columns) {
        super(columns);
        //integerFormatter.setParseIntegerOnly(true);
        setValue(value);
    }

    public float getValue() {
        int returnValue = 0;
        try {
            returnValue = integerFormat.parse(getText()).intValue();
        } catch (ParseException e) {
        }
        return returnValue;
    }

    public void setValue(float value) {
        setText(integerFormat.format(value));
    }

    protected Document createDefaultModel() {
        return new NumberDocument();
    }

    protected class NumberDocument extends PlainDocument {
        public void insertString(int offset, String string, AttributeSet a) 
	    throws BadLocationException {
            char[] source = string.toCharArray();
            char[] result = new char[source.length];
            int j = 0;

            for (int i = 0; i < result.length; i++) {
                if(Character.isDigit(source[i])) {
                    result[j++] = source[i];
                }
            }
            super.insertString(offset, new String(result, 0, j), a);
        }
    }
}
