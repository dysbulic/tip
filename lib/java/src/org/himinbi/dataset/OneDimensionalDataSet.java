package org.himinbi.dataset;

import java.awt.*;
import java.awt.geom.*;

public interface OneDimensionalDataSet {
    public String getName();
    public void setName(String name);
    public void addPoint(Object point);
    public Object getPoint(int index);
    public void setPoint(int index, Object value);
    public double getValue(int index);
    public double getMin();
    public double getMax();
    public int getRowCount();
    public Shape getPath(OneDimensionalDataSet reference, Rectangle2D viewDataSpace, Rectangle2D viewCanvasSpace);
}
