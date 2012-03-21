package org.himinbi.parser;

import java.util.ArrayList;
import java.util.List;
import java.util.Collections;
import java.util.Iterator;

import java.io.StringWriter;
import java.io.PrintWriter;
import java.io.OutputStream;

/**
 * Represents a grammar rule.
 */
public class Rule {
    Element product;
    List expansion;

    public Rule(Element product) {
        this(product, Element.EMPTY_TERMINAL);
    }

    public Rule(Element product, Element expansion) {
        this(product, Collections.singletonList(expansion));
    }

    public Rule(Element product, List expansion) {
        this.product = product;
        this.expansion = expansion;
    }

    public Element getProduct() {
        return product;
    }

    public List getExpansion() {
        return expansion;
    }

    public void setExpansion(List expansion)
    {
        this.expansion = expansion;
    }

    public void print(OutputStream outputStream) {
        print(new PrintWriter(outputStream));
    }

    public void print(PrintWriter out) {
        out.print(product + " = ");
        Iterator elements = expansion.iterator();
        while(elements.hasNext()) {
            Element next = (Element)elements.next();
            if(next != Element.EMPTY_TERMINAL) {
                out.print(next);
            }
            if(elements.hasNext()) {
                out.print(", ");
            }
        }
        out.print(";");
        out.flush();
    }

    public String toString() {
        StringWriter writer = new StringWriter();
        print(new PrintWriter(writer));
        return writer.toString();
    }
}
