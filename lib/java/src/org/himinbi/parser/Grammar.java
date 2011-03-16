package org.himinbi.parser;

import java.util.Collection;
import java.util.Set;
import java.util.List;

/**
 * A grammar is a set of non-terminals and their expansions to lists
 * of terminals and non-terminals.
 */

public interface Grammar {
    //public Nonterminal getStartSymbol();
    public Collection getNonterminals();
    //public Collection getExpansions(Nonterminal nonterminal);

    public void setStartSymbol(Element startSymbol);
    public Element getStartSymbol();
    public void addRule(Rule rule);
    public Collection getRules();
    public Collection getTerminals();
    public Set getTokenTypeSet(String tokenType);
    public Element addRepetition(Element element, int min, int max);
    public Element addOr(Collection elements);
    public Element addGroup(List elements);

}
