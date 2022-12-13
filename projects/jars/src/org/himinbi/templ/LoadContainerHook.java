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
 * Loads the container template. The container is the outermost content, so only one may be loaded at a time.
 */
public class LoadContainerHook implements TemplHook {
    static Logger log = Logger.getLogger(LoadContainerHook.class);

    String contentFilename = "default.html.tmpl";
    
    public LoadContainerHook(HooksProcessor processor) {
    }

    public void init(Element configuration, HooksProcessor processor) {
    }

    public void runHook(HooksProcessor processor) {
        try {
            log.debug("Setting Template: " + contentFilename);
            processor.setTemplate(processor.basePath + contentFilename);
        } catch(IOException ioe) {
            log.error("IO Error: " + ioe.getMessage());
        }
    }
}
