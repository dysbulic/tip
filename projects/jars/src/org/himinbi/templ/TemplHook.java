package org.himinbi.templ;

import org.w3c.dom.Element;

/**
 * A constructor that takes a HooksProcessor nust also be defined
 */
public interface TemplHook {
    public void runHook(HooksProcessor processor);
    public void init(Element configuration, HooksProcessor processor);
}
