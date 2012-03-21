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
 * Simple hook that takes an xPath to identify nodes and replace them with content from
 * templates.
 */
public class XSLTHook implements TemplHook {
    static Logger log = Logger.getLogger(XSLTHook.class);

    final static String XSLT_NS = "http://www.w3.org/1999/XSL/Transform";
    static TransformerFactory factory = TransformerFactory.newInstance();

    Transformer transformer;
    
    {
        log.debug("TranformerFactory: " + factory.getClass().getName());
    }

    public XSLTHook(HooksProcessor processor) {
        processor.uris.put("xsl", XSLT_NS);
    }

    public void init(Element configuration, HooksProcessor processor) {
        TemplateArea area = new TemplateArea(configuration);
        if(area.content.size() > 0) {
            Document doc = processor.builder.newDocument();
            doc.appendChild(doc.createElementNS(XSLT_NS, "stylesheet"));
            for(String namespace : processor.uris.keySet()) {
                ((Element)doc.getLastChild()).setAttribute("xmlns:" + namespace,
                                                           processor.uris.get(namespace));
            }

            // By default copy all content into the new document
            if(configuration.getAttribute("copynodes") != "false") {
                Element nodeCopy = doc.createElementNS(XSLT_NS, "template");
                doc.getFirstChild().appendChild(nodeCopy);
                nodeCopy.setAttribute("match", "node()");
                Element copy = doc.createElementNS(XSLT_NS, "copy");
                nodeCopy.appendChild(copy);
                copy.appendChild(doc.createElementNS(XSLT_NS, "apply-templates"));
                ((Element)copy.getLastChild()).setAttribute("select", "@*|node()");
                
                Element attrCopy = doc.createElementNS(XSLT_NS, "template");
                doc.getFirstChild().appendChild(attrCopy);
                attrCopy.setAttribute("match", "@*");
                attrCopy.appendChild(doc.createElementNS(XSLT_NS, "copy"));
            }

            Element root = area.content.get(0).getOwnerDocument().createElementNS(XSLT_NS, "stylesheet");
            for(Node child : area.content) {
                //root.appendChild(child);
                doc.getDocumentElement().appendChild(doc.importNode(child, true));
            }
            log.debug("Processing: " + doc);
            try {
                DOMSource source = new DOMSource(doc);
                transformer = factory.newTemplates(source).newTransformer();
            } catch (TransformerConfigurationException tce) {
                log.error("Error Creating Transformer: " + tce.getMessage());
            }
        }
    }

    public void runHook(HooksProcessor processor) {
        try {
            log.debug("Transforming: " + transformer);
            DOMResult result = new DOMResult();
            transformer.transform(new DOMSource(processor.container), result);
            processor.container.removeChild(processor.container.getDocumentElement());
            processor.container.appendChild(processor.container.importNode(((Document)result.getNode()).getDocumentElement(), true));;
        } catch(TransformerException te) {
            log.error("Transform Error: " + te.getMessage());
        }
    }
}
