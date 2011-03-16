package org.himinbi.tokenizer;

import java.util.List;
import java.util.ArrayList;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

public class CombinationFilter extends TokenizerBase
    implements TokenFilter
{
    TokenStream incomingStream;
    List incomingElements;
    List incomingTypes = new ArrayList();
    String outputType;
    Token holdingToken;

    static Logger log = Logger.getLogger(CombinationFilter.class);

    public void setAdditionalIncomingType(String incomingType)
    {
        incomingTypes.add(incomingType);
    }

    public void setOutputType(String outputType)
    {
        this.outputType = outputType;
    }

    public void setIncomingStream(TokenStream incomingStream)
    {
        this.incomingStream = incomingStream;
    }

    protected void queueToken()
    {
        // Could be holding a token from a previous check
        if(holdingToken != null)
        {
            log.debug("Using holding token: " + holdingToken);
            setNextToken(holdingToken);
            holdingToken = null;
        }
        else
        {
            /* A combination is not done unless there are at least
             *  two tokens that are combined; otherwise it is just
             *  a replacement and should be handed by a replacement
             *  filter
             */
            Token testToken = incomingStream.nextToken();
            holdingToken = incomingStream.nextToken();

            log.debug("Testing combination of: " +
                      testToken + " and " + holdingToken);
            
            if(testToken != null && holdingToken != null &&
               incomingTypes.contains(testToken.getType()) &&
               incomingTypes.contains(holdingToken.getType()))
            {
                StringBuffer outputValue = new StringBuffer();
                outputValue.append(testToken.getValue());
                do
                {
                    outputValue.append(holdingToken.getValue());
                    holdingToken = incomingStream.nextToken();
                    log.debug("Testing: " + holdingToken);
                }
                while(holdingToken != null &&
                      incomingTypes.contains(holdingToken.getType()));
                
                log.debug("Combined to: " + outputValue);

                Token nextToken = new Token(outputType, outputValue.toString());
                setNextToken(nextToken);
            }
            else
            {
                setNextToken(testToken);
            }
        }
    }
}
