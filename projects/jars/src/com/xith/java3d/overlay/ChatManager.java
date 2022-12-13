package com.xith.java3d.overlay;

import java.awt.*;
import java.awt.event.*;
import java.util.*;
import com.sun.j3d.utils.applet.MainFrame;

/**
 * Description:  Simple class to manage the keystrokes coming into an
 *                application and map them onto an array of
 *                LabelOverlays
 * Copyright:    Copyright (c) 2000,2001
 *
 * @author Will Holcomb
 */
public class ChatManager implements KeyListener, ScrollEventListener {
    OverlayScroller scroller;
    int numLines;
    StringBuffer buffer = new StringBuffer();
    Stack history = new Stack();
    boolean ignorable = true;
    boolean skipKey = false;

    public ChatManager(OverlayScroller scroller) {
	this.scroller = scroller;
	scroller.addScrollEventListener(this);
    }

    public void keyPressed(KeyEvent e) {
	ignorable = true;
	
	switch (e.getKeyCode()) {
	case KeyEvent.VK_ENTER:
	    scrollUp();
	    break;
	case KeyEvent.VK_BACK_SPACE:
	    if (buffer.length() > 0) {
		buffer.setLength(buffer.length() - 1);
		ignorable = false;
	    } else {
		scrollDown();
	    }
	    break;
	case KeyEvent.VK_NUMPAD1:
	    scroller.setRelativePosition(Overlay.PLACE_LEFT, Overlay.PLACE_BOTTOM);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD2:
	    scroller.setRelativePosition(Overlay.PLACE_CENTER, Overlay.PLACE_BOTTOM);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD3:
	    scroller.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_BOTTOM);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD4:
	    scroller.setRelativePosition(Overlay.PLACE_LEFT, Overlay.PLACE_CENTER);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD5:
	    scroller.setRelativePosition(Overlay.PLACE_CENTER, Overlay.PLACE_CENTER);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD6:
	    scroller.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_CENTER);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD7:
	    scroller.setRelativePosition(Overlay.PLACE_LEFT, Overlay.PLACE_TOP);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD8:
	    scroller.setRelativePosition(Overlay.PLACE_CENTER, Overlay.PLACE_TOP);
	    skipKey = true;
	    break;
	case KeyEvent.VK_NUMPAD9:
	    scroller.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_TOP);
	    skipKey = true;
	    break;
	default:
	    ignorable = true;
	}
	
	if(!ignorable) {
	    ((LabelOverlay)scroller.getLine(0)).setText(buffer.toString());
	}
    }
    
    public void keyReleased(KeyEvent e) {
    }

    public void keyTyped(KeyEvent e) {
	if (!skipKey) {
	    char c = e.getKeyChar();
	    if (Character.isLetterOrDigit(c) || c == ' ' || isPunctuation(c)) {
		buffer.append(c);
		((LabelOverlay)scroller.getLine(0)).setText(buffer.toString());
	    }
	} else {
	    skipKey = false;
	}
    }

    public void itemScrolled(ScrollEvent e) {
	switch (e.getScrollType()) {
	case ScrollEvent.SCROLLED_UP:
	    ((LabelOverlay)e.getScrolledItem()).setText("");
	    break;
	case ScrollEvent.SCROLLED_DOWN:
	    int offset = history.size() - scroller.getNumLines();
	    if (offset >= 0) {
		((LabelOverlay)e.getScrolledItem())
		    .setText(history.get(offset).toString());
	    }
	    break;
	}
    }

    public void scrollUp() {
	history.push(buffer);
	buffer = new StringBuffer();
	scroller.scroll(0, 1);
    }

    public void scrollDown() {
	if (history.size() > 0) {
	    scroller.scroll(scroller.getNumLines() - 1, -1);
	    buffer = (StringBuffer)history.pop();
	}
    }

    public void pushLine(String line) {
	scrollUp();
	buffer = new StringBuffer(line);
    }

    final static char[] punctuation = {'?', '.', ',', '\'', '\"', ';', '(', ')',
				       '/', '\\', '!', '-', '=', '+', '_',':',
				       '<', '>', '$', '%', '*', '&', '^', '|', 
				       '~', '`', '@', '#', '{', '}', '[', ']'};

    public static boolean isPunctuation(char c) {
	boolean found = false;
	for (int i = punctuation.length - 1; i >= 0 && !found; i--) {
	    found = c == punctuation[i];
	}
	return found;
    }
}
