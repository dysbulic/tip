package org.himinbi.j3d;

import javax.media.j3d.TransformGroup;
import org.himinbi.util.IndexedItem;
import org.himinbi.dataset.OneDimensionalDataSet;

public abstract class ItemDataBind implements IndexedItem {
    OneDimensionalDataSet[] data;

    public ItemDataBind(OneDimensionalDataSet[] data) {
	this.data = data;
    }

    public OneDimensionalDataSet getData(int index) {
	return data[index];
    }

    public void setData(int index, OneDimensionalDataSet data) {
	this.data[index] = data;
    }

    public void setData(OneDimensionalDataSet[] data) {
	this.data = data;
    }
    
    public abstract void setIndex(int index);

    public int getMaxRowCount() {
	int max = Integer.MIN_VALUE;
	for(int i = data.length - 1; i >= 0; i--) {
	    max = Math.max(max, data[i].getRowCount());
	}
	return max;
    }
}
