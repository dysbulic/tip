package org.himinbi.templ;

import org.apache.log4j.Logger;

import java.util.Map;
import java.util.Stack;
import java.util.HashMap;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 * Holds the metadata document and allows for scoping
 */
public class TemplMetadata {
    static Logger log = Logger.getLogger(TemplMetadata.class);

    Stack<Document> docHistory = new Stack<Document>();
    Stack<HashMap<String, Element>> eltHistory = new Stack<HashMap<String, Element>>();
    Document doc;
    HashMap<String, Element> elements = new HashMap<String, Element>();

    public TemplMetadata() {
    }

    public Document getMetadata() {
        return doc;
    }

    /**
     * Establishes a new scope
     */
    public synchronized void openScope() {
        docHistory.push(doc);
        doc = (Document)doc.cloneNode(true);
        eltHistory.push(elements);
        elements = (HashMap<String, Element>)elements.clone();
    }

    /**
     * Restores the previous scope
     */
    public synchronized void closeScope() {
        doc = docHistory.pop();
        elements = eltHistory.pop();
    }

    /**
     * Merges a metadata document into the current metadata structure
     */
    public void mergeMetadata(Document metadata) {
        log.debug("Loading: " + metadata.getDocumentElement().getTagName());
        if(doc == null) {
            doc = (Document)metadata.cloneNode(true);
        } else {
            NodeList childNodes = metadata.getDocumentElement().getChildNodes();
            for(int i = 0; i < childNodes.getLength(); i++) {
                if(childNodes.item(i).getNodeType() == Node.ELEMENT_NODE) {
                    Element child = (Element)childNodes.item(i);
                    child = (Element)doc.importNode(child, true);
                    Element existing = elements.get(child.getTagName());
                    if(existing != null && child.getAttribute("additive") == "true") {
                        log.debug("Appending Metadata: " + child.getTagName() +
                                  " (" + existing.getChildNodes().getLength() +
                                  " + " + child.getChildNodes().getLength() + ")");
                        while(child.getChildNodes().getLength() > 0) {
                            existing.appendChild(child.getChildNodes().item(0));
                        }
                    } else {
                        if(existing != null) {
                            log.debug("Replacing Metadata: " + child.getTagName() +
                                      " (" + child.getChildNodes().getLength() + ")");
                            doc.replaceChild(child, existing);
                        } else {
                            log.debug("Adding Metadata: " + child.getTagName() +
                                      " (" + child.getChildNodes().getLength() + ")");
                            doc.getDocumentElement().appendChild(child);
                        }
                    }
                }
            }
        }
    }
}
