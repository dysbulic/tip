package org.himinbi.tokenizer.basic;

public class NegationCharacterClass implements CharacterClass {
    CharacterClass characterClass;

    public NegationCharacterClass(CharacterClass characterClass) {
        this.characterClass = characterClass;
    }

    public boolean contains(char character) {
        return !characterClass.contains(character);
    }
}
