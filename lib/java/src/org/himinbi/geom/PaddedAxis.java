package org.himinbi.geom;

import java.beans.PropertyChangeListener;

public interface PaddedAxis {
    final static int TOP = 0;
    final static int LEFT = 0;
    final static int BOTTOM = 1;
    final static int RIGHT = 1;
    final static String PAD_PROPERTY = "pad";
    public int getPad(int type);
    public void setPad(int type, int value);
    public void setBounds(double min, double max);
    public double getMin();
    public double getMax();
    public double getLength();
    public int getDivisions();
    public void setDivisions(int divisions);
    public void addPropertyChangeListener(PropertyChangeListener listener);
    public void removePropertyChangeListener(PropertyChangeListener listener);
    public boolean isPropertyChangeListener(PropertyChangeListener listener);
}
