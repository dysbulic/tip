import java.util.List;

public class EightPuzzleSearch {
    public static void main(String[] args) {
        EightPuzzleSearchState onePuzzle = new EightPuzzleSearchState(new int[][] {{1,0,2},{3,4,5},{6,7,8}});
        System.out.println("One Out of Place:");
        List<SearchState> path = (new AStarSearcher()).search(onePuzzle);
        System.out.println(path.size() + " >> " + path);

        EightPuzzleSearchState fourPuzzle = new EightPuzzleSearchState(new int[][] {{3,1,2},{6,0,5},{7,4,8}});
        System.out.println("Four Out of Place:");
        path = (new AStarSearcher()).search(fourPuzzle);
        System.out.println(path.size() + " >> " + path);

        /*
        EightPuzzleSearchState sixPuzzle = new EightPuzzleSearchState(new int[][] {{3,1,2},{6,5,8},{7,0,4}});
        System.out.println("Six Out of Place:");
        path = (new AStarSearcher()).search(sixPuzzle);
        System.out.println(path.size() + " >> " + path);
        */

        for(int numMoves = 1; numMoves <= 21; numMoves += 5) {
            EightPuzzleSearchState randomPuzzle = EightPuzzleSearchState.getRandomPuzzle(numMoves);
            System.out.println("\n" + numMoves + " Out of Place: " + randomPuzzle);
            path = (new AStarSearcher()).search(randomPuzzle);
            System.out.println(path.size() + " >> " + path);
        }
    }
}
