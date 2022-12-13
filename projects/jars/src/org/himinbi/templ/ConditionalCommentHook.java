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
import java.io.StringWriter;
import java.io.File;
import java.io.FileInputStream;

import org.w3c.dom.ls.LSOutput;

import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.dom.DOMResult;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerConfigurationException;

import java.util.Map;

/**
 * Creates IE conditional comments from <tmpl:condcomment> tags.
 */
public class ConditionalCommentHook implements TemplHook {
    static Logger log = Logger.getLogger(ConditionalCommentHook.class);

    static String comments = "//tmpl:condcomment";

    {
    }

    public ConditionalCommentHook(HooksProcessor processor) {
    }

    public void init(Element configuration, HooksProcessor processor) {
    }

    // ToDo: Handle [if !IE] correctly
    public void runHook(HooksProcessor processor) {
        try {
            XPathExpression comments = processor.xpath.compile(this.comments);

            NodeList nodes = (NodeList)comments.evaluate(processor.container, XPathConstants.NODESET);
            log.debug("Replacing Comments: count(" + this.comments + ") = " + nodes.getLength());
            for(int i = 0; i < nodes.getLength(); i++) {
                Element node = (Element)nodes.item(i);
                log.debug("Replacing Comment: " + (i + 1) + "/" + nodes.getLength() + ": " + node.getAttribute("cond"));
                StringWriter out = new StringWriter();
                out.write("[" + node.getAttribute("cond") + "]>\n");
                LSOutput output = processor.lsFeature.createLSOutput();
                output.setCharacterStream(out);
                while(node.getChildNodes().getLength() > 0) {
                    Node child = node.getChildNodes().item(0);
                    node.removeChild(child);
                    processor.serializer.write(child, output);
                }
                out.write("\n<![endif]");
                Node comment = node.getOwnerDocument().createComment(out.toString());
                node.getParentNode().insertBefore(comment, node);
                node.getParentNode().removeChild(node);
            }
        } catch(XPathExpressionException xpee) {
            throw new IllegalArgumentException("XPEE: " + xpee.getMessage());
        }
    }
}
