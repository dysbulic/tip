/*
	CommandInterpreter.java
	by Lewis Baumstark

Purpose
--------------

Takes a string formatted with the JBOT language as input and parses it for
calling appropriate graphics routines, calculations, etc.


Algorithm
--------------
	
1. pre-passes through an input string and creates a table of all lines
with "LABEL" command on them (for reference when input script is actually
run).

2. Tokenizes an input string into lines delimited by ";"'s.

3. Tokenizes each line, in succession, into the command followed by its
operators.

4. Each parsed line calls the appropriate method to perform calculations,
call graphics routines, etc.  Also writes tracing information to a
StringBuffer object.

*/


import java.io.*;
import java.lang.*;
import java.util.*;

public class CommandInterpreter {

      //public variables/objects

      public static String script; //string containing the entire text of the input script
      public StringTokenizer scriptLines; //each token here is one line of the input script
      public static String name = new String("default");

      //graphics objects
      public base b = new base();
      public robot r = new robot();

      private static int MAX_CODE = 100; //specifies the size of codeMem[]
      public static String codeMem[] = new String [MAX_CODE]; //a "virtual address space" for lines of code
      int lineNum = 0; //index variable for codeMem[]
      int totalLines =  0; //will hold the total number of lines
      int n; //counter variable.  no other major significance.  poor insignificant little n.

    //all label references begin with "L", ie, "LABEL L14" accesses the 14th element
    private static int MAX_LABELS = 25; //specifies size of the above
    public static int labelTable[] = new int [MAX_LABELS]; //lookup table for labelled lines

    //all register references begin with "V" followed by an integer, ie
    // "V5" refers to the data in register 5, which is varList[5]
    private static int MAX_VAR = 10; //specifies size of the above
    public static int varList[] = new int [MAX_VAR]; // list of "virtual data registers"

    //Status register definition
    private static Register SR = new Register();

    //place to send tracing information
    public StringBuffer tracer = new StringBuffer();

  //public constructor method
    public CommandInterpreter()
    {
	    this.loadScript("");
    }

    //loads the input string into the command parser
    public void loadScript(String s)
    {
	    this.script = s;
    }

  //reset all major variables back to initial values
    public void reset()
    {
      this.script = "";
      this.name = "";
      this.lineNum = 0;
      this.totalLines = 0;
      SR.set();
      this.tracer.setLength(0);
      r.reset();
      b.reset();

      for(n=0;n<=MAX_VAR;n++){varList[n]=0;}
      for(n=0;n<=MAX_LABELS;n++){labelTable[n]=0;}
    }


  //main looping routing to go through all the lines of the input script
    public void parseScript()
    {
	    String s = new String(this.script);

	    this.scriptLines = new StringTokenizer(this.script, ";");
	    
	    //put the full source at the beginning of the trace stream
	    this.tracer.append("\nUser Input\n----------\n");
	    this.tracer.append(this.script+"\n\n\n");
	    this.tracer.append("+---Begin Trace---+\n\n");

	    //"loads" the lines of code into the "virtual memory"
	    while(this.scriptLines.hasMoreTokens())
	    {
		    CommandInterpreter.codeMem[this.lineNum] = this.scriptLines.nextToken();
		    this.lineNum++;
	    }

	    this.totalLines = this.lineNum;	

	    this.lineNum = 0; //needs to be reset for later

	    //pre pass the script and find all the labels
	    this.findLabel1(codeMem);

	    //print a table of labels into the tracer
	    for(n=0;n<=MAX_LABELS-1;n++)
	    {
		    this.tracer.append("L"+n+" = "+labelTable[n]+"\n");
	    }	



	    //the graphics routines accessed
	    while(this.lineNum < this.totalLines)
	    {
		    this.processLine(codeMem[this.lineNum]);
		    this.lineNum++;
	    }

	    //System.out.print(this.tracer.toString());

	    this.lineNum = 0; //reset so we overwrite codeMem instead of appending!
    }


      //findLabel1() and findLabel2() are used to establish a list of labelled lines
      //this is done on the first pass of the interpreter
    private void findLabel1(String s[])
	{
	  int lineNum=0;

	    for(lineNum=0;lineNum<=this.totalLines-1;lineNum++)
	    {
	      this.findLabel2(s[lineNum],lineNum);
	    }
	}

    private void findLabel2(String s, int lnum)
	{
	  int a;
	  StringTokenizer t = new StringTokenizer(s);
	  if((t.nextToken()).equals("LABEL"))
	    {
	      this.labelTable[this.getLabelIndex(t.nextToken())] = lnum;
	    }
	}

  //parse out the individual lines of code
    public void processLine(String line)
    {
	    String firstWord;

	    StringTokenizer command = new StringTokenizer(line);

	    this.tracer.append(line+";\n"); //tell which line we're tracing

	    firstWord = command.nextToken(); //ie, firstWord = the keyword

    	    //route to the appropriate method to process the keyword
	    if(firstWord.equalsIgnoreCase("EQ")) this.EQ(command.nextToken(), command.nextToken());
	    if(firstWord.equalsIgnoreCase("ADD")) 
	    {
		    this.ADD(command.nextToken(), command.nextToken());
	    }
	    if(firstWord.equalsIgnoreCase("SENSOR")) this.SENSOR(); 
	    if(firstWord.equalsIgnoreCase("COPEN")) this.COPEN(command.nextToken());
	    if(firstWord.equalsIgnoreCase("CCLOSE")) this.CCLOSE(command.nextToken());
	    if(firstWord.equalsIgnoreCase("ROTATE")) this.ROTATE(command.nextToken(),command.nextToken(),command.nextToken());
	    if(firstWord.equalsIgnoreCase("BRT")) this.BRT(command.nextToken());
	    if(firstWord.equalsIgnoreCase("BNT")) this.BNT(command.nextToken());
	    if(firstWord.equalsIgnoreCase("LABEL")) this.LABEL(command.nextToken());
	    if(firstWord.equalsIgnoreCase("END"))
	    {
		    this.tracer.append("Program finished.\n");
		    this.tracer.append("\n+---End Trace---+\n\n");
		    return;
	    }
    }

    //decides if an operator is a register name or a numeric value
    //if a register, returns the value in the register, else it
    //returns the value of the integer
    public int getVarOrInt(String s)
    {
	    if(s.startsWith("V")) return this.varList[Integer.parseInt(s.substring(1))];
	    else return Integer.parseInt(s);
    }

    //returns the index of the label reference.  if an error occurs, will simply return the next line.
    private int getLabelIndex(String s)
    {
	    if(s.startsWith("L")) return Integer.parseInt(s.substring(1));
	    else return 0;
    }

    //returns the value of the label reference.
    public int getLabel(String s)
    {
	    if(s.startsWith("L")) return this.labelTable[Integer.parseInt(s.substring(1))];
	    else return ++this.lineNum;
    }

    //returns true if op1 == op2, else false, sets the status register accordingly
    public boolean EQ(String op1, String op2)
    {
	    int a;
	    int b;
	    a = this.getVarOrInt(op1);
	    b = this.getVarOrInt(op2);
	    if(a == b) SR.reset();
	    else SR.set();
	    this.tracer.append("op1 = "+a+" op2 = "+b+"\nresult: "+SR.check()+"\n\n");
	    return SR.check();
    }

    //adds two values and places the result in the first operand
    //the first operand must be a variable name
    public int ADD(String op1, String op2)
    {
	    int a;
	    int b;
	    int c;
	    int result;

	    a = this.getVarOrInt(op1);

	    b = this.getVarOrInt(op2);

	    c = Integer.parseInt(op1.substring(1));

	    result = a + b;
	    this.varList[c] = result;

	    //write tracing info
	    this.tracer.append("op1 + op2 -> V"+c+"\n"+a+" + "+b+" -> "+result+"\n\n");	

	    return result;    
    }

    //sets the status register to true if the claw's sensor detects an object
    //false otherwise
    public boolean SENSOR()
    {
    /*	if("Claw Object".sensor)SR.set(); //needs to be coordinator with
    Andy
	    else SR.reset();
    */
	    //write tracing info
	    this.tracer.append("Sensor status: "+SR.check()+"\n\n");

	    return SR.check();
    }

    //calls the claw open routines with a percentage
    //claw will open to that percentage, NOT that much more percentage 
    public static int percent = 0;
    public int COPEN(String op)
    {
	    int a;
	    a = this.getVarOrInt(op);
	    //percent = percent + a;
	    //test if claw is already open this much
	    //if(percent >= 100) percent = 99;

    	    r.moveControl(0, 0, a);
    
	    //write tracing info
	    this.tracer.append("\tClaw Position is "+percent+" %\n\n");

	    return percent;
    }

    public int CCLOSE(String op)
    {
	    int a;
	    a = this.getVarOrInt(op);
	    //    percent = percent - a;

	    //test if claw isn't closed
	    //if(percent <= 0) percent = 0;
    
	    r.moveControl(0, 0, -a);
   
	    //write tracing info
	    this.tracer.append("Claw position is "+percent+" %\n\n");

	    return percent;
    }

    // "branch on true"
    public void BRT(String op)
    {

	    if(SR.check())
	    {
		    this.lineNum = this.getLabel(op);
		    //write tracing info
		    this.tracer.append("Branching to label "+op+"\n\n");
		    //send command to Andy's stuff here
		    this.SR.set();
	    }
	    else this.tracer.append("Condition not true; no branch.\n\n");
	    //this.lineNum--; //necessary b/c of the ++ in the while loop
    }

    //"branch on not true"
    public void BNT(String op)
    {
	    if(!SR.check())
	    {
		    this.lineNum = this.getLabel(op);
		    //write  tracing info
		    this.tracer.append("Brancing to label "+op+"\n\n");
		    //send message to Andy's stuff here
		    this.SR.set();
	    }
	    else this.tracer.append("Test true; no branch.\n\n");
    }

    //label a line
    public void LABEL(String op)
    {
      //this.labelTable[this.getLabel(op)] = this.lineNum;
	    //write tracing info
	    this.tracer.append(op+" = "+this.lineNum+"\n\n");
    }

    //rotate subroutines and static var's to accompany
    private static int Apos;
    private static int Bpos;
    private static int Cpos;
    public void ROTATE(String a1, String b1, String c1) {

	    int Adeg;
	    int Bdeg;
	    int Cdeg;

	    Adeg = getVarOrInt(a1);
	    Bdeg = getVarOrInt(b1);
	    Cdeg = getVarOrInt(c1);

	    //determine relative orientation of joint A and range-check
	    Apos = Apos + Adeg;
	    if(Apos <= 0) Apos = 0;
	    if(Apos >= 180) Apos = 180;

	    //determine relative orientation of joint B and range-check
	    Bpos = Bpos + Bdeg;
	    if(Bpos <= 0) Bpos = 0;
	    if(Bpos >= 90) Bpos = 90;

	    //Andy's arm movement
	    r.moveControl(Adeg, Bdeg, 0);

	    //determine relative orientation of joint C and range-check
	    Cpos = Cpos + Cdeg;
	    if(Cpos <= 0) Cpos = Cpos + 360;
	    if(Cpos >= 360) Cpos = Cpos - 360;
	    b.move(Cdeg);

	    //write tracing info
	    this.tracer.append("A moved "+Adeg+" deg.\n");
	    this.tracer.append("B moved "+Bdeg+" deg.\n");
	    this.tracer.append("C moved "+Cdeg+" deg.\n");
    }

}
