package org.himinbi.tokenizer.basic;

public class ListCharacterClass implements CharacterClass {
    char[] characters;

    public ListCharacterClass(char character) {
        this(new char[] { character });
    }

    public ListCharacterClass(char[] characters) {
        this.characters = characters;
    }

    public boolean contains(char character) {
        boolean found = false;
        for(int i = 0;
            !found && i < characters.length;
            i++) {
            found = character == characters[i];
        }
        return found;
    }
}
