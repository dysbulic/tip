package org.himinbi.tokenizer;

public interface TokenFilter extends TokenStream
{
    public void setIncomingStream(TokenStream incoming);
}
