package com.xith.java3d.overlay;

public class ScrollEvent extends java.util.EventObject {
    public final static int SCROLLED_UP = 0;
    public final static int SCROLLED_DOWN = 1;

    Object scrolledItem;
    int scrollType;

    public ScrollEvent(Object source, Object scrolledItem, int scrollType) {
	super(source);
	this.scrolledItem = scrolledItem;
	this.scrollType = scrollType;
    }

    public Object getScrolledItem() {
	return scrolledItem;
    }

    public int getScrollType() {
	return scrollType;
    }
}
