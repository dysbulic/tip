package org.himinbi.tokenizer;

public class Token
{
    final static String UNIDENTIFIED_TYPE = null;

    String value;
    String type;
    boolean isAnonymous = false;
   
    public Token()
    {
        this(UNIDENTIFIED_TYPE, null);
    }

    public Token(String value)
    {
        this(UNIDENTIFIED_TYPE, value);
    }
    
    public Token(String type, String value)
    {
        this.value = value;
        this.type = type;
    }

    public boolean isIdentified()
    {
        return type != UNIDENTIFIED_TYPE;
    }

    public String getValue()
    {
        return value;
    }

    public String getType()
    {
        return type;
    }

    public void setType(String type)
    {
        this.type = type;
    }

    public void setValue(String value)
    {
        this.value = value;
    }

    public String toString()
    {
        return "(" + type + ") \"" + value + "\"";
    }
}
