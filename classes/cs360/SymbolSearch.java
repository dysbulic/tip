import java.util.List;

public class SymbolSearch {
    public static void main(String[] args) {
        SymbolChecker checker =
            new SymbolChecker() {
                public boolean isPotentialPathNode(String string) {
                    return string.startsWith("E") && string.length() <= 5;
                }
                public boolean isGoal(SymbolSearchState state) {
                    return state.currentString.length() == 5;
                }
            };
        
        SymbolSearchState initialState = new SymbolSearchState(checker);

        System.out.println("Searching to: " + initialState);
        List<SearchState> path = (new AStarSearcher()).search(initialState);
        System.out.println("\nGoal Path: " + path);

    }
}
