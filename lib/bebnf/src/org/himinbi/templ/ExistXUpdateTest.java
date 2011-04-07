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
import java.io.FileInputStream;
import java.io.IOException;
import java.io.FileNotFoundException;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  

import org.exist.xmldb.DatabaseInstanceManager;
import org.xmldb.api.DatabaseManager;
import org.xmldb.api.base.Database;
import org.xmldb.api.base.Collection;
import org.xmldb.api.base.ResourceSet;
import org.xmldb.api.base.ResourceIterator;
import org.xmldb.api.modules.CollectionManagementService;
import org.xmldb.api.modules.XQueryService;
import org.xmldb.api.modules.XUpdateQueryService;

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

import org.xmldb.api.base.Resource;
import org.xmldb.api.base.CompiledExpression;
import org.exist.xmldb.XmldbURI;
import org.exist.dom.DocumentImpl;
import org.exist.dom.ElementImpl;
import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

import org.w3c.dom.Document;

/**
 */
public class ExistXUpdateTest {
    public static Logger log = Logger.getLogger(ExistXUpdateTest.class);

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length < 3) {
            log.error("Usage: ExistTreeTest <directory name> <xupdate file> <xpath expression>");
            System.exit(-1);
        }
        
        DocumentSet docs = new DocumentSet(args[0]);

        XUpdateQueryService service =
            (XUpdateQueryService)docs.rootCollection.getService("XUpdateQueryService", "1.0");
        
        log.debug("Generating XUpdate From: " + args[1]);
        service.update(readFile(args[1]));
       
        log.debug("Executing Query: " + args[2]);
        ExistTreeTest.xpathTest(docs.rootCollection, args[2]);

        docs.shutdown();
    }

    public static String readFile(String filename) 
        throws FileNotFoundException, IOException {
        FileInputStream fis = new FileInputStream(filename);
        byte bytes[] = new byte[fis.available()];
        fis.read(bytes);
        return new String(bytes);
    }
}
