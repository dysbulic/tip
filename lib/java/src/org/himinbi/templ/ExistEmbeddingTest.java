package org.himinbi.templ;

import org.apache.log4j.Logger;
import org.apache.log4j.PropertyConfigurator;

import java.util.Properties;

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

import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

import org.w3c.dom.Document;
//import org.jdom.input.SAXHandler;

public class ExistEmbeddingTest {
    public static Logger log = Logger.getLogger(ExistEmbeddingTest.class);
    
    public static String DEFAULT_QUERY = "count(//*)";

    static String baseURI = "xmldb:exist:///db";
    static String driver = "org.exist.xmldb.DatabaseImpl";
    static String collectionName = "documents";

    static String DBUSER = "admin";
    static String DBPASS = "";

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length == 0) {
            log.error("Usage: ExistEmbeddingTest <filename> [query]");
            System.exit(-1);
        }
        Class driverClass = Class.forName(driver);
        Database database = (Database)driverClass.newInstance();
        database.setProperty("create-database", "true");
        DatabaseManager.registerDatabase(database);

        Collection col = DatabaseManager.getCollection(baseURI + "/" + collectionName, DBUSER, DBPASS);
        if(col != null) {
            log.debug("Retrieved Collection: " + collectionName);
        } else {
            log.debug("Creating Collection: " + collectionName);

            Collection root = DatabaseManager.getCollection(baseURI, DBUSER, DBPASS);
            CollectionManagementService mgtService = 
                (CollectionManagementService)root.getService("CollectionManagementService", "1.0");
            col = mgtService.createCollection(collectionName);
        }

        XMLResource document = (XMLResource)col.getResource(args[0]);
        if(document != null) {
            log.debug("Retreived Document: " + document.getId());
        } else {
            document = (XMLResource)col.createResource(args[0], "XMLResource");
            
            StringBuffer fileData = new StringBuffer();
            BufferedReader reader = new BufferedReader(new FileReader(args[0]));
            char[] buf = new char[1024];
            int numRead = 0;
            while((numRead = reader.read(buf)) != -1){
                fileData.append(String.valueOf(buf, 0, numRead));
            }
            reader.close();
            document.setContent(fileData);
            
            log.debug("Storing Document: " + document.getId());
            col.storeResource(document);
        }

        col.setProperty("pretty", "true");
        col.setProperty("encoding", "UTF-8");

        XPathQueryService service = (XPathQueryService)col.getService("XPathQueryService", "1.0");
        for(String abbr : HooksProcessor.uris.keySet()) {
            log.debug("Added Namespace: " + abbr + ": " + HooksProcessor.uris.get(abbr));
            service.setNamespace(abbr, HooksProcessor.uris.get(abbr));
        }
        String query = (args.length > 1) ? query = args[1] : DEFAULT_QUERY;

        try {
            ResourceSet result = service.query(query);
            log.debug("Running Query: " + query + " (" + result.getSize() + ")");
            ResourceIterator i = result.getIterator();
            while(i.hasMoreResources()) {
                XMLResource r = (XMLResource)i.nextResource();
                log.debug(r.getContent());
            }
        } catch(XMLDBException xmldbe) {
            log.error(xmldbe.getMessage());
        }

        try {
            DatabaseInstanceManager manager =
                (DatabaseInstanceManager)col.getService("DatabaseInstanceManager", "1.0"); 
            manager.shutdown();
        } catch(XMLDBException xmldbe) {
            log.error(xmldbe.getMessage());
        }
    }
}
