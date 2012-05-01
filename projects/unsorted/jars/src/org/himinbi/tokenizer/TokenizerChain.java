package org.himinbi.tokenizer;

import java.util.List;
import java.util.Iterator;
import java.io.Reader;
import java.io.IOException;

public class TokenizerChain implements StreamTokenizer
{
    protected StreamTokenizer tokenizer;
    protected List filters;
    protected TokenStream chainEnd;

    /**
     * Takes a base tokenizer and a list of token filters and
     *  strings them together.
     */
    public TokenizerChain(StreamTokenizer tokenizer,
                          List filters)
    {
        this.tokenizer = tokenizer;
        this.filters = filters;

        Iterator streamFilters = filters.iterator();
        TokenStream currentStream = tokenizer;
        while(streamFilters.hasNext())
        {
            TokenFilter filter = (TokenFilter)streamFilters.next();
            filter.setIncomingStream(currentStream);
            currentStream = filter;
        }
        chainEnd = currentStream;
    }

    public Token nextToken()
    {
        return chainEnd.nextToken();
    }

    public String nextTokenType()
    {
        return chainEnd.nextTokenType();
    }

    public String nextTokenValue()
    {
        return chainEnd.nextTokenValue();
    }

    public Token lookAhead()
    {
        return chainEnd.lookAhead();
    }

    public boolean hasNext()
    {
        return chainEnd.hasNext();
    }

    public void setInputReader(Reader input) throws IOException
    {
        tokenizer.setInputReader(input);
    }
    
    public void addTokenType(String name, String description)
    {
        throw new UnsupportedOperationException
            ("This is a wrapper class; not intended for standalone usage");
    }

    public void initialize()
    {
    }

    public String toString()
    {
        StringBuffer buffer = new StringBuffer();
        buffer.append(tokenizer.getClass().getName());
        Iterator filters = this.filters.iterator();
        while(filters.hasNext())
        {
            buffer.append(", ");
            buffer.append(filters.next().getClass().getName());
        }
        return buffer.toString();
    }
}
