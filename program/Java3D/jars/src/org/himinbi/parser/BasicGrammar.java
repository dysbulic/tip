package org.himinbi.parser;

import java.util.Hashtable;
import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Collection;
import java.util.Collections;

import java.io.OutputStream;
import java.io.PrintWriter;

/**
 * The grammar class represents a grammar used to generate a language.
 * This is a computer science grammar (ala Noam Chomsky) where a language
 * is a set of combinations of strings.
 *
 * A grammar consists of a set of nonterminals. Each nonterminal has
 * one or more completions. A completion is a list of zero or more
 * terminals and nonterminals which that nonterminal can be expanded
 * to.
 *
 */
public class BasicGrammar implements Grammar
{
    ArrayList rules = new ArrayList();
    Element startSymbol;
    int generatedSymbolCount = 0;
    Set terminals;
    Set nonterminals;
    boolean finalized = false;

    public void setStartSymbol(Element startSymbol)
    {
        this.startSymbol = startSymbol;
    }

    public Element getStartSymbol()
    {
        return startSymbol;
    }

    public void addRule(Rule rule)
    {
        finalized = false;
        rules.add(rule);
    }

    public Collection getRules()
    {
        terminals = null;
        nonterminals = null;
        return rules;
    }

    public Collection getTerminals()
    {
        if(terminals == null)
        {
            terminals = getTokenTypeSet(Element.TERMINAL_TYPE);
        }
        return terminals;
    }

    public Collection getNonterminals()
    {
        if(nonterminals == null)
        {
            nonterminals = getTokenTypeSet(Element.NONTERMINAL_TYPE);
        }
        return nonterminals;
    }

    public Set getTokenTypeSet(String tokenType)
    {
        Set set = new HashSet();
        Iterator rules = this.rules.iterator();
        while(rules.hasNext())
        {
            Rule rule = (Rule)rules.next();

            Element product = rule.getProduct();
            if(product.getType().equals(tokenType))
            {
                set.add(product);
            }

            Iterator expansion = rule.getExpansion().iterator();
            while(expansion.hasNext())
            {
                Element next = (Element)expansion.next();
                if(next.getType().equals(tokenType))
                {
                    set.add(next);
                }
            }
        }
        return set;
    }

    /**
     * Adds the element to the grammar repeated at least min times,
     * but no more than max. If max = Integer.MAX_VALUE, infinite
     * repetitions will be allowed.
     *
     * The method returns a non-terminal that will expand to the
     * requested repetitions.
     */
    public Element addRepetition(Element element, int min, int max)
    {
        Element head = new Element(Element.NONTERMINAL_TYPE,
                                   "repetition head [" + (generatedSymbolCount++) + "]",
                                   true);
        Element currentElement = head;
        for(int i = 0; i < min; i++)
        {
            Element nextElement = new Element(Element.NONTERMINAL_TYPE,
                                              "anonymous [" + (generatedSymbolCount++) + "]",
                                              true);
            ArrayList expansion = new ArrayList();
            expansion.add(element);
            expansion.add(nextElement);
            addRule(new Rule(currentElement, expansion));
            currentElement = nextElement;
        }

        List emptyList = Collections.singletonList(Element.EMPTY_TERMINAL);
        if(max < Integer.MAX_VALUE)
        {
            for(int i = min; i < max - 1; i++)
            {
                Element nextElement = new Element(Element.NONTERMINAL_TYPE,
                                                  "anonymous [" + (generatedSymbolCount++) + "]",
                                                  true);
                ArrayList expansion = new ArrayList();
                expansion.add(element);
                expansion.add(nextElement);
                addRule(new Rule(currentElement, expansion));
                addRule(new Rule(currentElement, emptyList));
                currentElement = nextElement;
            }
            addRule(new Rule(currentElement, Collections.singletonList(element)));
            addRule(new Rule(currentElement, emptyList));
        } else {
            ArrayList expansion = new ArrayList();
            expansion.add(element);
            expansion.add(currentElement);
            addRule(new Rule(currentElement, expansion));
            addRule(new Rule(currentElement, emptyList));
        }
        return head;
    }

    public Element addOr(Collection elements)
    {
        Element head = new Element(Element.NONTERMINAL_TYPE,
                                   "or head [" + (generatedSymbolCount++) + "]",
                                   true);
        Iterator elementList = elements.iterator();
        while(elementList.hasNext())
        {
            addRule(new Rule(head,
                             Collections.singletonList(elementList.next())));
        }
        return head;
    }

    public Element addGroup(List elements)
    {
        Element head = new Element(Element.NONTERMINAL_TYPE,
                                   "group head [" + (generatedSymbolCount++) + "]",
                                   true);
        addRule(new Rule(head, elements));
        return head;
    }

    public void print(OutputStream outputStream)
    {
        Iterator rules = getRules().iterator();
        PrintWriter writer = new PrintWriter(outputStream);
        while(rules.hasNext())
        {
            ((Rule)rules.next()).print(writer);
            writer.println();
        }
        writer.flush();
    }
}
