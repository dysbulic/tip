package org.himinbi.dataset;

import java.io.*;
import java.util.*;
import java.awt.*;
import java.awt.geom.*;

/**
 * This is a class responsible for managing datasets used in a program.
 *
 * @author Will Holcomb
 */
public class DataSetManager {
    BranchDataSet files = new BranchDataSet("Files");
    public static boolean debug = false;
    
    /**
     * filename#column_name
     */
    public OneDimensionalDataSet getDataSet(String dataset) {
	int index = dataset.indexOf('#');
	OneDimensionalDataSet node = null;
	if (index >= 0) {
	    File file = new File(dataset.substring(0, index));
	    String column = dataset.substring(index + 1);
	    
	    TreeModelDataSet parent = files.getNode(file.getAbsolutePath());
	    if(parent == null) {
		try {
		    parent = parseFile(file);
		    files.addChild(parent);
		} catch(IOException e) {
		}
	    }
	    if(parent != null) {
		node = (LeafDataSet)parent.getNode(column);
	    }
	} else {
	    throw new IllegalArgumentException("No # in: " + dataset);
	}
	return node;
    }

    final static Character obChar = new Character('[');
    final static Character cbChar = new Character(']');
    final static Character opChar = new Character('(');
    final static Character cpChar = new Character(')');

    public static BranchDataSet parseFile(File file) throws IOException {
	BufferedReader reader = new BufferedReader(new FileReader(file));
	StreamTokenizer stream = new StreamTokenizer(reader);
	stream.resetSyntax();
	/* This took an ascii table and cannot be unicode compliant, but until
	 *  I write a new stream tokenizer it is better than nothing:
	 * What I want not to go into words are:
	 *  1. Space characters: ' ', '\n', '\t'
	 *  2. Parenthesis: () (These are used as grouping symbols)
	 *  3. Brackets: [] (These are used to deliniate units)
	 *  4. Quote character: " (This is used to deliniate strings)
	 *
	 * < 32 are control characters, ' ' is 32, '"' is 34, 
	 *                              '(' is 40, ')' is 41,
	 *                              '[' is 91, ']' is 93,
	 *                              '~' is 176
	 */
	stream.wordChars((int)' ' + 1, (int)'"' - 1);
	stream.wordChars((int)'"' + 1, (int)'(' - 1);
	stream.wordChars((int)')' + 1, (int)'[' - 1);
	stream.wordChars((int)'[' + 1, (int)']' - 1);
	stream.wordChars((int)']' + 1, (int)'~');
	stream.quoteChar('"');
	
	Stack groupingSymbols = new Stack();
	Vector leaves = new Vector();
	BranchDataSet root = new BranchDataSet(file.getAbsolutePath());
	String name = null;
	String units = null;
	int bktCount = 0;
	int prnCount = 0;
	while(stream.ttype != StreamTokenizer.TT_EOL) {
	    stream.nextToken();
	    switch(stream.ttype) {
	    case '"':
	    case StreamTokenizer.TT_WORD:
		/* A word is either a name or units, depending on whether the previous
		 *  character was a space or a [. The state is maintained such that
		 *  name will be null any time that a new entry is being started.
		 */
		if(name == null) {
		    name = stream.sval;
		} else {
		    units = stream.sval;
		}
		break;
	    case '[':
		if(name == null) {
		    name = new String();
		}
		groupingSymbols.push(obChar);
		break;
	    case ']':
		if(groupingSymbols.empty()) {
		    System.err.println("Mismatched brackets in header; stack empty");
		} else if(!(groupingSymbols.peek() == obChar)) {
		    System.err.println("Mismatched brackets in header;" + 
				       " expected: " + obChar + " and got " + groupingSymbols.pop());
		} else {
		    groupingSymbols.pop();
		}
		break;
	    case '(':
		groupingSymbols.push(opChar);
		BranchDataSet branch = new BranchDataSet(name, units, root);
		root.addChild(branch);
		root = branch;
		name = null;
		units = null;
		break;
	    case ')':
		if(groupingSymbols.empty()) {
		    System.err.println("Mismatched parenthesis in header; stack empty");
		} else if(!(groupingSymbols.peek() == opChar)) {
		    System.err.println("Mismatched parenthesis in header;" + 
				       " expected: " + opChar + " and got " + groupingSymbols.pop());
		} else {
		    groupingSymbols.pop();
		    LeafDataSet leaf = new LeafDataSet(name, units, root);
		    root.addChild(leaf);
		    leaves.add(leaf);
		    name = null;
		    units = null;
		    root = (BranchDataSet)root.getParent();
		}
		break;
	    case ' ':
	    case '\n':
	    case '\t':
	    case '\r':
		if(name != null) {
		    LeafDataSet leaf = new LeafDataSet(name, units, root);
		    root.addChild(leaf);
		    leaves.add(leaf);
		    name = null;
		    units = null;
		}
		break;
	    case StreamTokenizer.TT_NUMBER:
		System.err.println("Got a number in header parse. Why?");
		break;
	    default:
		System.err.println("Unexpected token type in header: " + stream.ttype + ":" + (char)stream.ttype);
	    }
	}
	while(!groupingSymbols.empty()) {
	    System.err.println("Mismatched grouping symbols: reamining: " + groupingSymbols.pop());
	}
	//stream.parseNumbers();
	int index = 0;
	while(stream.nextToken() != StreamTokenizer.TT_EOF) {
	    switch(stream.ttype) {
	    case StreamTokenizer.TT_EOL:
		index = 0;
		break;
	    case StreamTokenizer.TT_NUMBER:
		// This should not be reached. The existing stream parser doesn't support
		//  xEy format numbers so parseNumbers is not turned on
		((LeafDataSet)leaves.elementAt(index++)).addPoint(stream.nval);
		break;
	    case ' ':
	    case '\t':
	    case '\r':
		break;
	    case '"':
	    case StreamTokenizer.TT_WORD:
		try {
		    ((LeafDataSet)leaves.elementAt(index++)).addPoint(new Double(stream.sval));
		} catch(NumberFormatException e) {
		    System.err.println("Bad number at index " + index +
				       " on line " + stream.lineno() +
				       ": " + stream.sval);
		}
		break;
	    default:
		System.err.println("Unknown token type in data parse: " + stream.ttype + ":" + (char)stream.ttype);
	    }
	}
	reader.close();
	return root;
    }

    /**
     * Transforms each point in the dataset by a constant factor. Exe. to convert a
     *  dataset in degrees to radians dataset.transform(Math.PI / 180);
     */
    public static LeafDataSet transform(OneDimensionalDataSet data, double factor) {
	LeafDataSet outputData = new LeafDataSet(data.toString() + " transformed by " + factor);
	for(int i = 0; i < data.getRowCount(); i++) {
	    outputData.addPoint(data.getValue(i) * factor);
	}
	return outputData;
    }

    public static Shape transformPath(GeneralPath path,
				      Rectangle2D pathDataSpace,
				      Rectangle2D viewDataSpace,
				      Rectangle2D viewCanvasSpace) {
        Rectangle2D pathCanvasSpace = path.getBounds();
	/* There are two transformations that have to be applied. The path has to
         *  be both the correct size and the correct position. So there is a
         *  translation and a scale.
         * To ease some computations we will make up a new unit type called graph
         *  units. Already we have a data space which is what the user sees as
         *  the data that is being displayed on the graph. Also we already have
         *  canvas units which is the actual number of pixels that the path is
         *  going to be drawn on.
         * Graph units are the number of pixels that each data unit covers.
         *  For instance if the user sees a viewport from 25-150 on the x-axis
         *  and 0-300 on the y-axis, and then the actual canvas is 550x200:
         *  The graph units for the x-axis is 550 / (150 - 25) = 4.4 and the
         *  graph units for the y-axis is 200 / (300 - 0) = .66.
         * This same ratio exists for the path which has a data range and an
         *  actual pixel range and it is found in the same way.
         */
	double viewGraphXUnits = viewCanvasSpace.getWidth() / viewDataSpace.getWidth();
        double viewGraphYUnits = viewCanvasSpace.getHeight() / viewDataSpace.getHeight();
	double pathGraphXUnits = pathCanvasSpace.getWidth() / pathDataSpace.getWidth();
        double pathGraphYUnits = pathCanvasSpace.getHeight() / pathDataSpace.getHeight();
       
	if(debug) {
	    System.out.println("  Graph Units:");
	    System.out.println("         View: " +
			       "[" + viewCanvasSpace.getWidth() + ", " + viewCanvasSpace.getHeight() + "] / " +
			       "(" + viewDataSpace.getWidth()  + ", " + viewDataSpace.getHeight()  + ") -> " +
			       "{" + viewGraphXUnits           + ", " + viewGraphYUnits            + "}");
	    System.out.println("         Path: " +
			       "[" + pathCanvasSpace.getWidth() + ", " + pathCanvasSpace.getHeight() + "] / " +
			       "(" + pathDataSpace.getWidth()  + ", " + pathDataSpace.getHeight()  + ") -> " +
			       "{" + pathGraphXUnits           + ", " + pathGraphYUnits            + "}");
	}

        AffineTransform pathToGraph = new AffineTransform();

        /* The first transformation of the path to its proper place in
         *  the graph. This transformation needs to place the path so that
         *  the upper left hand corner is in the correct place in the view.
         *  (Because scale transformations move relative to that point.)
         *
         */
        double xOffset = pathDataSpace.getX() - viewDataSpace.getX();
        double yOffset = ((viewDataSpace.getY() + viewDataSpace.getHeight()) -
                          (pathDataSpace.getY() + pathDataSpace.getHeight()));
        if(debug) {
	    System.out.println("  Translating: " +
			       "[" + viewDataSpace.getX() + ", " + viewDataSpace.getY() + "]" +
			       "["  + (viewDataSpace.getX() + viewDataSpace.getWidth()) +
			       "{" + viewGraphXUnits           + ", " + viewGraphYUnits            + "}");
	    System.out.println("         Path: " +
			       "[" + pathCanvasSpace.getWidth() + ", " + pathCanvasSpace.getHeight() + "] / " +
			       "(" + pathDataSpace.getWidth()  + ", " + pathDataSpace.getHeight()  + ") -> " +
			       "{" + pathGraphXUnits           + ", " + pathGraphYUnits            + "}");
	}

        pathToGraph.translate(viewCanvasSpace.getX() + xOffset * viewGraphXUnits,
                              viewCanvasSpace.getY() + yOffset * viewGraphYUnits);

	/* The scaling transform is just the relationship of the units for
         *  the viewport to the same quantity for the path.
         */
        pathToGraph.scale(viewGraphXUnits / pathGraphXUnits, viewGraphYUnits / pathGraphYUnits);
        
	if(debug) {
	    System.out.println("  Scaling: " +
			       "[" + pathToGraph.getScaleX() + ", " + pathToGraph.getScaleY() + "]");
        }
        return path.createTransformedShape(pathToGraph);
    }

    public static GeneralPath createPath(OneDimensionalDataSet xAxis,
					 OneDimensionalDataSet yAxis) {
	return createPath(xAxis, yAxis, new Dimension(1000, 1000));
    }

    public static GeneralPath createPath(OneDimensionalDataSet xAxis,
					 OneDimensionalDataSet yAxis,
					 Dimension graphSize) {
        int xCount = xAxis.getRowCount();
        int yCount = yAxis.getRowCount();
        int stepCount = Math.min(xCount, yCount);
        double xMin = xAxis.getMin();
        double yMin = yAxis.getMin();
        double xMax = xAxis.getMax();
        double yMax = yAxis.getMax();
        double xExtent = xMax - xMin;
        double yExtent = yMax - yMin;

        GeneralPath path = new GeneralPath();

        for(int i = 0; i < stepCount; i++) {
            double percent = (double)i / stepCount;
            float xPos = (float)(graphSize.width * (xAxis.getValue((int)(percent * xCount)) - xMin) / xExtent);
            float yPos = (float)(graphSize.height * (yMax - yAxis.getValue((int)(percent * yCount))) / yExtent);

            if(i == 0) {
                path.moveTo(xPos, yPos);
            } else {
                path.lineTo(xPos, yPos);
            }
        }
        return path;
    }
}
