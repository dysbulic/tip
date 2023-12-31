package org.himinbi.tokenizer;

import java.util.Enumeration;
import java.io.Reader;
import java.io.IOException;

import org.apache.log4j.Logger;

public class DiscardFilter extends TokenizerBase
    implements TokenFilter
{
    TokenStream incomingStream;
    String discardType;

    static Logger log = Logger.getLogger(DiscardFilter.class);

    public DiscardFilter()
    {
    }

    public DiscardFilter(TokenStream incomingStream,
                         String type)
    {
        setIncomingStream(incomingStream);
        setDiscardType(type);
    }

    public void setDiscardType(String type)
    {
        discardType = type;
    }

    public void setIncomingStream(TokenStream incomingStream)
    {
        this.incomingStream = incomingStream;
    }

    public void queueToken() {
        Token nextToken = null;
        do
        {
            nextToken = incomingStream.nextToken();
            log.debug("Checking: " + nextToken);
        }
        while(nextToken != null &&
              discardType.equals(nextToken.getType()));
        setNextToken(nextToken);
    }
}
