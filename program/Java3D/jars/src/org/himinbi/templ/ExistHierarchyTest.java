package org.himinbi.templ;

import org.apache.log4j.Logger;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  

import org.exist.xmldb.DatabaseInstanceManager;
import org.xmldb.api.DatabaseManager;
import org.xmldb.api.base.Database;
import org.xmldb.api.base.Collection;
import org.xmldb.api.modules.CollectionManagementService;
import org.xmldb.api.modules.XMLResource;

import org.xml.sax.SAXException;
import org.xml.sax.XMLReader;
import org.xml.sax.helpers.XMLReaderFactory;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import org.exist.xmldb.XmldbURI;
import org.exist.dom.DocumentImpl;
import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

/**
 * Verifies that writing testdir/test will overwrite the test resource.
 */
public class ExistHierarchyTest {
    public static Logger log = Logger.getLogger(ExistHierarchyTest.class);
    
    static String baseURI = "xmldb:exist:///db";
    static String driver = "org.exist.xmldb.DatabaseImpl";

    static String DBUSER = "admin";
    static String DBPASS = "";

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        log.debug("Creating Database: " + driver);

        Class driverClass = Class.forName(driver);
        Database database = (Database)driverClass.newInstance();
        database.setProperty("create-database", "true");
        DatabaseManager.registerDatabase(database);

        Collection root = DatabaseManager.getCollection(baseURI, DBUSER, DBPASS);
        CollectionManagementService mgtService = 
            (CollectionManagementService)root.getService("CollectionManagementService", "1.0");
        Collection collection = DatabaseManager.getCollection(baseURI, DBUSER, DBPASS);
        if(collection != null) {
            log.debug("Retrieved Collection: " + baseURI);
        } else {
            collection = mgtService.createCollection(XmldbURI.xmldbUriFor(baseURI).getCollectionPath());
            log.debug("Created Collection: " + baseURI);
        }
        
        String[] resourceNames = { "test", "testdir/test" };
        for(String resourceName : resourceNames) {
            XMLResource document = (XMLResource)collection.getResource(resourceName);
            if(document != null) {
                log.debug("Retreived Document: " + document.getId());
            } else {
                document = (XMLResource)collection.createResource(resourceName, "XMLResource");
                log.debug("Created Document: " + document.getId());
            }
            Document doc = DocumentBuilderFactory.newInstance().newDocumentBuilder().newDocument();
            Element fileNode = doc.createElementNS(HooksProcessor.uris.get("templ"), "file");
            fileNode.setAttribute("name", resourceName);
            doc.appendChild(fileNode);
            document.setContentAsDOM(doc);
            try {
                collection.storeResource(document);
                log.debug("Stored Document: " + document.getId());
            } catch(XMLDBException xmldbe) {
                log.error("Failed to Store " + document.getId() + " (" + xmldbe.getMessage() + ")");
            }
        }

        for(String resourceName : resourceNames) {
            XMLResource document = (XMLResource)collection.getResource(resourceName);
            if(document == null) {
                log.debug("Second Pass: " + resourceName + " not found");
            } else {
                log.debug("Second Pass: " + resourceName + " => " +
                          ((Document)document.getContentAsDOM()).getDocumentElement().getAttribute("name"));
            }
        }

        log.debug("Child Collections: " + collection.getName());
        for(String child : collection.listChildCollections()) {
            log.debug("  " + child);
        }
        log.debug("Child Resources: " + collection.getName());
        for(String child : collection.listResources()) {
            log.debug("  " + child);
        }

        try {
            DatabaseInstanceManager manager =
                (DatabaseInstanceManager)root.getService("DatabaseInstanceManager", "1.0"); 
            manager.shutdown();
        } catch(XMLDBException xmldbe) {
            log.error(xmldbe.getMessage());
        }
    }
}
