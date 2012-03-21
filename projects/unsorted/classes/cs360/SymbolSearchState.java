/**
 */
import java.util.Map;
import java.util.List;
import java.util.Arrays;
import java.util.ArrayList;
import java.util.Hashtable;

class SymbolPair {
    SymbolSearchState.Symbol start, end;
    SymbolPair(SymbolSearchState.Symbol start, SymbolSearchState.Symbol end) {
        this.start = start; this.end = end;
    }
    /* this could collide, but it is very unlikely */
    public int hashCode() { return start.hashCode() * 99999 + end.hashCode(); }
    public String toString() { return "<" + start + "," + end + ">"; }
    public boolean equals(Object pair) { return hashCode() == pair.hashCode(); }
}

class WeightManager {
    static Map <SymbolPair, Double> weights = new Hashtable<SymbolPair, Double>();
    static {
        // start everything at .25 and change the ones that differ
        for(SymbolSearchState.Symbol startSymbol : SymbolSearchState.Symbol.values()) {
            for(SymbolSearchState.Symbol endSymbol : SymbolSearchState.Symbol.values()) {
                weights.put(new SymbolPair(startSymbol, endSymbol), .25);
            }
        }
        weights.put(new SymbolPair(SymbolSearchState.Symbol.A, SymbolSearchState.Symbol.E), .5);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.A, SymbolSearchState.Symbol.A), (double)0);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.B, SymbolSearchState.Symbol.E), .125);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.B, SymbolSearchState.Symbol.A), .5);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.B, SymbolSearchState.Symbol.B), .125);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.C, SymbolSearchState.Symbol.A), .125);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.C, SymbolSearchState.Symbol.B), .5);
        weights.put(new SymbolPair(SymbolSearchState.Symbol.C, SymbolSearchState.Symbol.C), .125);
    }

    public double getWeight(SymbolSearchState state, SymbolSearchState.Symbol symbol) {
        return (state.previousSymbol == null
                ? (double)1 / 4 // Symbol.size();
                : weights.get(new SymbolPair(state.previousSymbol, symbol)));
    }
}

interface SymbolChecker {
    public boolean isPotentialPathNode(String string);
    public boolean isGoal(SymbolSearchState state);
}

public class SymbolSearchState implements SearchState {
    public static enum Symbol {
        E, A, B, C;
    }

    static WeightManager weightManager;
    String currentString = "";
    double probability = 1;
    Symbol previousSymbol = null;
    SymbolChecker checker;

    static { weightManager = new WeightManager(); }

    public SymbolSearchState() { }

    public SymbolSearchState(SymbolChecker checker) {
        this.checker = checker;
    }

    public SymbolSearchState(String currentString, Symbol previousSymbol,
                             double probability, SymbolChecker checker) {
        this.currentString = currentString;
        this.probability = probability;
        this.previousSymbol = previousSymbol;
        this.checker = checker;
    }
    
    public List<EdgeStatePair> getSuccessors() {
        ArrayList<EdgeStatePair> successors = new ArrayList<EdgeStatePair>();
        for(Symbol symbol : Symbol.values()) {
            String next = currentString + symbol.toString();
            if(checker == null || checker.isPotentialPathNode(next)) {
                double newProbability = probability * weightManager.getWeight(this, symbol);
                successors.add(new EdgeStatePair(new SymbolSearchState(next, symbol, newProbability, checker),
                                                 probability - newProbability));
            }
        }
        //System.out.println("Returning: " + successors);
        return successors;
    }
    
    public boolean isGoal() { return checker == null || checker.isGoal(this); }
    
    public double getEstimatedGoalCost() { return 0; }

    public String toString() { return currentString + "(" + probability + ")"; }
}
