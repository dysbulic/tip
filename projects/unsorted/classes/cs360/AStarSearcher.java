import java.util.List;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Arrays;
import java.util.HashMap;

/**
 * Solves 8-puzzles using A*
 */

interface SearchState {
    public List<EdgeStatePair> getSuccessors();
    public boolean isGoal();
    public double getEstimatedGoalCost();
}

class EdgeStatePair {
    SearchState state;
    double edgeCost;

    public EdgeStatePair(SearchState state, double edgeCost) {
        this.state = state; this.edgeCost = edgeCost;
    }
    public double getEdgeCost() { return edgeCost; }
    public SearchState getState() { return state; }
    public String toString() { return state.toString() + ":<" + edgeCost + ">"; }
}

class WeightedSearchState implements Comparable<WeightedSearchState> {
    SearchState state;
    Double cost;
    Double estimate;

    public WeightedSearchState(SearchState state, Double cost, Double estimate) {
        this.state = state;
        this.cost = cost;
        this.estimate = estimate;
    }

    public int compareTo(WeightedSearchState state) {
        double offset = (this.cost + this.estimate) - (state.cost + state.estimate);
        return (offset < 0 ? -1 : (offset > 0 ? 1 : 1));
    }

    public String toString() { return state.toString() + ":<" + cost + "+" + estimate + ">"; }
}

/**
 * A* search constantly expands the node with the least estimated cost
 * to the root. The esimated cost is a combination of the known cost
 * to a given node plus a heuristic which estimates the cost from
 * the node to a goal.
 */
public class AStarSearcher {
    /**
     * A* uses two lists to maintain the nodes to be searched. The
     * "open" list is a sorted queue of potential pathes to the root.
     * It is sorted by the estimated cost to a goal and a least
     * estimated cost path is always examined next. The "closed" list
     * is a set of nodes that have already been examined. When
     * considering the children of a node, a node that is in the
     * closed list should not be examined again unless the cost to get
     * to it along the current path is less than the cost of the path
     * that reached it previously.
     *
     * The open list stores both a SearchState and a weight so that
     * the states can be ordered.
     */
    ArrayList<WeightedSearchState> openList = new ArrayList<WeightedSearchState>();
    ArrayList<SearchState> closedList = new ArrayList<SearchState>();

    /**
     * The best cost to get to a given node is cached.
     */
    HashMap<SearchState,Double> costs = new HashMap<SearchState,Double>();

    /**
     * The best cost parents of nodes are stored to reconstruct the
     * path to the root.
     */
    HashMap<SearchState,SearchState> parentOf = new HashMap<SearchState,SearchState>();

    /**
     * Method which actually performs the search.
     */
    public List<SearchState> search(SearchState initialState) {
        // The cost to get to the start from the start is 0
        costs.put(initialState, (double)0);

        // Begin by making the start state the first node to be searched
        openList.add(new WeightedSearchState(initialState, (double)0, (double)0));

        // A solution has no yet been found
        boolean found = false;

        while(!found && openList.size() > 0) {
            // the open list is sorted so that the least estimated
            // cost solution will be chosen
            Collections.sort(openList);

            System.out.println("\n\nOpen: (" + openList.size() + "): " + openList);
            System.out.println("\nClosed: (" + closedList.size() + "): " + closedList);

            // there may be multiple nodes tied with the least
            // estimated cost to goal. if one of these nodes is a
            // goal, it should be chosen first.
            WeightedSearchState current = openList.get(0);
            for(int idx = 1; idx < openList.size() && current.compareTo(openList.get(idx)) == 0; idx++) {
                if(openList.get(idx).state.isGoal()) {
                    current = openList.get(idx);
                    break;
                }
            }

            if(current.state.isGoal()) {
                found = true;
            } else {
                // when a node is examined, it is removed from the
                // open list and placed on the closed list
                openList.remove(current);
                closedList.add(current.state);

                // all the successors of the current node are
                // considered
                for(EdgeStatePair successor: current.state.getSuccessors()) {
                    // a successor will be added to the open list if
                    // it hasn't been examined previously.
                    // additionaly, if the node has been expanded
                    // previously, check if the new path is cheaper
                    // than the previous one. it is also necessary to
                    // check that the start state isn't being being
                    // revisited since it has no parent
                    boolean addToOpen = (!closedList.contains(successor.getState()) ||
                                         (!successor.getState().equals(initialState) &&
                                          costs.get(current.state) < costs.get(parentOf.get(successor.getState()))));
                    if(addToOpen) {
                        costs.put(successor.getState(), costs.get(current.state) + successor.getEdgeCost());
                        parentOf.put(successor.getState(), current.state);
                        openList.add(new WeightedSearchState(successor.getState(),
                                                             costs.get(successor.getState()),
                                                             successor.getState().getEstimatedGoalCost()));
                    }
                }
            }
        }
        ArrayList<SearchState> path = null;
        if(!openList.isEmpty()) {
            path = new ArrayList<SearchState>();
            path.add(openList.get(0).state);
            while(parentOf.containsKey(path.get(0))) {
                path.add(0, parentOf.get(path.get(0)));
            }
        }
        return path;
    }
}
