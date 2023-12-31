package org.himinbi.j3d;

import javax.media.j3d.TransformGroup;
import org.himinbi.util.IndexedItem;
import org.himinbi.dataset.OneDimensionalDataSet;

public abstract class TransformDataBind extends ItemDataBind {
    TransformGroup transformGroup;

    public TransformDataBind(OneDimensionalDataSet[] data) {
	this(data, null);
    }

    public TransformDataBind(OneDimensionalDataSet[] data,
			     TransformGroup transformGroup) {
	super(data);
	this.transformGroup = transformGroup;
    }

    public OneDimensionalDataSet getData(int index) {
	return data[index];
    }

    public void setData(int index, OneDimensionalDataSet data) {
	this.data[index] = data;
    }

    public TransformGroup getTransform() {
	return transformGroup;
    }

    public void setTransform(TransformGroup transformGroup) {
	this.transformGroup = transformGroup;
    }
    
    public abstract void setIndex(int index);
}
