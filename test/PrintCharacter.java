import java.io.PrintStream;

public class PrintCharacter {
    public static void main(String[] args) {
        PrintStream out = System.out;

        if(args.length == 0) {
            int max = 255;

            int[] topLine = new int[] { 201, 205, 209, 205, 203, 205, 209, 205, 187 };
            printCharacters(topLine, out);

            int[] separatorLine =
                new int[] { 199, 196, 197, 196, 215, 196, 197, 196, 182 };
            char[] mainLine = { (char)186, '\0', (char)179, '\0',
                                (char)186, '\0', (char)179, '\0', (char)186 }; 
            for(int i = 0; i < max / 2; i++) {
                mainLine[1] = mainLine[3] = (char)i;
                mainLine[5] = mainLine[7] = (char)(i + max / 2);
                printCharacters(mainLine, out);
                if(i < max / 2) {
                    printCharacters(separatorLine, out);
                }
            }
            int[] endLine = {200, 205, 207, 205, 202, 205, 207, 205, 188 };
            out.print((char)27 + "[2J");
            printCharacters(endLine, out);
        } else {
            for(int i = 0; i < args.length; i++) {
                try {
                    int number = Integer.parseInt(args[i]);
                    out.println(number + " = '" + (char)number + "'");
                } catch(NumberFormatException nfe) {
                    System.err.println("Error: \"" + args[i] + "\" is not a number");
                }
            }
        }
    }

    public static void printCharacters(int[] characters, PrintStream out) {
        char[] chars = new char[characters.length];
        for(int i = 0; i < characters.length; i++) {
            chars[i] = (char)characters[i];
        }
        printCharacters(chars, out);
    }

    public static void printCharacters(char[] characters, PrintStream out) {
        for(int i = 0; i < characters.length; i++) {
            out.print(characters[i]);
        }
        out.println();
    }
}
