package org.himinbi.parser;

import org.apache.log4j.Logger;

import javax.swing.event.EventListenerList;

public abstract class ParserBase implements Parser
{
    EventListenerList listeners = new EventListenerList();

    static Logger log = Logger.getLogger(ParserBase.class);
    
    public void addParseErrorListener(ParseErrorListener listener)
    {
        listeners.add(ParseErrorListener.class, listener);
    }

    public void removeParseErrorListener(ParseErrorListener listener)
    {
        listeners.remove(ParseErrorListener.class, listener);
    }

    public void fireParseError(ParseError error)
        throws ParseException
    {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0 && error != null; i -= 2)
        {
            if(listeners[i] == ParseErrorListener.class)
            {
                ((ParseErrorListener)listeners[i + 1]).parseError(error);
            }
        }
    }

    public void addParseEventListener(ParseEventListener listener)
    {
        listeners.add(ParseEventListener.class, listener);
    }

    public void removeParseEventListener(ParseEventListener listener)
    {
        listeners.remove(ParseEventListener.class, listener);
    }

    public void fireParseStarted() throws ParseException
    {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0; i -= 2)
        {
            if(listeners[i] == ParseEventListener.class)
            {
                ((ParseEventListener)listeners[i + 1]).parseStarted();
            }
        }
    }

    public void fireParseFinished() throws ParseException
    {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0; i -= 2)
        {
            if(listeners[i] == ParseEventListener.class)
            {
                ((ParseEventListener)listeners[i + 1]).parseFinished();
            }
        }
    }

    public void fireRuleStarted(String nonterminal)
         throws ParseException
    {
        log.debug("Firing rule started: " + nonterminal);

        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0; i -= 2)
        {
            if(listeners[i] == ParseEventListener.class)
            {
                ((ParseEventListener)listeners[i + 1])
                    .ruleStarted(nonterminal);
            }
        }
    }

    public void fireRuleFinished(String nonterminal)
        throws ParseException
    {
        log.debug("Firing rule finished: " + nonterminal);

        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0; i -= 2)
        {
            if(listeners[i] == ParseEventListener.class)
            {
                ((ParseEventListener)listeners[i + 1])
                    .ruleFinished(nonterminal);
            }
        }
    }

    public void fireTerminal(String terminalClass,
                             String terminal)
         throws ParseException
    {
        Object[] listeners = this.listeners.getListenerList();
        for(int i = listeners.length - 2; i >= 0; i -= 2)
        {
            if(listeners[i] == ParseEventListener.class)
            {
                ((ParseEventListener)listeners[i + 1])
                    .terminal(terminalClass, terminal);
            }
        }
    }
}
