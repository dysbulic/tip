import java.util.regex.*;

/**
 * Doing some tests of the regular expression classes
 */
public class PatternQuoteTest {
    public static void main( String[] args ) {
        System.out.println( Pattern.quote( "/^$/" ) );
        System.out.println( Pattern.quote( "^$" ) );
        try {
            System.out.println( Pattern.quote( "\\Q\\E^$" ) );
        } catch(Exception e) {
            System.out.println( e.getMessage() );
        }
    }
}
                                
