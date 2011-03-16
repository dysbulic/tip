package org.himinbi.templ;

import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;

import java.util.Properties;
import java.util.Iterator;
import java.util.Date;
import java.text.DateFormat;

import java.io.File;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.IOException;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  

import org.exist.xmldb.DatabaseInstanceManager;
import org.xmldb.api.DatabaseManager;
import org.xmldb.api.base.Database;
import org.xmldb.api.base.Collection;
import org.xmldb.api.base.ResourceSet;
import org.xmldb.api.base.ResourceIterator;
import org.xmldb.api.modules.CollectionManagementService;
import org.xmldb.api.modules.XMLResource;
import org.xmldb.api.modules.XPathQueryService;

import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.sax.TransformerHandler;
import javax.xml.transform.sax.SAXTransformerFactory;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerConfigurationException;
import javax.xml.transform.stream.StreamSource;

import org.xml.sax.InputSource;
import org.xml.sax.SAXException;
import org.xml.sax.XMLReader;
import org.xml.sax.helpers.XMLReaderFactory;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import org.apache.tools.ant.types.Resource;
import org.apache.tools.ant.Project;
import org.apache.tools.ant.types.FileSet;

import org.exist.xmldb.XmldbURI;
import org.exist.dom.DocumentImpl;
import org.exist.dom.ElementImpl;
import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

import org.w3c.dom.Document;
//import org.jdom.input.SAXHandler;

/**
 * Recursively loads a directory tree of files into an Exist database.
 */
public class ExistXSLTTest {
    public static Logger log = Logger.getLogger(ExistXSLTTest.class);

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length < 2) {
            log.error("Usage: ExistTreeTest <directory name> <xslt stylesheet>");
            System.exit(-1);
        }
        
        DocumentSet docs = new DocumentSet(args[0]);
        
        //try {
            TransformerFactory tFactory = SAXTransformerFactory.newInstance();
            Transformer transformer = tFactory.newTransformer(new StreamSource(new File(args[1])));

            /*
            ResourceSet result = service.query(query);
            log.debug("Running Query: " + query + " (" + result.getSize() + ")");
            ResourceIterator i = result.getIterator();
            while(i.hasMoreResources()) {
                XMLResource resource = (XMLResource)i.nextResource();
                log.debug(resource.getResourceType() + ": " +
                          resource.getParentCollection().getName() + "/" + resource.getId());
                if(resource instanceof DocumentImpl) {
                    DocumentImpl doc = (DocumentImpl)resource.getContentAsDOM().getOwnerDocument();
                    log.debug(doc.getCollection().getURI() + " / " + resource.getDocumentId());
                } else {
                    log.debug("  Content: " + resource.getContent());
                }
            }
            */
            //} catch(XMLDBException xmldbe) {
            //log.error("XMLDBE: " + xmldbe.getMessage());
            //}
        
        docs.shutdown();
    }
}
