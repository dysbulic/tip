package org.himinbi.app;

import org.himinbi.util.*;
import java.util.*;
import java.text.*;

public class RunnerTest {
    int count = 0;

    public void printCount() {
	System.out.println(" " + (count + 1) + ": count -> " + (count++));
    }

    public void countPrint() {
	System.out.println(" " + (count + 1) + ": COUNT -> " + (count++));
    }

    public static void main(String[] args) {
	RunnerTest me = new RunnerTest();
	Date date = null;
	DateFormat format = new SimpleDateFormat("yyyy MM dd HH:mm:ss:SS (hh:mm a)");
	Object block = new Object();
	try {
	    Runner runner = new Runner(me, me.getClass().getMethod("printCount", (Class[])null), 100);
	    runner.setPriority(Thread.MAX_PRIORITY);
	    for(int i = 1; i <= 3; i++) {
		System.out.println("Time: " + format.format(new Date()));
		runner.setRunning((Math.random() >= .5 ? true : false));
		if(Math.random() >= .5) {
		    runner.setTargetMethod(me.getClass().getMethod("countPrint", (Class[])null));
		} else {
		    runner.setTargetMethod(me.getClass().getMethod("printCount", (Class[])null));
		}
		synchronized(block) {
		    try {
			block.wait(500);
			runner.sleep(500);
			block.wait(500);
		    } catch(InterruptedException e) {
		    }
		}
	    }
	    runner.die();
	} catch(NoSuchMethodException e) {
	    e.printStackTrace(System.err);
	}
	System.out.println("Exiting main");
    }
}
