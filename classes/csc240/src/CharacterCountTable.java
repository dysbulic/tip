public class CharacterCountTable {
    protected int size;
    protected CharacterCount [] characters;
    public boolean replaceDuplicates = true;

    public CharacterCountTable() {
        size = 0;
    }

    protected void add(CharacterCount cc) {
        int i = 0;
        
        if(!isInTable(cc)) {
            CharacterCount [] tempCC = new CharacterCount [size + 1];
            
            for(i = 0; i < size; i++)
                tempCC[i] = characters[i];
            
            characters = tempCC;
            
            characters[size] = cc;
            
            size++;
        } else {
            boolean found = false;

            for(i = 0; i < size && !found; i++) {
                if(characters[i].character == cc.character) {
                    found = true;
                    if(replaceDuplicates) {
                        characters[i].count = cc.count;
                    } else {
                        characters[i].count += cc.count;
                    }
		}
            }
        }
    }

    public boolean isInTable(CharacterCount c) {
        boolean found = false;
        
        for(int i = 0; i < size && !found; i++)
            if(characters[i].character == c.character)
                found = true;
        
        return found;
    }
    
    public boolean isInTable(char c) {
        boolean found = false;
        
        for(int i = 0; i < size && !found; i++)
            if(characters[i].character == c)
                found = true;
        
        return found;
    }
    
    public char charAt(int index) {
        return characters[index].character;
    }
    
    public int countFor(int index) {
        return characters[index].count;
    }
    
    public int countFor(char c) {
        boolean found = false;
        int count = 0;
        
        for(int i = 0; i < size && !found; i++)
            if(characters[i].character == c) {
                found = true;
                count = characters[i].count;
            }
        
        return count;
    }
    
    public void dump() {
        System.out.println("\tchar\tcount");
        
        for(int i = 0; i < size; i++)
            System.out.println("\t" + characters[i].character
                               + "\t" + characters[i].count);
    }
    
    public int length() {
        return size;
    }
}
