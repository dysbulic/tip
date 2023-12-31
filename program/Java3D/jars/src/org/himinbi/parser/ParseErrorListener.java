package org.himinbi.parser;

import java.util.EventListener;

public interface ParseErrorListener extends EventListener {
    public void parseError(ParseError error)
        throws ParseException;
}
