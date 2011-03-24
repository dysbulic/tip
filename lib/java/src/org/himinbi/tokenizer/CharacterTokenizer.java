package org.himinbi.tokenizer;

import java.util.Enumeration;
import java.io.Reader;
import java.io.LineNumberReader;
import java.io.IOException;

import org.apache.log4j.Logger;

public class CharacterTokenizer extends TokenizerBase
    implements StreamTokenizer
{
    LineNumberReader reader;
    
    static Logger log = Logger.getLogger(CharacterTokenizer.class);

    /**
     * Returns the number of lines processed
     */
    public int getLineCount()
    {
        return reader.getLineNumber();
    }

    /**
     * Sets the reader being tokenized
     */
    public void setInputReader(Reader inputStream) throws IOException
    {
        reader = new LineNumberReader(inputStream);
        queueToken();
    }

    public void addTokenType(String name, String description)
    {
        throw new UnsupportedOperationException();
    }

    protected void queueToken()
    {
        try
        {
            if(reader.ready())
            {
                String nextChar = String.valueOf((char)reader.read());
                nextToken = new Token(nextChar, nextChar);
            }
            else
            {
                nextToken = null;
            }
        }
        catch(IOException ioe)
        {
            log.error(ioe);
        }
    }
}
