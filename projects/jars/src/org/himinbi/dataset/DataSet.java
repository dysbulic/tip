package org.himinbi.dataset;

import java.awt.geom.*;
import java.util.*;
import javax.swing.event.*;
import javax.swing.table.*;

public abstract class DataSet implements TableModel {
    String name;
    EventListenerList listeners = new EventListenerList();

    DataSet(String name) {
        this.name = name != null ? name : new String();
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
	this.name = name;
    }

    public String toString() {
        return name;
    }

    public abstract double getMin();
    public abstract double getMax();
    
    /* Methods necessary to implement TableModel
     */
    public abstract Class getColumnClass(int columnIndex);
    public abstract int getColumnCount();
    public abstract String getColumnName(int columnIndex); 
    public abstract int getRowCount();
    public abstract Object getValueAt(int rowIndex, int columnIndex);
    public abstract boolean isCellEditable(int rowIndex, int columnIndex);
    public abstract void setValueAt(Object aValue, int rowIndex, int columnIndex);

    public boolean isTableModelListener(TableModelListener listener) {
        Object[] listeners = this.listeners.getListenerList();
        boolean found = false;
        for(int i = listeners.length - 2; i >= 0 && !found; i -= 2) {
            found = listener == listeners[i + 1];
        }
        return found;
    }

    public void addTableModelListener(TableModelListener listener) {
        listeners.add(TableModelListener.class, listener);
    }

    public void removeTableModelListener(TableModelListener listener) {
        listeners.remove(TableModelListener.class, listener);
    }

    protected void fireTableModelChange(TableModelEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == TableModelListener.class) {
                ((TableModelListener)listeners[i + 1]).tableChanged(e);
            }
        }
    }
}
