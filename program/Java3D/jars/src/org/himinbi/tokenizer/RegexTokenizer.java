package org.himinbi.tokenizer;

import java.util.Map;
import java.util.List;
import java.util.ArrayList;
import java.util.Hashtable;
import java.util.Iterator;
import java.io.Reader;
import java.io.StringWriter;
import java.io.IOException;
import java.util.regex.Pattern;
import java.util.regex.Matcher;
import java.util.regex.PatternSyntaxException;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

public class RegexTokenizer extends TokenizerBase
    implements StreamTokenizer
{
    /**
     * Ordered list of matches for token elements. Elements are Patterns.
     */
    List tokenMatches = new ArrayList();

    /**
     * Mapping between token Patterns and token names
     */
    Map tokenNames = new Hashtable();

    /**
     * Text to be tokenized
     */
    String text;

    static Logger log = Logger.getLogger(RegexTokenizer.class);

    public int getLineCount()
    {
        throw new UnsupportedOperationException("Not currently supported");
    }

    public void setInputReader(Reader inputStream) throws IOException
    {
        // Number of characters to move at a time
        int BUFFER_SIZE = 50;

        /* Currently the regex engine operates on the entire
         *  file at once. It is possible to alter this to a
         *  linewise method that would have some performance
         *  benefits.
         */
        StringWriter writer = new StringWriter();
        char[] buffer = new char[BUFFER_SIZE];
        for(int length = 0;
            (length = inputStream.read(buffer, 0, buffer.length)) >= 0;)
        {
            writer.write(buffer, 0, length);
        }
        text = writer.toString();

        log.debug("Pulled all text into: " + text);
    }

    protected void queueToken()
    {
        Iterator patterns = tokenMatches.iterator();
        Token nextToken = null;
        while(nextToken == null && patterns.hasNext())
        {
            Pattern pattern = (Pattern)patterns.next();
            Matcher matcher = pattern.matcher(text);
                      
            if(matcher.lookingAt())
            {
                nextToken = new Token();
                String value = matcher.group(matcher.groupCount());
                log.debug("Going from: " + matcher.start() + " to " +  matcher.end() +
                          " in " + text.length() + " for \"" + value + "\"" +
                          " (count = " + matcher.groupCount() + ")");
                nextToken.setValue(value);
                text = text.substring(matcher.end());
                nextToken.setType((String)tokenNames.get(pattern));
            }
        }
        if(nextToken == null)
        {
            log.debug("No tokens matched");
            if(text.length() != 0)
            {
                throw new IllegalArgumentException("Parse did not finish");
            }
        }
        setNextToken(nextToken);
    }

    /**
     * Adds a new token. The description is a regular expression to be
     * matched against the source text
     */
    public void addTokenType(String name, String description)
    {
        try
        {
            Pattern pattern = Pattern.compile(description);
            tokenMatches.add(pattern);
            tokenNames.put(pattern, name);
        }
        catch(PatternSyntaxException pse)
        {
            IllegalArgumentException iae= new IllegalArgumentException();
            iae.initCause(pse);
            throw iae;
        }
    }
}
