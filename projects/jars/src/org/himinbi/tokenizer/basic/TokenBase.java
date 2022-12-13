package org.himinbi.tokenizer.basic;

public abstract class TokenBase implements TokenType {
    String className = TT_UNKNOWN;

    public String getClassName() {
        return className;
    }

    public void setClassName(String className) {
        this.className = className;
    }
}
