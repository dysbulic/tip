package org.himinbi.tokenizer;

import java.io.Reader;
import java.io.IOException;

/**
 * Tokenizes an input stream
 */
public interface StreamTokenizer extends TokenStream {
    public void setInputReader(Reader inputStream) throws IOException;
    public void addTokenType(String name, String description);
}
