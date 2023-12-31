package org.himinbi.tokenizer.basic;

import java.io.PushbackReader;
import java.io.IOException;

public class QuoteDeliniatedToken extends TokenBase {
    int START = 0;
    int END = 1;
    String[] quote = new String[2];
    char escapeChar = '\0';
    boolean keepQuotes;

    public QuoteDeliniatedToken(String openQuote,
                                String closeQuote) {
        this(openQuote, closeQuote, '\\', false);
    }

    public QuoteDeliniatedToken(String openQuote,
                                String closeQuote,
                                char escapeChar,
                                boolean keepQuotes) {
        quote[START] = openQuote;
        quote[END] = closeQuote;
        this.escapeChar = escapeChar;
        this.keepQuotes = keepQuotes;
    }

    public String getToken(PushbackReader reader)
        throws IOException {
        String token = null;
        StringBuffer buffer = new StringBuffer();
        try {
            int matchCount = 0;
            char currentChar;
            boolean finished = false;
            while(reader.ready() && !finished) {
                 currentChar = (char)reader.read();
                 buffer.append(currentChar);
                 finished = !(currentChar == quote[START].charAt(matchCount)
                              && ++matchCount < quote[START].length());
             }

            if(matchCount < quote[START].length()) {
                // It did not match; rollback
                while(buffer.length() > 0) {
                    reader.unread(buffer.charAt(buffer.length() - 1));
                    buffer.setLength(buffer.length() - 1);
                }
            } else {
                if(!keepQuotes) {
                    buffer.setLength(0);
                }
                matchCount = 0;
                finished = false;
                while(reader.ready() && !finished) {
                    currentChar = (char)reader.read();
                    if(currentChar == quote[END].charAt(matchCount)) {
                        ++matchCount;
                    } else {
                        matchCount = 0;
                    }
                    if(currentChar == escapeChar && reader.ready()) {
                        currentChar = (char)reader.read();
                        switch(currentChar) {
                        case 'n':
                            currentChar = '\n';
                            break;
                        case 't':
                            currentChar = '\t';
                            break;
                        default:
                        }
                    }
                    buffer.append(currentChar);
                    finished = matchCount == quote[END].length();
                }
                if(!keepQuotes) {
                    buffer.setLength(buffer.length() - quote[END].length());
                }
                token = buffer.toString();
            }
        } catch(IOException ioe) {
            System.out.println("Quoted caught: " + ioe);
            throw new IOException("Improperly terminated quote: " +
                                  "\"" + buffer.toString() + "\"");
        }
        return token;
    }
}
