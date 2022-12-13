package org.himinbi.parser;

import java.io.Reader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Stack;
import java.util.Arrays;
import java.util.Collection;
import java.util.Collections;
import java.util.Iterator;
import java.util.List;

import org.apache.log4j.Level;
import org.apache.log4j.Logger;

import org.himinbi.tokenizer.Token;
import org.himinbi.tokenizer.TokenStream;

/**
 * Recursive descent parser to generate ebnf grammar
 */
public class GrammarGenerator
{
    /**
     * Log4j logging category
     */
    static Logger log = Logger.getLogger(GrammarGenerator.class);
    
    TokenStream tokens;
    Stack elementMemory = new Stack();
    Stack memoryMemory = new Stack();
    Grammar grammar;

    public synchronized Grammar generateGrammar(TokenStream tokens)
    {
        this.tokens = tokens;
        grammar = new BasicGrammar();
        ebnf();
        return grammar;
    }

    /**
     * ebnf = rule+;
     */
    protected void ebnf()
    {
        while(tokens.hasNext())
        {
            rule();
        }
    }
    
    /**
     * rule = 'nonterminal', '=', element list?, ';';
     */
    protected void rule()
    {
        Token nextToken = tokens.nextToken();
        if(!nextToken.getType().equals(Element.NONTERMINAL_TYPE))
        {
            throw new IllegalStateException("Expecting " +
                                            Element.NONTERMINAL_TYPE +
                                            " got " + nextToken);
        }

        Rule currentRule = new Rule(new Element(Element.NONTERMINAL_TYPE,
                                                nextToken.getValue()));
        
        nextToken = tokens.nextToken();
        if(!nextToken.getType().equals("="))
        {
            throw new IllegalStateException("Expecting = got " + nextToken);
        }

        ArrayList elements = new ArrayList();
        if(!tokens.nextTokenType().equals(";"))
        {
            elementList();
        }
        else
        {
            elementMemory.push(Element.EMPTY_TERMINAL);
        }
        currentRule.setExpansion(elementMemory);
        elementMemory = new Stack();

        nextToken = tokens.nextToken();
        if(!nextToken.getType().equals(";"))
        {
            throw new IllegalStateException("Expecting ; got " + nextToken);
        }
        grammar.addRule(currentRule);

        if(grammar.getStartSymbol() == null)
        {
            // The start symbol is the nonterminal starting the first rule
            grammar.setStartSymbol(currentRule.getProduct());
        }
    }

    /**
     * element list = element, (',' | '|', element)*;
     */
    protected void elementList()
    {
        boolean done = false;
        List orList = new ArrayList();
        while(!done)
        {
            element();
            String nextTokenType = tokens.nextTokenType();
            
            // An option marker means the last generated element
            //  is part of an or group
            if(nextTokenType.equals("|"))
            {
                orList.add(elementMemory.pop());
                log.debug("Added to options: " + orList.get(orList.size() - 1));
            }
            // If it is not then check to see if any have been
            //  collected already. If so then the last element
            //  generated is the last one in that group
            else
            {
                if(orList.size() > 0)
                {
                    orList.add(elementMemory.pop());
                    log.debug("Finishing options: " + orList.get(orList.size() - 1));
                    elementMemory.push(grammar.addOr(orList));
                    orList.clear();
                }
            }

            // The next token is a separator
            if(nextTokenType.equals(",") || nextTokenType.equals("|"))
            {
                // eat the token
                tokens.nextToken();
            }
            // The list is done
            else
            {
                done = true;
            }
        }
    }

    /**
     * element = token, repetition?;
     */
    protected void element()
    {
        token();
        
        String nextTokenType = tokens.nextTokenType();
        if(nextTokenType.equals("{") ||
           nextTokenType.equals("*") ||
           nextTokenType.equals("+") ||
           nextTokenType.equals("?"))
        {
            repetition();
        }
    }
    
    /**
     * token = ('terminal' | 'nonterminal' | group);
     */
    protected void token()
    {
        String nextTokenType = tokens.nextTokenType();
        if(nextTokenType.equals(Element.TERMINAL_TYPE))
        {
            elementMemory.push(new Element(Element.TERMINAL_TYPE,
                                           tokens.nextToken().getValue()));
        }
        else if(nextTokenType.equals(Element.NONTERMINAL_TYPE))
        {
            elementMemory.push(new Element(Element.NONTERMINAL_TYPE,
                                           tokens.nextToken().getValue()));
        }
        else
        {
            group();
        }
    }    

    /**
     * group = '(', element list, ')';
     */
    protected void group()
    {
        /* A group is added as a single anonymous nonterminal in
         *  the current production
         */
        Token nextToken = tokens.nextToken();
        if(!nextToken.getType().equals("("))
        {
            throw new IllegalStateException("Expecting: ( got " + nextToken);
        }

        memoryMemory.push(elementMemory);
        elementMemory = new Stack();
        elementList();
        
        Element group = grammar.addGroup(elementMemory);
        elementMemory = (Stack)memoryMemory.pop();
        elementMemory.push(group);

        nextToken = tokens.nextToken();
        if(!nextToken.getType().equals(")"))
        {
            throw new IllegalStateException("Expecting: ) got " + nextToken);
        }
    }

    /*(
     *  (* {m, n} means repeated at least m times, but not more tham n (n >= m)
     *   * {m}    means repeated at least m times with no upper limit
     *   * ?      is the same as {0, 1}
     *   * *      is the same as {0}
     *   * +      is the same as {1}
     *   *)
     * repetition = '{', 'integer'?, (',', 'integer')?, '}';
     * repetition = '?' | '*' | '+';
     */
    protected void repetition()
    {
        int min = 0;
        int max = Integer.MAX_VALUE;

        Token nextToken = tokens.nextToken();
        if(nextToken.getType().equals("*"))
        {
            // defaults
        }
        else if(nextToken.getType().equals("+"))
        {
            min = 1;
        }
        else if(nextToken.getType().equals("?"))
        {
            max = 1;
        }
        else if(nextToken.getType().equals("{"))
        {
            nextToken = tokens.nextToken();
            if(nextToken.getType().equals("integer"))
            {
                min = Integer.parseInt(nextToken.getValue());
                nextToken = tokens.nextToken();
            }
            if(nextToken.getType().equals(","))
            {
                nextToken = tokens.nextToken();
            }
            if(nextToken.getType().equals("integer"))
            {
                max = Integer.parseInt(nextToken.getValue());
                nextToken = tokens.nextToken();
            }
            if(!nextToken.getType().equals("}"))
            {
                throw new IllegalStateException("Expected } got " + nextToken);
            }
        }
        else
        {
            throw new IllegalStateException("Expected repetition got " +
                                            nextToken);
        }
        elementMemory.push(grammar.addRepetition((Element)elementMemory.pop(),
                                                 min, max));
    }
}
