package org.himinbi.j3d;

import org.himinbi.dataset.*;
import javax.vecmath.*;
import javax.media.j3d.*;

public class TransformPositionBind extends TransformDataBind {
    public final static int XAXIS = 0;
    public final static int YAXIS = 1;
    public final static int ZAXIS = 2;
    
    public TransformPositionBind(OneDimensionalDataSet xData,
				 OneDimensionalDataSet yData,
				 OneDimensionalDataSet zData,
				 TransformGroup transformGroup) {
	this(new OneDimensionalDataSet[] {xData, yData, zData}, transformGroup);
    }

    public TransformPositionBind(OneDimensionalDataSet[] data) {
	this(data, null);
    }

    public TransformPositionBind(OneDimensionalDataSet[] data, 
				 TransformGroup transformGroup) {
	super(data, transformGroup);
    }

    public void setIndex(int index) {
        Transform3D position = new Transform3D();
        transformGroup.getTransform(position);
        position.setTranslation(new Vector3d(data[XAXIS].getValue(index),
					     data[YAXIS].getValue(index),
					     data[ZAXIS].getValue(index)));
        transformGroup.setTransform(position);
    }
}
