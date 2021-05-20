import java.io.*;
import java.lang.*;
import java.util.*;

//a very simple class of methods to operate on a 1-bit "register"
//wr. Lewis Baumstark
public class Register {
    private boolean status;

    //bit register to hold the status of conditional expressions
    public Register(){ this.set(); }

    public boolean check(){ return this.status; }
    public void reset(){ this.status = true; }
    public void set(){ this.status = false; }
}
