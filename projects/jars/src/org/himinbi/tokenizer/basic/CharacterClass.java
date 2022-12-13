package org.himinbi.tokenizer.basic;

public interface CharacterClass {
    /**
     * Returns true if the specified character belongs to the
     * class
     */
    public boolean contains(char character);
}
