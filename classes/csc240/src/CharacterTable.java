public class CharacterTable {
    protected int height;
    protected int width;
    protected int [][] counts;
    protected char [] chars;
    protected String heldString;
    protected boolean calculated;
    
    public CharacterTable(String s) {
        heldString = s;
        width = s.length() + 1;
        
        for(int i = 0; i < heldString.length(); i++)
            add(heldString.charAt(i));

        calculated = false;
    }

    public int length() {
        return heldString.length();
    }

    protected void calculate() {
        if(calculated)
            System.out.println("Calling calculated when calculated is " + calculated + ".");

        int i = 0;
        int j = 0;
        
        for(j = 0; j < height; j++)
            counts[j][width - 1] = 0;
        
        for(i = width - 2; i >= 0; i--)
            for(j = 0; j < height; j++)
                if(heldString.charAt(i) == chars[j])
                    counts[j][i] = counts[j][i + 1] + 1;
                else
                    counts[j][i] = counts[j][i + 1];
        
        calculated = true;
    }
    
    protected void add(char c) {
        if(!isInTable(c)) {
            char [] tempChar = new char [height + 1];
            int [][] tempInt = new int [height + 1][width];
            int i = 0;
            
            for(i = 0; i < height; i++)
                tempChar[i] = chars[i];
            
            chars = tempChar;
            counts = tempInt;
            
            chars[height] = c;
            
            height++;
        }
        calculated = false;
    }

    public boolean isInTable(char c) {
        boolean found = false;
        
        for(int i = 0; i < height && !found; i++)
            if(chars[i] == c)
                found = true;
        
        return found;
    }
    
    public CharacterCountTable countsFor(int col) {
        CharacterCountTable count = new CharacterCountTable();
        
        if(!calculated)
            calculate();
        
        for(int i = 0; i < height; i++)
            count.add(new CharacterCount(chars[i], counts[i][col]));
        
        return count;
    }

    public void dump() {
        if(!calculated)
            calculate();
        
        int i = 0;
        int j = 0;
        
        System.out.print("\t");
        
        for(i = 0; i < width - 1; i++)
            System.out.print("\t" + heldString.charAt(i));
        
        System.out.println("");
        
        for(j = 0; j < height; j++) {
            System.out.print("\t" + chars[j]);
            
            for(i = 0; i < width; i++)
                System.out.print("\t" + counts[j][i]);
            
            System.out.println("");
        }
    }

    public String toString() {
        return heldString;
    }
    
    public char charAt(int index) {
        return heldString.charAt(index);
    }
}
