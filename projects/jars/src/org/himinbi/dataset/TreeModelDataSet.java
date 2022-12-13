package org.himinbi.dataset;

import java.io.*;
import java.util.*;
import java.awt.geom.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.tree.*;
import javax.swing.event.*;

public abstract class TreeModelDataSet extends DataSet implements TreeModel {
    BranchDataSet parent;
    String units;

    TreeModelDataSet(String name) {
	this(name, null, null);
    }

    TreeModelDataSet(String name, String units) {
	this(name, units, null);
    }

    TreeModelDataSet(String name, String units, BranchDataSet parent) {
	super(name);
	this.units = units;
	this.parent = parent;
    }

    /* Abstract methods needed to build a tree
     */

    public abstract int getLeafCount();
    public abstract LeafDataSet getLeaf(int index);

    public TreePath getPathToRoot() {
	TreePath path = null;
	if(parent != null) {
	    path = parent.getPathToRoot().pathByAddingChild(this);
	} else {
	    path = new TreePath(this);
	}
	return path;
    }

    public BranchDataSet getParent() {
	return parent;
    }

    public void setParent(BranchDataSet parent) {
	this.parent = parent;
    }

    public String getUnits() {
	String units = this.units;
	if(parent != null && units == null) {
	    units = parent.getUnits();
	}
	return units;
    }

    public String toString() {
	String name = this.name;
	if(getUnits() != null) {
	    name += " (" + getUnits() + ")";
	}
	return name;
    }

    public abstract TreeModelDataSet getNode(String nodeName);
    public abstract String getTreeAsString();
    protected abstract StringBuffer getTreeAsString(StringBuffer path);

    /* Methods to implement TableModel
        (inherited from DataSet)
     */

    public abstract int getRowCount();

    public int getColumnCount() {
	return getLeafCount();
    }

    public Class getColumnClass(int columnIndex) {
        return getLeaf(columnIndex).getDataClass();
    }

    public String getColumnName(int columnIndex) {
	return getLeaf(columnIndex).toString();
    }

    public Object getValueAt(int rowIndex, int columnIndex) {
        return getLeaf(columnIndex).getPoint(rowIndex);
    }

    public boolean isCellEditable(int rowIndex, int columnIndex) {
        return getLeaf(columnIndex).isCellEditable(rowIndex);
    }

    public void setValueAt(Object value, int rowIndex, int columnIndex) {
        getLeaf(columnIndex).setPoint(rowIndex, (Double)value);
    }

    /* Methods to implement TreeModel
     */

    public Object getChild(Object parent, int index) {
	return ((BranchDataSet)parent).getChild(index);
    }

    public int getChildCount(Object parent) {
	return ((BranchDataSet)parent).getChildCount();
    }

    public int getIndexOfChild(Object parent, Object child) {
	return ((BranchDataSet)parent).getIndex((TreeModelDataSet)child);
    }

    public Object getRoot() {
	TreeModelDataSet root;
	if(parent != null) {
	    root = (TreeModelDataSet)parent.getRoot();
	} else {
	    root = this;
	}
	return root;
    }

    public boolean isLeaf(Object leaf) {
	return leaf instanceof LeafDataSet;
    }

    public void valueForPathChanged(TreePath path, Object newValue) {
	System.out.println("Path changed to: " + newValue);
    }

    public boolean isTreeModelListener(TreeModelListener listener) {
        Object[] listeners = this.listeners.getListenerList();
        boolean found = false;
        for(int i = listeners.length - 2; i >= 0 && !found; i -= 2) {
            found = listener == listeners[i + 1];
        }
        return found;
    }

    public void addTreeModelListener(TreeModelListener listener) {
        listeners.add(TreeModelListener.class, listener);
    }

    public void removeTreeModelListener(TreeModelListener listener) {
        listeners.remove(TreeModelListener.class, listener);
    }

    protected void fireTreeNodesChange(TreeModelEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == TreeModelListener.class) {
                ((TreeModelListener)listeners[i + 1]).treeNodesChanged(e);
            }
        }
    }

    protected void fireTreeNodeInserted(TreeModelEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == TreeModelListener.class) {
                ((TreeModelListener)listeners[i + 1]).treeNodesInserted(e);
            }
        }
    }

    protected void fireTreeNodesRemoved(TreeModelEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == TreeModelListener.class) {
                ((TreeModelListener)listeners[i + 1]).treeNodesRemoved(e);
            }
        }
    }

    protected void fireTreeStructureChange(TreeModelEvent e) {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && e!= null; i -= 2) {
            if(listeners[i] == TreeModelListener.class) {
                ((TreeModelListener)listeners[i + 1]).treeStructureChanged(e);
            }
        }
    }
}
