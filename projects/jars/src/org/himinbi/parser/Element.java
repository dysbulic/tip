package org.himinbi.parser;

/**
 * Represents an element in a grammar rule.
 */
public class Element {
    public final static String TERMINAL_TYPE = "terminal";
    public final static String NONTERMINAL_TYPE = "nonterminal";
    public final static Element EMPTY_TERMINAL = new Element(TERMINAL_TYPE,
                                                             null);

    String type;
    String value;
    boolean isAnonymous = false;

    public Element(String type, String value) {
        this(type, value, false);
    }

    public Element(String type, String value, boolean isAnonymous) {
        this.type = type;
        this.value = value;
        this.isAnonymous = isAnonymous;
    }

    public String getType() {
        return type;
    }

    public String getValue() {
        return value;
    }

    public String toString() {
        String string;
        if(type == TERMINAL_TYPE) {
            string = "'" + value + "'";
        } else if(type == NONTERMINAL_TYPE) {
            string = value;
        } else {
            string = "?" + value + "?";
        }
        return string;
    }

    public boolean isAnonymous() {
        return isAnonymous;
    }

    public boolean equals(Object object)
    {
        if(!(object instanceof Element))
        {
            return false;
        }

        Element element = (Element)object;

        if(type == null)
        {
            if(element.getType() != null)
            {
                return false;
            }
        }
        else if(!type.equals(element.getType()))
        {
            return false;
        }

        if(value == null)
        {
            if(element.getValue() != null)
            {
                return false;
            }
        }
        else if(!value.equals(element.getValue()))
        {
            return false;
        }
        
        return element.isAnonymous() == isAnonymous;
    }
    
    public int hashCode()
    {
        int hash = 0;
        if(type != null)
        {
            hash += type.hashCode();
        }
        if(value != null)
        {
            hash += value.hashCode();
        }
        return hash;
    }
}
