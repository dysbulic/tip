package org.himinbi.util;

import java.lang.reflect.*;
//import org.apache.log4j.*;

public class Runner implements Runnable {
    Object target;
    Method toInvoke;
    long interval;
    boolean running = false;
    boolean killed = false;
    Thread runner;
    //static Category cat = Category.getInstance("Runner");

    {
	//cat.debug("Runner created:");
    }

    public Runner(Object target, Method toInvoke) {
	this(target, toInvoke, 1000, false);
    }
    
    public Runner(Object target, Method toInvoke, long interval) {
	this(target, toInvoke, interval, false);
    }

    public Runner(Object target, Method toInvoke, boolean running) {
	this(target, toInvoke, 1000, running);
    }

    public Runner(Object target, Method toInvoke, long interval, boolean running) {
	runner = new Thread(this);
	setTargetObject(target);
	setTargetMethod(toInvoke);
	setInterval(interval);
	setRunning(running);
	runner.start();
    }

    public void setTargetObject(Object object) {
	//cat.debug("Target set to: " + object);
	target = object;
    }

    public Object getTargetObject() {
	return target;
    }

    public void setTargetMethod(Method method) {
	//cat.debug("Target method set to: " + method);
	toInvoke = method;
    }

    public Method getTargetMethod() {
	return toInvoke;
    }

    public void setInterval(long interval) {
	//cat.debug("Interval set to: " + interval);
	synchronized(this) {
	    this.interval = interval;
	    notifyAll();
	}
    }

    public long getInterval() {
	return interval;
    }

    public void sleep(long interval) {
	if(running) {
	    synchronized(runner) {
		try {
		    Thread.sleep(interval);
		} catch(InterruptedException e) {
		}
	    }
	}
    }

    public void setPriority(int priority) {
	runner.setPriority(priority);
    }

    public int getPriority() {
	return runner.getPriority();
    }

    public void setRunning(boolean running) {
	if(killed) {
	    killed = false;
	    synchronized(runner) {
		Thread runner = new Thread(this);
		runner.setPriority(this.runner.getPriority());
		this.runner = runner;
		runner.start();
	    }
	}
	//cat.debug("Setting running: " + this.running + " -> " + running);
	this.running = running;
	synchronized(runner) {
	    runner.notify();
	}
	//cat.debug("Leaving setRunning()");
    }

    public boolean isRunning() {
	return running;
    }

    public void die() {
	killed = true;
	synchronized(runner) {
            runner.notify();
        }
    }

    public void run() {
	//cat.info("Beginning run: " + runner);
	while(!killed) {
	    synchronized(runner) {
		while(!running && !killed) {
		    try {
			//cat.info("Holding loop: " + running);
			runner.wait();
		    } catch(InterruptedException e) {
		    }
		}
	    }
	    if(!killed) {
		synchronized(runner) {
		    try {
			Thread.sleep(interval);
			try {
			    toInvoke.invoke(target, (Object[])null);
			} catch(Exception e) {
			    e.printStackTrace(System.err);
			    running = false;
			}
		    } catch(InterruptedException e) {
		    }
		}
	    }
	}
	running = false;
	//cat.info("Ending run: " + toString());
    }
}
