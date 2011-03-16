package org.himinbi.parser;

public class ParseException extends Exception
{
    public ParseException()
    {
    }

    public ParseException(String message)
    {
        super(message);
    }

    public ParseException(Throwable cause)
    {
        super(cause);
        initCause(cause);
    }
}
