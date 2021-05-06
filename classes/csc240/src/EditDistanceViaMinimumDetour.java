public class EditDistanceViaMinimumDetour {
    protected CharacterTable fromString;
    protected CharacterTable [] toString;
    protected EditDistanceGraphStateStack [] detours;
    protected int minimumDistance;
    protected boolean calculated;
    protected int closestStringIndex = 0;
    protected static boolean TESTING = true;

    public EditDistanceViaMinimumDetour(String from, String [] to) {
        fromString = new CharacterTable(from);
        toString = new CharacterTable[to.length];
        int i = 0;
        
        if(TESTING) {
            System.out.println("String to compare to is \"" + from + "\"");
            System.out.println("Strings being compared are:");
        }

        for(i = 0; i < to.length; i++) {
            toString[i] = new CharacterTable(to[i]);
            
            if(TESTING)
                System.out.println("\t\"" + to[i] + "\"");
        }

        int maxLength = 0;
        
        for(i = 0; i < toString.length; i++)
            maxLength = Math.max(maxLength, toString[i].length());
        
        detours = new EditDistanceGraphStateStack[maxLength];
        
        for(i = 0; i < maxLength; i++)
            detours[i] = new EditDistanceGraphStateStack();
        
        calculated = false;
    }

    public String closestString() {
        if(!calculated)
            calculate();
        
        return toString[closestStringIndex].toString();
    }

    protected void calculate() {
        boolean solutionFound = false;
        int i = 0;
        
        int currentLowerBound = Integer.MAX_VALUE;
        EditDistanceGraphState currentState = new EditDistanceGraphState();
        EditDistanceGraphState tempState;
        int newLowerBound = Integer.MAX_VALUE;
        
        for(i = 0; i < toString.length; i++)
            currentLowerBound = Math.min(currentLowerBound,
                                         lowerBoundFor(fromString.countsFor(0),
                                                       toString[i].countsFor(0)));
        
        for(i = 0; i < toString.length; i++) {
            newLowerBound = lowerBoundFor(fromString.countsFor(0), toString[i].countsFor(0));
            currentState = new EditDistanceGraphState(i, 0, 0, newLowerBound);
            detours[newLowerBound - currentLowerBound].push(currentState);
        }
        
        while(!solutionFound) {
            if(TESTING) {
                for(i = 0; i < detours.length; i++) {
                    System.out.print("Stack [" + i + "]: ");
                    detours[i].dump();
                }
                System.out.println("");
            }

            for(i = 0; i < detours.length && detours[i].empty(); i++);

            if(i >= detours.length) {
                System.out.println("Error: Ran out of stacks");
                System.exit(0);
            } else
                currentState = detours[i].nextElement();

            if(currentState.fromIndex == fromString.length()
               && currentState.toIndex == toString[currentState.toId].length()) {
                closestStringIndex = currentState.toId;
                solutionFound = true;
            } else {
                if(currentState.toIndex + 1 <= toString[currentState.toId].length()) {
                    tempState = new EditDistanceGraphState(currentState.toId,
                                                           currentState.fromIndex,
                                                           currentState.toIndex + 1);
                    tempState.lowerBound = lowerBoundFor(tempState);

                    detours[detourFor(currentState, tempState)].push(tempState);
                }

                if(currentState.fromIndex + 1 <= fromString.length()) {
                    tempState = new EditDistanceGraphState(currentState.toId,
                                                           currentState.fromIndex + 1,
                                                           currentState.toIndex);
                    tempState.lowerBound = lowerBoundFor(tempState);

                    detours[detourFor(currentState, tempState)].push(tempState);
                }

                if(currentState.fromIndex + 1 <= fromString.length()
                   && currentState.toIndex + 1 <= toString[currentState.toId].length())	{
                    tempState = new EditDistanceGraphState(currentState.toId,
                                                           currentState.fromIndex + 1,
                                                           currentState.toIndex + 1);
                    tempState.lowerBound = lowerBoundFor(tempState);

                    detours[detourFor(currentState, tempState)].push(tempState);
                }
            }
            if(TESTING)
                System.out.println("");
        }
    }

    public int detourFor(EditDistanceGraphState start, EditDistanceGraphState end) {
        if(start.equals(end)) {
            System.out.println("Error: Attempt to detour from X to X");
            return 0;
        } else {
            if(TESTING)
                System.out.println("Calculating the detour for going from ("
                                   + start.fromIndex + "," + start.toIndex + ") to ("
                                   + end.fromIndex + "," + end.toIndex + ") in string \""
                                   + toString[end.toId].toString() + "\"");

            return costFor(start, end) + end.lowerBound - start.lowerBound;
        }
    }

    public int costFor(EditDistanceGraphState start, EditDistanceGraphState end) {
        int cost;
        
        if(start.toId != end.toId) {
            System.out.println("Error in costFor: " + start.toId + " && " + end.toId);
            return 1;
        }

        /**
         * There is additional processing necessary becasue though the graph is indexed from
         * 0 to the length of the string the string is indexed from 0 to the length of the
         * string - 1 and whereas in the graph an index of 0 represents an empty string, in
         * the string an index of 0 represents the first character. It is necessary therefore
         * to do a special case check for the zero element of the graph and the cost for it
         * will never be zero because the only place that both strings are empty is at 0,0
         * and that is the start node which may not be transitioned to.
         */

        if(end.fromIndex == 0 || end.toIndex == 0)
            cost = 1;

        /**
         * The only case in which the cost will be other than 1 is in fact an equal
         * substitution. An equal substitution occurs when the character being substituted
         * is equal to the character being substituted for.
         *
         * The check to see if a substitution is occuring is that for the two points,
         * PS(XS, YS) (start) and PE(XE, YE) (end), XS < XE and YS < YE.
         *
         * Again it is important to remember that the indexing of the graph is right-
         * shifted one space in relation to the indexing of the string.
         */
        else {
            System.out.println("\tComparing " + fromString.charAt(end.fromIndex - 1)
                               + " in \"" + fromString.toString()
                               + "\" with " + toString[end.toId].charAt(end.toIndex - 1)
                               + " in \"" + toString[end.toId].toString() + "\"");
            
            if(start.fromIndex < end.fromIndex && start.toIndex < end.toIndex) {
                if(fromString.charAt(end.fromIndex - 1) ==
                   toString[end.toId].charAt(end.toIndex - 1))
                    cost = 0;
                else
                    cost = 1;
            } else
                cost = 1;
        }
        return cost;
    }


    public int lowerBoundFor(EditDistanceGraphState state) {
        return lowerBoundFor(fromString.countsFor(state.fromIndex),
                             toString[state.toId].countsFor(state.toIndex));
    }

    public static int lowerBoundFor(CharacterCountTable tableOne,
                                    CharacterCountTable tableTwo) {
        int i = 0;
        int deficiencies = 0;
        int excesses = 0;

        for(i = 0; i < tableOne.length(); i++)
            if(tableOne.countFor(i) <= tableTwo.countFor(tableOne.charAt(i)))
                deficiencies += tableTwo.countFor(tableOne.charAt(i)) - tableOne.countFor(i);
            else
                excesses += tableOne.countFor(i) - tableTwo.countFor(tableOne.charAt(i));
        
        for(i = 0; i < tableTwo.length(); i++)
            if(!tableOne.isInTable(tableTwo.charAt(i)))
                deficiencies += tableTwo.countFor(i);
        
        return Math.max(excesses, deficiencies);
    }
}
