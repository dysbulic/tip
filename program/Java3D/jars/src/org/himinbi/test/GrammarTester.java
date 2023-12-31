package org.himinbi.test;

import java.util.Collections;
import java.util.Map;
import java.util.Stack;
import java.util.Set;
import java.util.List;
import java.util.Arrays;
import java.util.Iterator;

import org.xml.sax.SAXException;

import java.io.Reader;
import java.io.PrintStream;
import java.io.FileReader;
import java.io.IOException;

import org.apache.log4j.Logger;

import org.himinbi.tokenizer.StreamTokenizer;
import org.himinbi.tokenizer.TokenizerFactory;
import org.himinbi.parser.Grammar;
import org.himinbi.parser.GrammarGenerator;
import org.himinbi.parser.LLParser;
import org.himinbi.parser.Element;
import org.himinbi.parser.Grammar;
import org.himinbi.parser.ParseEventListener;
import org.himinbi.parser.ParseErrorListener;
import org.himinbi.parser.ParseError;
import org.himinbi.parser.ParseException;

public class GrammarTester
{
    /**
     * Log4j logging category
     */
    static Logger log = Logger.getLogger(GrammarTester.class);

    public static void main(String[] args)
        throws IOException, SAXException, ParseException
    {
        if(args.length < 2)
        {
            System.out.println("Usage:");
            System.out.println("  GrammarTester <ebnf tokenizer file> <ebnf file> [tokenizer file] [file]+");
            System.out.println(" Will create a grammar from the ebnf file using the ebnf");
            System.out.println("  tokenizer and then will tokenize each of the files at the");
            System.out.println("  end using the tokenizer in the third file");
            return;
        }

        log.info("Creating ebnf tokenizer from: " + args[0]);

        StreamTokenizer ebnfTokenizer =
            new TokenizerFactory().getTokenizer(args[0]);
        Reader ebnfReader = new FileReader(args[1]);
        ebnfTokenizer.setInputReader(ebnfReader);

        Grammar grammar = new GrammarGenerator().generateGrammar(ebnfTokenizer);

        grammar.print(System.out);

        LLParser parser = new LLParser();

        Map actions = null;
        try
        {
            parser.setGrammar(grammar);
            actions = parser.getParseTable();
        }
        catch(IllegalArgumentException iae)
        {
            System.err.println(iae.getMessage());
            return;
        }

        System.out.println("Table Entries:");
        Iterator keys = actions.keySet().iterator();
        while(keys.hasNext())
        {
            Element key = (Element)keys.next();
            System.out.println(" For non-terminal: " + key + " -> ");
            Map action = (Map)actions.get(key);
            Iterator elements = action.keySet().iterator();
            while(elements.hasNext())
            {
                String terminal = (String)elements.next();
                System.out.println("   on: '" + terminal + "': " +
                                   action.get(terminal));
            }
        }
        
        if(args.length >= 4)
        {
            parser.addParseEventListener(new ParseEventListener()
                {
                    int depth = 0;
                    String TAB = "  ";
                    PrintStream output = System.out;
                    
                    public void parseStarted()
                    {
                        printTabbed("Parse started");
                    }
                    
                    public void parseFinished()
                    {
                        printTabbed("Parse finished");
                    }
                    
                    public void ruleStarted(String nonterminal)
                    {
                        printTabbed("Rule started: " + nonterminal);
                        depth++;
                    }
                    
                    public void ruleFinished(String nonterminal)
                    {
                        depth--;
                        printTabbed("Rule finished: " + nonterminal);
                    }
                    
                    public void terminal(String terminalClass, String terminalValue)
                    {
                        printTabbed("Terminal: " + 
                                    "(" + terminalClass + "): " +
                                    "'" + terminalValue + "'");
                    }
                    
                    protected void printTabbed(String value)
                    {
                        for(int i = 0; i < depth; i++)
                        {
                            output.print(TAB);
                        }
                        output.println(value);
                    }
                });
            
            parser.addParseErrorListener(new ParseErrorListener()
                {
                    public void parseError(ParseError error)
                    {
                        System.err.println("Error: " + error.getMessage());
                    }
                });
            
            StreamTokenizer fileTokenizer =
                new TokenizerFactory().getTokenizer(args[2]);
            
            for(int i = 3; i < args.length; i++)
            {
                try
                {
                    FileReader reader = new FileReader(args[i]);
                    fileTokenizer.setInputReader(reader);
                    parser.parse(fileTokenizer);
                }
                catch(IOException ioe)
                {
                    System.err.println("IO Error: " + ioe);
                }
            }
        }
    }
}
