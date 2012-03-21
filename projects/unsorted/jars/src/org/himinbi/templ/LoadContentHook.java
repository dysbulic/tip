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
import java.util.List;
import java.util.Vector;

/**
 */
public class LoadContentHook implements TemplHook {
    static Logger log = Logger.getLogger(LoadContentHook.class);

    public LoadContentHook(HooksProcessor processor) {
    }

    public void init(Element configuration, HooksProcessor processor) {
    }
        
    public void runHook(HooksProcessor processor) {
        try {
            log.info("Merging Content for: " + processor.activeFile);
            processor.loadArea(new FileInputStream(processor.activeFile));
        } catch(IOException ioe) {
            throw new IllegalArgumentException("Merge Error: " + ioe.getMessage());
        } catch(SAXException saxe) {
            throw new IllegalArgumentException("SAX Error: " + saxe.getMessage());
        }
    }
}
