/**
 * This is a simple driver program to demonstrate the functionality of the EditDistance
 * classes developed for CSc 240.
 *
 * The interface could easily be expanded to include any types of graphical components that
 * the user wanted. The class is self contined and funtions properly.
 *
 * Will Holcomb
 * Programming Assignment Number 2
 * May 5, 1999
 */
public class EditDistanceViaMinimumDetourTest {
    public static void main(String [] args) {
        if(args.length < 2) {
            System.out.println("Edit Distance Calculator");
            System.out.println(" Usage: calculator [source] [destination]+");
            System.out.println("");
            System.out.println("Displays which of the destination strings is closest to source");
        } else {
            String fromString = args[0];
            String [] toStrings = new String[args.length - 1];

            for(int i = 1; i < args.length; i++)
                toStrings[i - 1] = args[i];

            EditDistanceViaMinimumDetour e =
                new EditDistanceViaMinimumDetour(fromString, toStrings);
        
            System.out.println("The closest string to " + fromString + " is " + e.closestString());
        }
    }
}
