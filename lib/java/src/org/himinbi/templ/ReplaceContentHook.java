package org.himinbi.templ;

import javax.xml.xpath.XPathFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathConstants;

import org.xml.sax.SAXException;  
import org.w3c.dom.Element;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;

/**
 * Simple hook that takes an xPath to identify nodes and replace them with content from
 * templates.
 */
public class ReplaceContentHook extends ReplaceAreaHook {
    File content;

    public ReplaceContentHook(HooksProcessor processor) {
        super(processor);
    }

    public ReplaceContentHook(String content) {
        this(new File(content));
    }

    public ReplaceContentHook(File content) {
        super("//tmpl:area[@name='content']");
        this.content = content;
    }
    
    public void init(Element configuration, HooksProcessor processor) {}

    protected TemplateArea getArea(Element insertionPoint, HooksProcessor processor) throws SAXException {
        try {
            if(content == null) {
                content = processor.activeFile;
            }
            log.debug("Loading Content from " + content.getName());
            return processor.loadArea(new FileInputStream(content));
        } catch(IOException ioe) {
            throw new IllegalArgumentException(ioe.getMessage());
        }
    }
}
