package org.himinbi.tokenizer;

import java.util.regex.Pattern;
import java.util.regex.Matcher;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

public class ReplacementFilter extends TokenizerBase
    implements TokenFilter
{
    Pattern incomingType;
    Pattern match;
    String replacement;
    String outputType;

    TokenStream incomingStream;

    static Logger log = Logger.getLogger(ReplacementFilter.class);

    public void setIncomingType(String incomingType)
    {
        setIncomingType(Pattern.compile(incomingType));
    }

    public void setIncomingType(Pattern incomingType)
    {
        this.incomingType = incomingType;
    }

    public void setMatch(String match)
    {
        setMatch(Pattern.compile(match));
    }

    public void setMatch(Pattern match)
    {
        this.match = match;
    }

    public void setReplacement(String replacement)
    {
        this.replacement = replacement;
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
        Token nextToken = incomingStream.nextToken();
        if(nextToken != null &&
           incomingType.matcher(nextToken.getType()).matches())
        {
            Matcher matcher = (match != null
                               ? match.matcher(nextToken.getValue())
                               : null);
                
            if(matcher == null || matcher.find())
            {
                String value = null;
                if(matcher != null && replacement != null)
                {
                    value = matcher.replaceAll(replacement);
                }
                else if(replacement != null)
                {
                    value = replacement;
                }

                log.debug("Replaced: \"" + nextToken.getValue() + "\"" +
                          " with \"" + value + "\"");
                if(value != null)
                {
                    nextToken.setValue(value);
                }

                if(outputType != null)
                {
                    nextToken.setType(outputType);
                }
            }
        }
        setNextToken(nextToken);
    }
}
