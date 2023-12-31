package org.himinbi.j3d;

import java.text.*;
import javax.vecmath.*;
import javax.media.j3d.*;
import org.himinbi.dataset.*;
import com.xith.java3d.overlay.*;

public class LabelTextBind extends ItemDataBind {
    LabelOverlay label;
    StringBuffer line = new StringBuffer();
    DecimalFormat format = new DecimalFormat("#,###.00");
    int baseLength = 0;

    public LabelTextBind(OneDimensionalDataSet[] data) {
	this(data, null);
    }

    public LabelTextBind(OneDimensionalDataSet[] data,
			 LabelOverlay label) {
	super(data);
	this.label = label;
    }

    public LabelOverlay getLabel() {
	return label;
    }

    public void setLabel(LabelOverlay label) {
	this.label = label;
    }

    public void setBaseText(String baseText) {
	line = new StringBuffer(baseText);
	baseLength = baseText.length();
    }

    public void setFormat(String pattern) {
	format.applyPattern(pattern);
    }

    public void setIndex(int index) {
	if(data.length > 1) {
	    line.append("<");
	}

	int i;
	for(i = 0; i < data.length - 1; i++) {
	    line.append(format.format(data[i].getValue(index)));
	    line.append(", ");
	}

	line.append(format.format(data[i].getValue(index)));
	
	label.setText(line.toString());

	line.setLength(baseLength);
    }
}
