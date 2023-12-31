package org.himinbi.j3d;

import org.himinbi.dataset.*;
import javax.media.j3d.*;

public class TransformScaleBind extends TransformDataBind {
    public final static int SCALE = 0;

    public TransformScaleBind(OneDimensionalDataSet scale, 
			      TransformGroup transformGroup) {
	this(new OneDimensionalDataSet[] {scale}, transformGroup);
    }

    public TransformScaleBind(OneDimensionalDataSet[] data) {
	this(data, null);
    }

    public TransformScaleBind(OneDimensionalDataSet[] data,
			      TransformGroup transformGroup) {
	super(data, transformGroup);
    }

    public void setIndex(int index) {
        Transform3D scale = new Transform3D();
        transformGroup.getTransform(scale);
        scale.setScale(data[SCALE].getValue(index));
        transformGroup.setTransform(scale);
    }
}
