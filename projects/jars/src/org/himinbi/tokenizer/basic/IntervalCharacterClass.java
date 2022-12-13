package org.himinbi.tokenizer.basic;

public class IntervalCharacterClass implements CharacterClass {
    char lowerBound;
    char upperBound;

    public IntervalCharacterClass(char lowerBound, char upperBound) {
        this.lowerBound = lowerBound;
        this.upperBound = upperBound;
    }

    public boolean contains(char character) {
        return character >= lowerBound && character <= upperBound;
    }
}
