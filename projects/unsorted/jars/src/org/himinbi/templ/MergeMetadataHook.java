package org.himinbi.templ;

import org.apache.log4j.Logger;

import javax.xml.xpath.XPathFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathConstants;

import org.xml.sax.SAXException;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.Text;
import org.w3c.dom.NodeList;
import org.w3c.dom.Element;

import java.io.IOException;
import java.io.File;
import java.io.FileInputStream;

import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.dom.DOMResult;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerConfigurationException;

import java.util.Map;

/**
 */
public class MergeMetadataHook implements TemplHook {
    static Logger log = Logger.getLogger(MergeMetadataHook.class);


    public MergeMetadataHook(HooksProcessor processor) {
    }

    public void init(Element configuration, HooksProcessor processor) {
    }

    public void runHook(HooksProcessor processor) {
        try {
            String varsPath = "//tmpl:var[count(node()) = 0 and not(@value)]";
            XPathExpression varsExpression = processor.xpath.compile(varsPath);
            NodeList nodes;
            do {
                nodes = (NodeList)varsExpression.evaluate(processor.container, XPathConstants.NODESET);
                for(int i = 0; i < nodes.getLength(); i++) {
                    Element node = ((Element)nodes.item(i));
                    String name = node.getAttribute("name");
                    if(processor.hasMetadata(name)) {
                        NodeList var = processor.metadata.get(name).getChildNodes();
                        log.debug("Inserting Var: " + name + ": " + var.getLength());
                        for(int j = 0; j < var.getLength(); j++) {
                            Node nodeCopy = node.getOwnerDocument().adoptNode(var.item(j).cloneNode(true));
                            node.getParentNode().insertBefore(nodeCopy, node);
                        }
                    } else {
                        log.error("No metadata for: " + name);
                    }
                    node.getParentNode().removeChild(node);
                }
            } while(nodes.getLength() > 0);
        } catch(XPathExpressionException xpee) {
            log.error("Bad Path: " + xpee.getMessage());
        }
    }
}
