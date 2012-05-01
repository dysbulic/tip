import java.awt.Point;
import java.util.Arrays;
import java.util.ArrayList;

class EightPuzzleSearchState implements SearchState, Cloneable {
    final static int[][] goalPositions = {{},   {1,2},{1,3},
                                          {2,1},{2,2},{2,3},
                                          {3,1},{3,2},{3,3}};
    final static int[][] defaultConfig = {{0,1,2},{3,4,5},{6,7,8}};
    public static enum Direction {
        DOWN, UP, LEFT, RIGHT;
        public Direction opposingDirection() {
            switch(this) {
            case DOWN: return UP;
            case UP: return DOWN;
            case LEFT: return RIGHT;
            case RIGHT: return LEFT;
            }
            return null;
        }
    }

    public int[][] puzzle;
    Point empty = null;
    int goalCostGuess = -1;
    
    public EightPuzzleSearchState() {
        this(copyIntArray(defaultConfig));
    }

    public EightPuzzleSearchState(int[][] puzzle) {
        this.puzzle = puzzle;
    }

    public double getEstimatedGoalCost() {
        if(goalCostGuess < 0) {
            goalCostGuess = 0;
            for(int row = 0, col; row < puzzle.length; row++) {
                for(col = 0; col < puzzle[row].length; col++) {
                    int num = puzzle[row][col];
                    if(num == 0) empty = new Point(row, col);
                    else goalCostGuess += (Math.abs(row + 1 - goalPositions[num][0]) +
                                           Math.abs(col + 1 - goalPositions[num][1]));
                }
            }
        }
        return goalCostGuess;
    }

    public Point getEmptySpace() {
        for(int row = 0, col; empty == null && row < puzzle.length; row++) {
            for(col = 0; empty == null && col < puzzle[row].length; col++) {
                if(puzzle[row][col] == 0) empty = new Point(col, row);
            }
        }
        return empty;
    }

    public boolean isGoal() {
        return getEstimatedGoalCost() == 0;
    }

    public synchronized ArrayList<EdgeStatePair> getSuccessors() {
        Point zero = getEmptySpace();
        ArrayList<EdgeStatePair> successors = new ArrayList<EdgeStatePair>();
        for(Direction direction : Direction.values()) {
            try {
                EightPuzzleSearchState successor = (EightPuzzleSearchState)this.clone();
                successor.move(direction);
                // edge costs are constant at 1
                successors.add(new EdgeStatePair(successor, 1));
            } catch(IllegalArgumentException iae) {
            }
        }
        return successors;
    }

    /**
     * Moves the empty space the specified direction.
     */
    public synchronized void move(Direction direction) throws IllegalArgumentException {
        Point emptyCell = getEmptySpace();
        switch(direction) {
        case DOWN:
            if(emptyCell.y >= puzzle[emptyCell.x].length - 1) throw new IllegalArgumentException("Can't move down");
            puzzle[emptyCell.y][emptyCell.x] = puzzle[emptyCell.y + 1][emptyCell.x];
            emptyCell.y++;
            break;
        case UP:
            if(emptyCell.y == 0) throw new IllegalArgumentException("Can't move up");
            puzzle[emptyCell.y][emptyCell.x] = puzzle[emptyCell.y - 1][emptyCell.x];
            emptyCell.y--;
            break;
        case LEFT:
            if(emptyCell.x == 0) throw new IllegalArgumentException("Can't move left");
            puzzle[emptyCell.y][emptyCell.x] = puzzle[emptyCell.y][emptyCell.x - 1];
            emptyCell.x--;
            break;
        case RIGHT:
            if(emptyCell.x >= puzzle.length - 1) throw new IllegalArgumentException("Can't move right");
            puzzle[emptyCell.y][emptyCell.x] = puzzle[emptyCell.y][emptyCell.x + 1];
            emptyCell.x++;
            break;
        }
        puzzle[emptyCell.y][emptyCell.x] = 0;
    }

    public static EightPuzzleSearchState getRandomPuzzle(int numMoves) {
        EightPuzzleSearchState randomPuzzle = new EightPuzzleSearchState();
        EightPuzzleSearchState.Direction lastDirection = null;
        while(numMoves > 0) {
            try {
                double rand = Math.random() * 4;
                EightPuzzleSearchState.Direction direction;
                if(rand < 1) {
                    direction = EightPuzzleSearchState.Direction.RIGHT;
                } else if(rand < 2) {
                    direction = EightPuzzleSearchState.Direction.LEFT;
                } else if(rand < 3) {
                    direction = EightPuzzleSearchState.Direction.UP;
                } else {
                    direction = EightPuzzleSearchState.Direction.DOWN;
                }
                // don't undo what was just done
                if(lastDirection == null || direction != lastDirection.opposingDirection()) {
                    String output = randomPuzzle + " + " + direction;
                    randomPuzzle.move(direction);
                    //System.out.println(output + " = " + randomPuzzle);
                    numMoves--;
                    lastDirection = direction;
                }
            } catch(IllegalArgumentException iae) {
            }
        }
        return randomPuzzle;
    }

    /*
    public static <ArrayType> ArrayType[][] copyArray(ArrayType[][] array) {
        ArrayType[][] newArray = (ArrayType[][])new Object[array.length][];
        for(int i = 0; i < array.length; i++) {
            array[i] = Arrays.copyOf(array[i], array[i].length);
        }
        return newArray;
    }
    */

    public static int[][] copyIntArray(int[][] array) {
        int[][] newArray = new int[array.length][];
        for(int i = 0; i < array.length; i++) {
            newArray[i] = Arrays.copyOf(array[i], array[i].length);
        }
        return newArray;
    }

    public Object clone() {
        return new EightPuzzleSearchState(copyIntArray(puzzle));
    }

    public String toString() {
        StringBuffer out = new StringBuffer();
        for(int col = 0, row; col < puzzle.length; col++) {
            for(row = 0; row < puzzle[col].length; row++) {
                out.append(puzzle[col][row]);
                out.append(":");
            }
        }
        return out.toString();
    }

    public int hashCode() {
        int code = 0;
        for(int col = 0, mult = 1, row; col < puzzle.length; col++) {
            for(row = 0; row < puzzle[col].length; row++, mult *= 10) {
                code += puzzle[col][row] * mult;
            }
        }
        return code;
    }

    public boolean equals(Object o) {
        return this.hashCode() == o.hashCode();
    }
}

