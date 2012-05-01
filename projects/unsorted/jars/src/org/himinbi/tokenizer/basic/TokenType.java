package org.himinbi.tokenizer.basic;

import java.io.PushbackReader;
import java.io.IOException;

public interface TokenType {
    final static String TT_UNKNOWN = "unknown";

    /**
     * Pulls a token off the stream if one can be formed
     * Returns null if no token could be formed
     * Throws IOException if the end of the stream is reached
     *  without forming a valid token
     */
    public String getToken(PushbackReader reader) throws IOException;
    public String getClassName();
    public void setClassName(String className);
}
