package org.himinbi.ui;

import java.util.*;
import javax.swing.*;

public class PaintBoxFrame extends JFrame {
    PaintBoxPanel paintBox = new PaintBoxPanel();

    {
	setDefaultCloseOperation(WindowConstants.HIDE_ON_CLOSE);
	getContentPane().add(paintBox);	
	pack();
    }

    public void addPaints(Hashtable paints) {
	paintBox.addPaints(paints);
    }
}
