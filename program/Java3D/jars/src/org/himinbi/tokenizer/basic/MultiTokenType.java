package org.himinbi.tokenizer.basic;

import java.io.PushbackReader;
import java.io.IOException;

public class MultiTokenType extends TokenBase {
    TokenType[] tokens;

    public MultiTokenType(TokenType[] tokens) {
        this.tokens = tokens;
    }

    public String getToken(PushbackReader reader)
        throws IOException {
        StringBuffer buffer = new StringBuffer();
        String token = null;
        do {
            token = null;
            for(int i = 0;
                token == null && i < tokens.length;
                i++) {
                token = tokens[i].getToken(reader);
            }
            if(token != null) {
                buffer.append(token);
            }
        } while(token != null);
        if(buffer.length() > 0) {
            token = buffer.toString();
        }
        return token;
    }
}
            
