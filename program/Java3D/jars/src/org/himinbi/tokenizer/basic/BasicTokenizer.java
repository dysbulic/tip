package org.himinbi.tokenizer.basic;

import java.io.Reader;
import java.io.PushbackReader;
import java.io.LineNumberReader;
import java.io.IOException;

import java.util.Hashtable;
import java.util.Vector;
import java.util.Enumeration;

import org.himinbi.tokenizer.Token;
import org.himinbi.tokenizer.TokenizerBase;
import org.himinbi.tokenizer.StreamTokenizer;

/**
 * Breaks an input stream up into tokens based on delimiting characters
 */
public class BasicTokenizer extends TokenizerBase implements StreamTokenizer
{
    public final static String TT_STRING = "string";
    public final static String TT_UNKNOWN = "unknown";
    public final static String TT_EOF= "eof";
    
    /**
     * Maximum number of characters that can be pushed back. This is
     * the maximum allowed length for the beginning of a quote.
     */
    public int BUFFER_SIZE = 5;

    Vector tokenTypes = new Vector();
    LineNumberReader lineCounter;
    PushbackReader reader;
    
    /**
     * setInputReader(Reader reader) must be called before the program can
     * be used
     */
    public BasicTokenizer()
    {
    }

    /**
     * Takes a prolog program from the reader
     */
    public BasicTokenizer(Reader reader) throws IOException
    {
	setInputReader(reader);
    }

    /**
     * Sets the current input stream
     */
    public void setInputReader(Reader reader) throws IOException
    {
        lineCounter = new LineNumberReader(reader);
	this.reader = new PushbackReader(lineCounter, BUFFER_SIZE);
	queueToken();
    }

    public void addTokenType(TokenType tokenType)
    {
        tokenTypes.add(tokenType);
    }

    public void addTokenType(String name, String description)
    {
        throw new UnsupportedOperationException("Not implemented");
    }

    /**
     * Returns the current line number
     */
    public int getLineCount()
    {
	return lineCounter.getLineNumber();
    }

    protected void queueToken()
    {
        Token nextToken = null;
	if(reader == null)
        {
            throw new IllegalArgumentException("Stream has not been set");
	}
        try {
            String nextString = null;
            while(nextString == null && reader.ready()) {
                /**
                 * Start wih the first added token types and work back
                 */
                for(int i = 0;
                    nextString == null && i < tokenTypes.size();
                    i++) {
                    TokenType nextTokenType = (TokenType)tokenTypes.get(i);
                    nextString = nextTokenType.getToken(reader);
                    if(nextString != null) {
                        nextToken = new Token(nextTokenType.getClassName(),
                                              nextString);
                    }
                }
                
                if(nextToken == null) {
                    throw new IllegalArgumentException
                        ("No class found with '" + (char)reader.read() + "' " +
                         "as a valid start");
                }
                setNextToken(nextToken);
            }
        } catch(IOException ioe) {
            System.out.println("Basic IOE: " + ioe);
            try {
                System.out.print("Remaining stream: ");
                while(reader.ready()) {
                    System.out.print((char)reader.read());
                }
                System.out.println();
            } catch(IOException innerIOE) {
            }
        }
    }
}
