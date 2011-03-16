package org.himinbi.dataset;

import java.awt.*;
import java.awt.geom.*;
import java.util.*;
import javax.swing.tree.*;

public class LeafDataSet extends TreeModelDataSet implements OneDimensionalDataSet {
    Vector data = new Vector();
    GeneralPath basePath;
    double max = Double.NEGATIVE_INFINITY;
    double min = Double.POSITIVE_INFINITY;
    OneDimensionalDataSet lastReference;
    Dimension lastSize;

    public LeafDataSet(String name) {
        super(name);
    }

    public LeafDataSet(String name, String units) {
        super(name, units);
    }

    public LeafDataSet(String name, String units, BranchDataSet parent) {
        super(name, units, parent);
    }

    public void addPoint(double point) {
        addPoint(new Double(point));
    }

    public void addPoint(Object point) {
        data.add(point);
	max = Double.NEGATIVE_INFINITY;
	min = Double.POSITIVE_INFINITY;
    }

    public Object getPoint(int index) {
	return data.elementAt(index);
    }

    public void setPoint(int index, Object value) {
	data.set(index, value);
    }

    public double getValue(int index) {
	return ((Double)data.elementAt(index)).doubleValue();
    }

    public TreeModelDataSet getNode(String nodeName) {
	TreeModelDataSet node = null;
	if(name.equals(nodeName)) {
	    node = this;
	}
	return node;
    }

    public double getMin() {
	if(min == Double.POSITIVE_INFINITY) {
	    synchronized(data) {
		for(int i = 0; i < data.size(); i++) {
		    min = Math.min(min, getValue(i));
		}
	    }
	}
	return min;
    }
    
    public double getMax() {
	if(max == Double.NEGATIVE_INFINITY) {
	    synchronized(data) {
		for(int i = 0; i < data.size(); i++) {
		    max = Math.max(max, getValue(i));
		}
	    }
	}
	return max;
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
            if(name != null) {
                path.append(name);
            }
            if(units != null) {
                path.append((name == null ? " " : "") + "(" + units + ")");
            }
        }
        return path;
    }

    public int getLeafCount() {
	return 1;
    }

    public LeafDataSet getLeaf(int index) {
	if(index != 0) {
	    System.err.println("Asked to return leaf #" + index + " from " + this);
	}
	return this;
    }

    public Class getDataClass() {
	return Double.class;
    }

    public int getRowCount() {
	return data.size();
    }

    public boolean isCellEditable(int rowIndex) {
	return false;
    }

    /**
     * Maps the dataset relative to another set. The mapping is done from
     *  the data space (i.e. the units of the data sets) to the graph space
     * (i.e. the units of the canvas being drawn on). The mapping requires:
     * <ol>
     *   <li>
     *     A data set to draw relative to. The relative set is used for
     *     the x-axis and this set is on the y-axis.
     *   </li>
     *   <li>
     *     The data space of the viewport. 
     *   </li>
     *   <li>
     *     The canvas space of the viewport.
     *   </li>
     * <ol>
     */
    public Shape getPath(OneDimensionalDataSet reference, Rectangle2D viewDataSpace, Rectangle2D viewCanvasSpace) {
	if(reference == null) {
	    reference = this;
	}
	/* The path is not actually drawn every time. Drawing the path requires
	 *  cycling through all the points several times so to save that overhead
	 *  a base path is saved in an independent coordinate frame and then
	 *  affine transformations are applied to generate the path for this
	 *  viewport.
	 */
	if(lastReference != reference || basePath == null) {
	    basePath = DataSetManager.createPath(this, reference);
	    lastReference = reference;
	}
	Rectangle2D pathDataSpace = new Rectangle2D.Double(reference.getMin(),
							   getMin(),
                                                           reference.getMax() - reference.getMin(),
                                                           getMax() - getMin());
	return DataSetManager.transformPath(basePath,
					    pathDataSpace,
					    viewDataSpace,
					    viewCanvasSpace);
    }
}
