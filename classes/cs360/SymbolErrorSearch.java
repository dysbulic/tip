import java.util.HashMap;
import java.util.Map;
import java.util.List;

public class SymbolErrorSearch {
    public static void main(String[] args) {
        SymbolChecker checker =
            new SymbolChecker() {
                public boolean isPotentialPathNode(String string) {
                    // the string has length 8 and starts and ends with E
                    return ((string.startsWith("E") && string.length() <= 8)
                            && (string.length() != 8 || string.endsWith("E")));
                }
                public boolean isGoal(SymbolSearchState state) {
                    return state.currentString.length() == 8;
                }
            };

        SymbolSearchState.weightManager =
            new WeightManager() {
                String original = "EEBCAAEE";
                Map <String, Double> errorRates = new HashMap<String, Double>();
                {
                    for(SymbolSearchState.Symbol startSymbol : SymbolSearchState.Symbol.values()) {
                        for(SymbolSearchState.Symbol endSymbol : SymbolSearchState.Symbol.values()) {
                            errorRates.put(startSymbol.toString() + endSymbol.toString(), 0.1);
                        }
                    }
                    String[] zeroes = {"EB", "EC", "AC", "BE", "CE"};
                    for(String zero : zeroes) {
                        errorRates.put(zero, (double)0);
                    }
                    errorRates.put("EE", .9);
                    errorRates.put("AA", .8);
                    errorRates.put("BB", .8);
                    errorRates.put("CC", .8);
                }
                public double getWeight(SymbolSearchState state, SymbolSearchState.Symbol symbol) {
                    if(state.previousSymbol == null) {
                        return 1; // messages begin with E so this is a certainty
                    } else {
                        double totalProbability = (double)0;
                        char transmittedChar = original.charAt(state.currentString.length());
                        for(SymbolSearchState.Symbol testSymbol : SymbolSearchState.Symbol.values()) {
                            if(errorRates.get(transmittedChar + testSymbol.toString()) != 0) {
                                totalProbability += super.getWeight(state, testSymbol);
                            }
                        }
                        /*
                        System.out.println(transmittedChar + symbol.toString() + ": " +
                                           (super.getWeight(state, symbol) *
                                            errorRates.get(transmittedChar + symbol.toString()) / totalProbability));
                        */
                        return errorRates.get(transmittedChar + symbol.toString()) * super.getWeight(state, symbol) / totalProbability;
                    }
                }
            };

        
        SymbolSearchState initialState = new SymbolSearchState(checker);

        System.out.println("Searching to: " + initialState);
        List<SearchState> path = (new AStarSearcher()).search(initialState);
        System.out.println("\nGoal Path: " + path);

    }
}
