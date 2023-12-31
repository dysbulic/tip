package org.himinbi.parser;

public class ParseError extends Error {
    public final static int FATAL = 1;
    public final static int NONFATAL = 2;

    int type = NONFATAL;

    public ParseError(String message) {
        this(message, FATAL);
    }

    public ParseError(String message, int type) {
        super(message);
        this.type = type;
    }

    public int getType() {
        return type;
    }
}
    
