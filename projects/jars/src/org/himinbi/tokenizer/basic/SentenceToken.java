package org.himinbi.tokenizer.basic;

import java.io.PushbackReader;
import java.io.IOException;

public class SentenceToken extends TokenBase {
    TokenType wordToken;
    TokenType whitespaceToken;
    String whitespace;

    public SentenceToken(TokenType wordToken,
                         TokenType whitespaceToken,
                         String whitespace) {
        this.wordToken = wordToken;
        this.whitespaceToken = whitespaceToken;
        this.whitespace = whitespace;
    }

    public String getToken(PushbackReader reader)
        throws IOException {
        StringBuffer buffer = new StringBuffer();
        String token = null;
        try {
            do {
                boolean whitespaceFound =
                    whitespaceToken.getToken(reader) != null;
                token = wordToken.getToken(reader);
                if(token != null) {
                    if(whitespaceFound && buffer.length() > 0) {
                        buffer.append(whitespace);
                    }
                    buffer.append(token);
                }
            } while(token != null);
        } catch(IOException ioe) {
            if(buffer.length() == 0) {
                throw ioe;
            }
        }
        if(buffer.length() > 0) {
            token = buffer.toString();
        }
        return token;
    }
}
