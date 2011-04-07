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
public class LoadMetadataHook implements TemplHook {
    static Logger log = Logger.getLogger(LoadMetadataHook.class);

    String templateName = ".templ.metadata";
    List<String> paths = new Vector<String>();

    public LoadMetadataHook(HooksProcessor processor) {
    }

    public void init(Element configuration, HooksProcessor processor) {
        if(configuration.getAttribute("path") != null) {
            addIfExists(configuration.getAttribute("path"));
        }
        for(int i = 0; i < configuration.getChildNodes().getLength(); i++) {
            Node child = configuration.getChildNodes().item(i);
            if(child.getNodeType() == Node.ELEMENT_NODE &&
               child.getNamespaceURI() == HooksProcessor.TEMPL_URI) {
                String tagName = ((Element)child).getNodeName().replaceAll(".+:", "");
                String valName = ((Element)child).getAttribute("name");
                if(tagName.equals("var")) {
                    if(valName.equals("path")) {
                        paths.add(TemplateArea.getVariableString((Element)child));
                    } else if(valName.equals("template name")) {
                        templateName = TemplateArea.getVariableString((Element)child);
                    }
                }
            }
        }
    }
        
    public void addIfExists(String filename) {
        if((new File(filename)).exists()) {
            paths.add(filename);
        } else {
            log.debug("Skipping Config: " + filename);
        }
    }

    public void runHook(HooksProcessor processor) {
        if(paths.size() == 0) {
            log.info("Loading default paths");
            addIfExists(System.getProperty("user.dir") + templateName);
            addIfExists(processor.basePath + templateName);
        }
        for(String path : paths) {
            try {
                log.debug("Loading Configuration: " + path);
                processor.loadArea(path);
            } catch(SAXException saxe) {
                log.error(saxe);
            } catch(IOException ioe) {
                log.error(ioe);
            }
        }
    }
}
