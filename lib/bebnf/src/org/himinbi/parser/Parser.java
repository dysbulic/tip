package org.himinbi.parser;

import java.io.IOException;

import org.himinbi.tokenizer.TokenStream;

public interface Parser {
    public void parse(TokenStream tokens)
        throws ParseException, IOException;
    public void addParseEventListener(ParseEventListener listener);
    public void removeParseEventListener(ParseEventListener listener);
    public void addParseErrorListener(ParseErrorListener listener);
    public void removeParseErrorListener(ParseErrorListener listener);

    public void setGrammar(Grammar grammar);
}

