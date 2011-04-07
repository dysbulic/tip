package org.himinbi.templ;

import org.apache.log4j.Logger;

import java.io.File;
import java.io.InputStream;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;

import java.text.SimpleDateFormat;
import java.net.URISyntaxException;
import java.util.jar.JarInputStream;
import java.util.jar.JarEntry;
import java.util.Iterator;
import java.util.Date;

import org.exist.xmldb.DatabaseInstanceManager;
import org.xmldb.api.DatabaseManager;
import org.xmldb.api.base.Database;
import org.xmldb.api.base.Collection;
import org.xmldb.api.base.ResourceSet;
import org.xmldb.api.base.ResourceIterator;
import org.xmldb.api.modules.CollectionManagementService;
import org.xmldb.api.modules.XMLResource;
import org.xmldb.api.modules.XPathQueryService;

import org.exist.xmldb.XmldbURI;
import org.exist.dom.DocumentImpl;
import org.exist.dom.ElementImpl;
import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

import org.apache.tools.ant.types.Resource;
import org.apache.tools.ant.Project;
import org.apache.tools.ant.types.FileSet;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import org.w3c.dom.Document;
import org.w3c.dom.Node;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  
import org.xml.sax.InputSource;
import org.xml.sax.XMLReader;
import javax.xml.parsers.ParserConfigurationException;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.XMLReaderFactory;

import javax.xml.transform.TransformerConfigurationException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.sax.TransformerHandler;
import javax.xml.transform.sax.SAXResult;
import javax.xml.transform.sax.SAXTransformerFactory;
import org.xml.sax.XMLFilter;
import javax.xml.transform.stream.StreamSource;
import javax.xml.transform.stream.StreamResult;

/**
 * Represents a set of documents rooted in the specified directory
 */
public class DocumentSet {
    public static Logger log = Logger.getLogger(DocumentSet.class);
    
    public final static String baseURI = "xmldb:exist:///db/documents";
    public final static String driver = "org.exist.xmldb.DatabaseImpl";

    String DBUSER = "admin";
    String DBPASS = "";

    Database database;
    CollectionManagementService mgtService;
    Collection rootCollection;

    // XSLT stylesheet for transforming paths
    public String PATHS_STYLE = "relative_paths.xslt";

    // XHTML and MathML entities
    public String MATHML_DTD_FILE = "mathml.ent";

    // Default metadata
    public String DEFAULT_SETTINGS = "default.tmpl.metadata";

    public boolean isInitialized = false;

    public DocumentSet() {
        try {
            Class driverClass = Class.forName(driver);
            database = (Database)driverClass.newInstance();
            database.setProperty("create-database", "true");
            DatabaseManager.registerDatabase(database);
            String collectionBase = XmldbURI.xmldbUriFor(baseURI).getCollectionPath();
            String basePath = baseURI.substring(0, baseURI.length() - collectionBase.length() + collectionBase.indexOf("/", 1));
            rootCollection = DatabaseManager.getCollection(basePath, DBUSER, DBPASS);
            log.debug("Loaded Base Collection: " + basePath);
            mgtService = (CollectionManagementService)rootCollection.getService("CollectionManagementService", "1.0");
            isInitialized = true;
        } catch(ClassNotFoundException cnfe) {
            log.error("CNFE: " + cnfe.getMessage());
        } catch(InstantiationException ie) {
            log.error("IE: " + ie.getMessage());
        } catch(XMLDBException xmldbe) {
            log.error("XMLDBE: " + xmldbe.getMessage());
        } catch(IllegalAccessException iae) {
            log.error("IAE: " + iae.getMessage());
        } catch(URISyntaxException urise) {
            log.error("URISE: " + urise.getMessage());
        }
    }

    public DocumentSet(String sourceName) throws FileNotFoundException, IOException, XMLDBException {
        this();
        File source = new File(sourceName);
        if(source.isDirectory()) {
            loadDirectory(sourceName);
        } else {
            loadJar(sourceName);
        }
    }

    public void shutdown() {
        try {
            DatabaseInstanceManager manager =
                (DatabaseInstanceManager)rootCollection.getService("DatabaseInstanceManager", "1.0"); 
            manager.shutdown();
        } catch(XMLDBException xmldbe) {
            log.error("XMLDB: " + xmldbe.getMessage());
        }
    }

    public Document getDocument(String name) throws URISyntaxException, XMLDBException {
        return getDocument(name, true);
    }

    public Document getDocument(String name, boolean create) throws URISyntaxException, XMLDBException {
        XMLResource resource = getResource(name, create);
        Document doc = null;
        if(resource != null) {
            doc = resource.getContentAsDOM().getOwnerDocument();
        }
        log.debug("Getting Document: " + name + ": " + doc);
        return doc;
    }

    Collection currentCollection = null;

    public Collection getCollection(String path) throws XMLDBException {
        return getCollection(path, true);
    }

    public Collection getCollection(String path, boolean create) throws XMLDBException {
        String collectionName = baseURI + (path.length() > 0 ? "/" + path : "");
        String collectionBase = null;
        try {
            collectionBase = XmldbURI.xmldbUriFor(collectionName).getCollectionPath();
        } catch(URISyntaxException urise) { }
        if(currentCollection == null || !currentCollection.getName().equals(collectionBase)) {
            currentCollection =
                DatabaseManager.getCollection(collectionName, DBUSER, DBPASS);
            if(currentCollection != null) {
                log.debug("Retrieved Collection: " + collectionName);
            } else if(create) {
                log.debug("Creating Collection: " + path);
                currentCollection = mgtService.createCollection(collectionName);
                currentCollection.setProperty("pretty", "true");
                currentCollection.setProperty("encoding", "UTF-8");
            } else {
                log.debug("Nonexistent Collection: " + path);
                return null;
            }
        }
        return currentCollection;
    }
    
    /**
     * Load the documents in a jar file
     */
    public void loadJar(String sourceJar) throws FileNotFoundException, IOException, XMLDBException {
        JarInputStream jarStream = new JarInputStream(new FileInputStream(sourceJar));
        log.debug("Loading Jar: " + sourceJar);
        while(true) {
            try {
                JarEntry jarEntry = jarStream.getNextJarEntry();
                if(jarEntry == null) { break; }
                if(jarEntry.getName().endsWith("/")) { continue; }
                
                InputStream input = ClassLoader.getSystemClassLoader().getResourceAsStream(jarEntry.getName());
                XMLResource resource = getResource(jarEntry.getName());
                storeResource(input, resource, jarEntry.getTime());
            } catch(URISyntaxException urise) {
                log.error("URI Error: " + urise.getMessage());
            }
        }
    }

    public void storeResource(InputStream input, XMLResource document) {
        storeResource(input, document, null);
    }

    public void storeResource(InputStream input, XMLResource document, Long lastModified) {
        boolean createPlaceholder = false;
        SimpleDateFormat format = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
        
        try {
            if(lastModified != null) {
                try {
                    Node content = document.getContentAsDOM();
                    if(content instanceof DocumentImpl) {
                        long resourceModified = ((DocumentImpl)content).getMetadata().getLastModified();
                        if(lastModified < resourceModified) {
                            log.debug("Cached Resource: " + document.getDocumentId() +
                                      " (" + format.format(resourceModified) + " >= " + format.format(lastModified) +") " +
                                      document.getContentAsDOM().getFirstChild().getNodeName());
                            return;
                        }
                        log.debug("Cache Failed: " + document.getDocumentId() +
                                  " (" + format.format(resourceModified) + " >= " + format.format(lastModified) +")");
                    }
                } catch(XMLDBException xmldbe) { }
            }
            SAXTransformerFactory stf = (SAXTransformerFactory)TransformerFactory.newInstance();;
            XMLReader reader = XMLReaderFactory.createXMLReader();

            /* XQueries and merges operate across multiple levels of the hierarchy and this
             * breaks relative pathes in the html. So, relative pathes are stored relative
             * to the root in the database and converted on serialization.
             */
            InputStream stylesheet =
                ClassLoader.getSystemClassLoader().getResourceAsStream(PATHS_STYLE);
            log.debug("Loading Stylesheet: " + PATHS_STYLE);
            TransformerHandler transformHandler =
                stf.newTransformerHandler(new StreamSource(stylesheet));
            //log.debug("Setting Path: " + document.getParentCollection().getName());
            transformHandler.getTransformer().setParameter("path", document.getParentCollection().getName() + "/");
            transformHandler.setResult(new SAXResult(document.setContentAsSAX()));
            //transformHandler.setResult(new StreamResult(System.out));
            reader.setContentHandler(transformHandler);
            reader.parse(new InputSource(input));
        } catch(TransformerConfigurationException tce) {
            log.error("TC Error: " + tce.getMessage());
            createPlaceholder = true;
        } catch(SAXException saxe) {
            log.error("SAX Error: " + saxe.getMessage());
            createPlaceholder = true;
        } catch(IOException ioe) {
            log.error("IO Error: " + ioe.getMessage());
            createPlaceholder = true;
        } catch(XMLDBException xmldbe) {
            String message = xmldbe.getLocalizedMessage();
            if(message.equals("")) { message = xmldbe.getMessage(); }
            if(message.equals("")) { xmldbe.printStackTrace(); }
            if(message.equals("")) { message = xmldbe.toString(); }
            log.error("XMLDB Error: " + message);
            createPlaceholder = true;
        }
        
        try {
            if(createPlaceholder) {
                Document doc = DocumentBuilderFactory.newInstance().newDocumentBuilder().newDocument();
                doc.appendChild(doc.createElementNS(HooksProcessor.uris.get("templ"), "file"));
                document.setContentAsDOM(doc);
                log.debug("Created Placeholder: " + document.getDocumentId());
            }
            document.getParentCollection().storeResource(document);
            log.debug("Stored Document: " + document.getId());
        } catch(ParserConfigurationException pce) {
            log.error(pce.getMessage());
        } catch(XMLDBException xmldbe) {
            log.error(xmldbe.getMessage());
        }
    }

    XMLResource getResource(String resourceName) throws URISyntaxException, XMLDBException {
        return getResource(resourceName, true);
    }

    /**
     * If create is false, null is returned if the resource is not present
     */
    XMLResource getResource(String resourceName, boolean create) throws URISyntaxException, XMLDBException {
        String collectionBase = XmldbURI.xmldbUriFor(baseURI).getCollectionPath();
        String path = "", fileName = resourceName;
        int slashIndex = resourceName.lastIndexOf("/");
        if(slashIndex >= 0) {
            path = resourceName.substring(0, resourceName.lastIndexOf("/"));
            fileName = resourceName.substring(resourceName.lastIndexOf("/") + 1);
        }

        Collection collection = getCollection(path, create);
        
        if(collection == null) { return null; }
        
        XMLResource document = (XMLResource)collection.getResource(fileName);
        if(document == null) {
            document = (XMLResource)collection.createResource(fileName, "XMLResource");
            log.debug("Created Document: " + document.getId() + " : " + fileName);
        }
        return document;
    }

    public String[] getResourceList(String path) {
        return null;
    }

    /**
     * Recursively load the documents from a directory
     */
    public void loadDirectory(String baseDir) {
        loadDirectory(baseDir, new String[0]);
    }

    /**
     * Caches a hierarchy of files in an exist database instance.
     * Excludes patterns are ant style.
     */
    public void loadDirectory(String baseDir, String[] excludes) {
        FileSet files = new FileSet();
        Project project = new Project();
        project.setBasedir(baseDir);
        files.setProject(project);
        files.setDir(project.getBaseDir());

        if(excludes != null) {
            for(String exclude : excludes) {
                log.debug("Adding Exclude Pattern: " + exclude);
                files.setExcludes(exclude);
            }
        }
        cacheFileset(files);
    }

    /**
     * Caches a hierarchy of files in an exist database instance.
     */
    public void cacheFileset(FileSet files) {
        log.debug("Importing " + files.size() + " file" + (files.size() != 1 ? "s" : ""));
        try {
            for(Iterator iter = files.iterator(); iter.hasNext(); ) {
                Resource resource = (Resource)iter.next();
                String resourceName = resource.getName();
                
                try {
                    XMLResource document = getResource(resourceName);
                    storeResource(resource.getInputStream(), document,
                                  (new File(resourceName)).lastModified());
                } catch(XMLDBException xmldbe) {
                    log.error("Failed to Store " + resourceName + " (" + xmldbe.getMessage() + ")");
                } catch(IOException ioe) {
                    log.error("Failed to Store " + resourceName + " (" + ioe.getMessage() + ")");
                }
            }
        } catch(URISyntaxException urise) {
            log.error(urise.getMessage());
            //} catch(XMLDBException xmldbe) {
            //     log.error(xmldbe.getMessage());
        }
    }
}
