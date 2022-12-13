package org.himinbi.parser;

import java.util.EventListener;

public interface ParseEventListener extends EventListener {
    public void parseStarted() throws ParseException;
    public void parseFinished() throws ParseException;
    public void ruleStarted(String name) throws ParseException;
    public void ruleFinished(String name) throws ParseException;
    public void terminal(String terminalClass, String terminal)
        throws ParseException;
}
