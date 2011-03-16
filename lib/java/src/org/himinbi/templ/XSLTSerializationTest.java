package org.himinbi.templ;

import org.apache.log4j.Logger;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  
import javax.xml.parsers.FactoryConfigurationError;  
import javax.xml.parsers.ParserConfigurationException;

import org.xml.sax.SAXException;  
import org.xml.sax.SAXParseException;  
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.DOMException;
import org.xml.sax.helpers.DefaultHandler;
import org.xml.sax.Attributes;

import org.xml.sax.XMLFilter;

import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.sax.TransformerHandler;
import javax.xml.transform.sax.SAXTransformerFactory;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerConfigurationException;

import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.sax.SAXResult;
import javax.xml.transform.stream.StreamResult; 

import java.io.File;
import java.io.IOException;

/**
 * Reads an XML file and serializes it
 */
public class XSLTSerializationTest {
    public static Logger log = Logger.getLogger(XSLTSerializationTest.class);
    
    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length == 0) {
            log.info("Usage: XSLTSerializationTest <xml file>");
            log.info("Serializes the file using [something]");
            System.exit(-1);
        }

        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        factory.setNamespaceAware(true);
        //factory.setValidating(true);   
        Document document = null;
        
        try {
            File file = new File(args[0]);
            DocumentBuilder builder = factory.newDocumentBuilder();
            document = builder.parse(file);

            Document holder = builder.newDocument();
            
            holder.appendChild(holder.createElementNS(HooksProcessor.uris.get("xsl"),
                                                      "stylesheet"));
            Element output = holder.createElementNS(HooksProcessor.uris.get("xsl"),
                                                    "output");
            output.setAttribute("method", "xml");
            holder.getLastChild().appendChild(output);
            Element template = holder.createElementNS(HooksProcessor.uris.get("xsl"),
                                                      "template");
            template.setAttribute("match", "/");
            template.appendChild(holder.adoptNode(document.getDocumentElement().cloneNode(true)));
            holder.getLastChild().appendChild(template);

            DOMSource source = new DOMSource(holder);
            TransformerFactory tFactory = SAXTransformerFactory.newInstance();
            //TransformerHandler serializer = tFactory.newTransformerHandler();
            
            //XMLFilter filter1 = tFactory.newXMLFilter(new SAXSource());

            Transformer transformer = tFactory.newTransformer(source);
            //StreamResult result = new StreamResult(System.out);
            //SAXResult result = new SAXResult(new DebugHandler());
            SAXResult result = new SAXResult();
            transformer.transform(source, result);
        } catch (SAXException saxe) {
            Exception e = saxe;
            if(saxe.getException() != null) {
                e = saxe.getException();
            }
            e.printStackTrace();
        } catch (ParserConfigurationException pce) {
            pce.printStackTrace();
        } catch (IOException ioe) {
            ioe.printStackTrace();
        }
    }
}

class DebugHandler extends DefaultHandler {
    public void startElement(String uri, String localName, String qName, Attributes attributes) {
        XSLTSerializationTest.log.info("Started Element: " + localName);
        for(int i = 0; i < attributes.getLength(); i++) {
            XSLTSerializationTest.log.info("  " + attributes.getLocalName(i) + ": "
                                           + attributes.getValue(i));
        }
    }
    public void processingInstruction(String target, String data) {
        XSLTSerializationTest.log.info("Processing Instruction: " + data);
    }
}
