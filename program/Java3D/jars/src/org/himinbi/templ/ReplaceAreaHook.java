package org.himinbi.templ;

import org.apache.log4j.Logger;

import javax.xml.xpath.XPathFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathConstants;

import org.xml.sax.SAXException;
import org.w3c.dom.Node;
import org.w3c.dom.Text;
import org.w3c.dom.NodeList;
import org.w3c.dom.Element;

import java.io.IOException;
import java.io.File;
import java.io.FileInputStream;

import java.util.Map;

/**
 * Simple hook that takes an xPath to identify nodes and replace them with content from
 * templates.
 */
public class ReplaceAreaHook implements TemplHook {
    static Logger log = Logger.getLogger(ReplaceAreaHook.class);

    String replacableNodes;
    String contentPath;

    public ReplaceAreaHook(HooksProcessor processor) {
        this("//tmpl:area[count(node()) = 0]");
    }

    public ReplaceAreaHook(String nodeCollection) {
        this(nodeCollection, "//tmpl:area[@name='content']");
    }

    public ReplaceAreaHook(String nodeCollection, String contentPath) {
        this.replacableNodes = nodeCollection;
        this.contentPath = contentPath;
    }

    public void init(Element configuration, HooksProcessor processor) {
        TemplateArea area = new TemplateArea(configuration);
        if(area.metadata.get("replace") != null) {
            replacableNodes = area.metadata.get("replace").getTextContent();
        }
        if(area.metadata.get("content") != null) {
            contentPath = area.metadata.get("content").getTextContent();
        }
    }

    public void runHook(HooksProcessor processor) {
        try {
            Map uris = processor.uris;
            XPathExpression replacableNodes = processor.xpath.compile(this.replacableNodes);
            XPathExpression contentPath = processor.xpath.compile(this.contentPath);

            NodeList nodes = (NodeList)replacableNodes.evaluate(processor.container, XPathConstants.NODESET);
            log.debug("Replacing Content: count(" + this.contentPath + ") = " + nodes.getLength());
            for(int i = 0; i < nodes.getLength(); i++) {
                Element node = (Element)nodes.item(i);
                log.debug("Replacing Node: " + (i + 1) + "/" + nodes.getLength() + ": " + node.getAttribute("name"));
                if(node.getAttributeNS((String)uris.get("tmpl"), "iscontent").equals("false")) {
                    log.error(node + " is not content (iscontent = 'false')");
                } else {
                    TemplateArea area = getArea(node, processor);
                    for(Node child : area.content) {
                        node.getParentNode().insertBefore(child, node);
                    }
                    log.debug("Added " + area.content.size() + " node" + (area.content.size() == 1 ? "" : "s"));
                    node.getParentNode().removeChild(node);
                }
            }
        } catch(SAXException saxe) {
            throw new IllegalArgumentException("SAXE: " + saxe.getMessage());
        } catch(XPathExpressionException xpee) {
            throw new IllegalArgumentException("XPEE: " + xpee.getMessage());
        }
    }

    protected TemplateArea getArea(Element insertionPoint, HooksProcessor processor) throws SAXException {
        String name = insertionPoint.getAttribute("name");
        String[] pathes = { processor.basePath + name + ".php.tmpl", processor.basePath + name + ".html.tmpl" };
        for(String path : pathes) {
            if(new File(path).exists()) {
                try {
                    log.debug("Attempting to load " + path + " for " + name);
                    return processor.loadArea(new FileInputStream(path));
                } catch(IOException ioe) {
                    log.error("Error: " + ioe.getMessage());
                }
            }
        }
        throw new IllegalArgumentException("Couldn't find area for: " + name);
    }
}
