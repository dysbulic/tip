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
import java.io.FileOutputStream;
import java.io.PrintStream;
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
import org.xmldb.api.modules.XQueryService;

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
//import org.jdom.input.SAXHandler;

/**
 * Recursively loads a directory tree of files into an Exist database.
 */
public class ExistXQueryTest {
    public static Logger log = Logger.getLogger(ExistXQueryTest.class);

    { HooksProcessor.configureLogging(); }

    public static void main(String[] args) throws Exception {
        if(args.length < 2) {
            log.error("Usage: ExistTreeTest <directory name> <xquery file> [output file]");
            System.exit(-1);
        }
        
        DocumentSet docs = new DocumentSet(args[0]);
        
        XQueryService service =
            (XQueryService)docs.rootCollection.getService("XQueryService", "1.0");
        service.setProperty("indent", "yes");

        log.debug("Generating XQuery: " + args[1]);

        FileInputStream fis = new FileInputStream(args[1]);
        byte bytes[] = new byte[fis.available()];
        fis.read(bytes);
        CompiledExpression compiled = service.compile(new String(bytes));
        ResourceSet result = service.execute(compiled);
        ResourceIterator i = result.getIterator();

        PrintStream out = System.out;
        if(args.length > 2) {
            log.debug("Outputting to: " + args[2]);
            out = new PrintStream(new FileOutputStream(args[2]));
        }
        while(i.hasMoreResources()) {
            Resource r = i.nextResource();
            out.println((String)r.getContent());
        }
        
        docs.shutdown();
    }
}
