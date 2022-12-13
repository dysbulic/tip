package org.himinbi.tokenizer;

import java.util.Enumeration;
import java.io.Reader;
import java.io.IOException;

/**
 * Represents a source of tokens to go into a parse.
 */
public interface TokenStream {
    /**
     * Returns true until there are no more tokens
     */
    public boolean hasNext();

    /**
     * Destructively returns the next token
     */
    public Token nextToken();

    /**
     * Returns the type of the next token
     */
    public String nextTokenType();

    /**
     * Returns the type of the next token
     */
    public String nextTokenValue();

    /**
     * Non-destructively returns the next token
     */
    public Token lookAhead();

    /**
     * Allows for setup after attributes have been set
     */
    public void initialize();
}
