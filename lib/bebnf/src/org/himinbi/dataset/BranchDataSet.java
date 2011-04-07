package org.himinbi.dataset;

import java.io.*;
import java.util.*;
import java.awt.geom.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.tree.*;
import javax.swing.event.*;

public class BranchDataSet extends TreeModelDataSet {
    Vector children = new Vector();
    String pathSeparator = ", ";
    boolean shortTreeString = true;

    public BranchDataSet(String name) {
	super(name);
    }

    public BranchDataSet(String name, String units) {
	super(name, units);
    }

    public BranchDataSet(String name, String units, BranchDataSet parent) {
	super(name, units, parent);
    }

    public double getMin() {
	double min = Double.POSITIVE_INFINITY;
	for(int i = children.size() - 1; i >= 0; i--) {
	    min = Math.min(min, getChild(i).getMin());
	}
	return min;
    }

    public double getMax() {
	double max = Double.NEGATIVE_INFINITY;
	for(int i = children.size() - 1; i >= 0; i--) {
	    max = Math.max(max, getChild(i).getMax());
	}
	return max;
    }

    public TreeModelDataSet getNode(String nodeName) {
	TreeModelDataSet node = null;
	for(int i = children.size() - 1; i >= 0 && node == null; i--) {
	    if(getChild(i).getName().equalsIgnoreCase(nodeName)) {
		node = getChild(i);
	    } else {
		node = getChild(i).getNode(nodeName);
	    }
	}
	return node;
    }

    public String getTreeAsString() {
	return getTreeAsString(null).toString();
    }

    protected StringBuffer getTreeAsString(StringBuffer path) {
	/* This function must travel to the root of the tree and then back down.
	 * So long as the path is null it travels farther up until it reaches
	 *  a node with no parent, then it goes back down breadthwise and builds
	 *  the name.
	 */
	if(path == null && parent != null) {
	    path = parent.getTreeAsString(path); // Go up
	} else {
	    if(path == null) {
		path = new StringBuffer();
	    }
	    /* These paths get long for large sets of nodes and so a shortening
	     *  option exists that will only list groups that add information
	     *  about the data; i.e. paths which give units for subpaths
	     */
	    if(!shortTreeString || units != null) {
		if(name != null) {
		    path.append(name);
		}
		if(units != null) {
		    path.append((name == null ? " " : "") + "(" + units + ")");
		}
	    }
	    path.append("[");
	    for(int i = 0; i < children.size(); i++) {
		getChild(i).getTreeAsString(path);
		if(i < children.size() - 1) {
		    path.append(pathSeparator);
		}
	    }
	    path.append("]");	    
	}
	return path;
    }

    /* Methods to support tree model
     */

    public void addChild(TreeModelDataSet child) {
	child.setParent(this);
	children.add(child);
	fireTableModelChange(new TableModelEvent(this, 0, getRowCount(), getChildCount()));
	fireTreeStructureChange(new TreeModelEvent(this, getPathToRoot(), null, null));
    }

    public void insertChildAt(TreeModelDataSet child, int index) {
	child.setParent(this);
	children.insertElementAt(child, index);
    }

    public TreeModelDataSet getChild(int index) {
	return (TreeModelDataSet)children.get(index);
    }

    public int getChildCount() {
	return children.size();
    }

    public int getIndex(TreeModelDataSet child) {
	return children.indexOf(child);
    }

    public int getLeafCount() {
	int sum = 0;
        for(int i = 0; i < children.size(); i++) {
            sum += getChild(i).getLeafCount();
        }
        return sum;
    }

    public LeafDataSet getLeaf(int index) {
	int i = 0;
	for(i = 0; i < children.size() && index >= getChild(i).getColumnCount(); i++) {
	    index -= getChild(i).getColumnCount();
	}
	return getChild(i).getLeaf(index);
    }

    public int getRowCount() {
	int max = Integer.MIN_VALUE;
	for(int i = 0; i < children.size(); i++) {
	    max = Math.max(max, getChild(i).getRowCount());
	}
	return max;
    }
}
