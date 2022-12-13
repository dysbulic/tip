package org.himinbi.templ;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.io.IOException;
import java.util.Map;
import java.util.List;
import java.util.Vector;
import java.util.HashMap;
import org.apache.log4j.Logger;

public class TemplateArea {
    static Logger log = Logger.getLogger(TemplateArea.class);

    List<Node> content = new Vector<Node>();
    Map<String, Element> metadata = new HashMap<String, Element>();
    List<Element> hooks = new Vector<Element>();

    public TemplateArea(Node template) {
        int length = template.getChildNodes().getLength();
        for(int i = 0; i < length; i++) {
            Node node = template.getChildNodes().item(i);
            if(node.getNamespaceURI() != HooksProcessor.TEMPL_URI) {
                content.add(node);
            } else if(node.getNodeType() == Node.ELEMENT_NODE) {
                String name = ((Element)node).getTagName().replaceAll(".+:", "");
                if(name.equals("var")) {
                    String value = ((Element)node).getAttribute("value");
                    // vars with no children or value are to be replaced with their expansion later
                    if(node.getChildNodes().getLength() == 0 && value == null) {
                        content.add(node);
                    } else {
                        name = ((Element)node).getAttribute("name");
                        if(value != null) {
                            node.appendChild(node.getOwnerDocument().createTextNode(value));
                        }
                        metadata.put(name, (Element)node);
                    }
                } else if(name.equals("hook")) {
                    hooks.add((Element)node);
                } else {
                    log.error("Unknown template tag: " + name);
                    content.add(node);
                }
            }
        }
        log.debug("Loaded " + length + " node" + (length != 1 ? "s" : "") +
                  " (m:" + metadata.size() + " / h:" + hooks.size() +
                  " / c:" + content.size() + ")");
    }

    /**
     * Extracts a String definition from a template variable
     */
    public static String getVariableString(Element varDef) {
        if(varDef.getAttribute("value") != null) {
            return varDef.getAttribute("value");
        }
        if(varDef.getChildNodes().getLength() > 0) {
            return varDef.getTextContent();
        }
        return null;
    }

}
