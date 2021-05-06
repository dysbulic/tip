public class EditDistanceGraphState {
    public int fromIndex;
    public int toIndex;
    public int toId;
    public int lowerBound;
    
    public EditDistanceGraphState() {
        fromIndex = 0;
        toIndex = 0;
        toId = 0;
        lowerBound = Integer.MAX_VALUE;
    }
    
    public EditDistanceGraphState(int id) {
        fromIndex = 0;
        toIndex = 0;
        toId = id;
        lowerBound = Integer.MAX_VALUE;
    }

    public EditDistanceGraphState(int id, int index1, int index2) {
        fromIndex = index1;
        toIndex = index2;
        toId = id;
        lowerBound = Integer.MAX_VALUE;
    }
    
    public EditDistanceGraphState(int id, int index1, int index2, int lb) {
        fromIndex = index1;
        toIndex = index2;
        toId = id;
        lowerBound = lb;
    }

    public boolean equals(EditDistanceGraphState toCompare) {
        if(fromIndex == toCompare.fromIndex
           && toIndex == toCompare.toIndex
           && toId == toCompare.toId)
            return true;
        else
            return false;
    }
}
