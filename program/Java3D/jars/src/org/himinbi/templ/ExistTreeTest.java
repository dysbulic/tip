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
public class ExistTreeTest {
    public static Logger log = Logger.getLogger(ExistTreeTest.class);
    
    public static String DEFAULT_QUERY = "count(//*)";
    public static String[] excludes = { "**/.templ.cache/*" };

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length == 0) {
            log.error("Usage: ExistTreeTest <directory name> [query]");
            System.exit(-1);
        }
        
        DocumentSet docs = new DocumentSet(args[0]);
        
        xpathTest(docs.rootCollection, 
                  (args.length > 1) ? args[1] : DEFAULT_QUERY);

        docs.shutdown();
    }

    public static void xpathTest(Collection collection, String query) throws XMLDBException {
        XPathQueryService service = (XPathQueryService)collection.getService("XPathQueryService", "1.0");
        for(String abbr : HooksProcessor.uris.keySet()) {
            log.debug("Added Namespace: " + abbr + ": " + HooksProcessor.uris.get(abbr));
            service.setNamespace(abbr, HooksProcessor.uris.get(abbr));
        }

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
    }

}
