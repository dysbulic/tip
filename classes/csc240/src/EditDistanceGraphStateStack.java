import java.util.*;

public class EditDistanceGraphStateStack extends java.util.Stack {
    final static long serialVersionUID = 23463410;

    public EditDistanceGraphState nextElement() {
        return (EditDistanceGraphState)pop();
    }

    public EditDistanceGraphState top() {
        return (EditDistanceGraphState)peek();
    }

    public void reverse() {
        Object tempObject;
        int i = 0;
        int n = size();
        
        while(i < n - i - 1) {
            tempObject = elementAt(i);
            setElementAt(elementAt(n - i - 1), i);
            setElementAt(tempObject, n - i - 1);
            i++;
        }
    }

    public void dump() {
        Enumeration tempEnumeration = elements();
        EditDistanceGraphState currentElement;
        
        if(size() == 0) {
            System.out.print("Stack is empty.");
            System.out.println("");
        } else {
            System.out.print("States in the stack are:");
            System.out.println("");
            System.out.println("\tFrom \tTo \tId \tBound");

            while(tempEnumeration.hasMoreElements()) {
                currentElement = (EditDistanceGraphState)tempEnumeration.nextElement();
                System.out.println("\t" + currentElement.fromIndex
                                   + "\t" + currentElement.toIndex
                                   + "\t" + currentElement.toId
				 + "\t" + currentElement.lowerBound);
            }
        }
    }
}
