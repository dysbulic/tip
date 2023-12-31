package org.himinbi.templ;

import org.apache.log4j.Logger;

import javax.xml.xpath.XPathFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathConstants;

import org.xml.sax.SAXException;  
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.w3c.dom.Element;

import java.io.IOException;
import java.io.File;

import java.util.Map;
import java.util.Hashtable;

/**
 * Alters relative pathes in a document to refer to refer to subdirectories as necessary.
 */
public class RelativePathsHook implements TemplHook {
    static Logger log = Logger.getLogger(RelativePathsHook.class);

    Map<String, String> replacementPaths;
    String absolutePaths = "^(#|([a-zA-Z]+://)|/).*";

    public RelativePathsHook(HooksProcessor processor) {
        replacementPaths = new Hashtable();
        addReplacement("//html:a", "href");
        addReplacement("//html:img", "src");
        addReplacement("//html:iframe", "src");
        addReplacement("//html:script", "src");
        addReplacement("//html:link", "href");
        addReplacement("//html:object", "data");
    }

    public RelativePathsHook(Map<String, String> replacementPaths) {
        this.replacementPaths = replacementPaths;
    }

    public void addReplacement(String path, String attr) {
        replacementPaths.put(path, attr);
    }

    public void init(Element configuration, HooksProcessor processor) {}

    public void runHook(HooksProcessor processor) {
        try {
            String basePath = processor.currentPath;
            if(!basePath.startsWith(processor.basePath)) {
                log.error(basePath + " not a descendant of " + processor.basePath);
            } else {
                basePath = basePath.substring(processor.basePath.length());
            }
            if(basePath.length() > 0) {
                String relativePath = basePath.replaceAll("[^/]+", "..");
                log.debug(processor.basePath + basePath + " => " + relativePath);
                for(String path : replacementPaths.keySet()) {
                    XPathExpression replacementNodes = processor.xpath.compile(path);
                    NodeList nodes = (NodeList)replacementNodes.evaluate(processor.container, XPathConstants.NODESET);
                    for(int i = 0; i < nodes.getLength(); i++) {
                        Element node = (Element)nodes.item(i);
                        String attr = node.getAttribute(replacementPaths.get(path));
                        if(attr.matches(absolutePaths)) {
                            log.debug("Absolute Path: " + attr);
                        } else {
                            node.setAttribute(replacementPaths.get(path), relativePath + attr);
                            log.debug("Set: " + node.getAttribute(replacementPaths.get(path)) + " = " + relativePath + attr);
                        }
                    }
                }
            }

        } catch(Exception ioe) {
            log.error(ioe.getMessage());
        }
    }
}
