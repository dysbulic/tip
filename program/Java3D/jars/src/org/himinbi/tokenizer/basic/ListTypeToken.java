package org.himinbi.tokenizer.basic;

import java.io.PushbackReader;
import java.io.IOException;

public class ListTypeToken extends TokenBase {
    CharacterClass[] characterClasses;
    boolean keepToken;
    int maxLength = Integer.MAX_VALUE;

    public ListTypeToken(CharacterClass characterClass) {
        this(new CharacterClass[] { characterClass });
    }

    public ListTypeToken(CharacterClass[] characterClasses) {
        this.characterClasses = characterClasses;
    }

    public void setMaxLength(int maxLength) {
        this.maxLength = maxLength;
    }
    
    public String getToken(PushbackReader reader) 
        throws IOException {
        String token = null;
        StringBuffer buffer = new StringBuffer();
        try {
            boolean finished = false;
            char currentChar = '\0';
            while(reader.ready() && !finished && buffer.length() < maxLength) {
                currentChar = (char)reader.read();
                boolean found = false;
                for(int i = 0;
                    !found && i < characterClasses.length;
                    i++) {
                    found = characterClasses[i].contains(currentChar);
                }
                if(found) {
                    buffer.append(currentChar);
                } else {
                    finished = true;
                    // Unread the character that broke the loop
                    reader.unread(currentChar);
                }
            }
        } catch(IOException ioe) {
            if(buffer.length() == 0) {
                System.out.println("List throwing: " + ioe);
                throw ioe;
            }
        }
        if(buffer.length() > 0) {
            // 0 length tokens are not allowed
            token = buffer.toString();
        }
        return token;
    }
}
