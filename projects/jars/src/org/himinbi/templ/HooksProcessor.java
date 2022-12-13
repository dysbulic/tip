package org.himinbi.templ;

import org.apache.log4j.Logger;
import org.apache.log4j.BasicConfigurator;
import org.apache.log4j.PropertyConfigurator;

import java.util.Map;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.List;
import java.util.ArrayList;
import java.util.Stack;

import javax.xml.parsers.DocumentBuilder; 
import javax.xml.parsers.DocumentBuilderFactory;  
import javax.xml.parsers.FactoryConfigurationError;  
import javax.xml.parsers.ParserConfigurationException;
import org.xml.sax.SAXException;  
import org.xml.sax.SAXParseException;
import org.w3c.dom.Document;
import org.w3c.dom.DOMException;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.w3c.dom.Element;

import org.w3c.dom.DOMImplementation;
import org.w3c.dom.bootstrap.DOMImplementationRegistry;
import org.w3c.dom.ls.DOMImplementationLS;
import org.w3c.dom.ls.LSSerializer;
import org.w3c.dom.ls.LSOutput;

import javax.xml.XMLConstants;
import javax.xml.xpath.XPathFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathConstants;

import javax.xml.namespace.NamespaceContext;

import java.io.File;
import java.io.FileOutputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.InputStream;
import java.io.FileInputStream;
import java.io.OutputStream;
import java.io.IOException;
import java.util.Properties;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

public class HooksProcessor {
    static Logger log = Logger.getLogger(HooksProcessor.class);

    final static String ENTITY_MANAGER_ID = "http://apache.org/xml/properties/internal/entity-manager";
    final static String ENTITY_REF_NODES_ID = "http://apache.org/xml/features/dom/create-entity-ref-nodes";
    final static String DOCUMENT_CLASS_ID = "http://apache.org/xml/properties/dom/document-class-name";
    final static String LEXER_ID = "http://xml.org/sax/properties/lexical-handler";
    final static String ENTITY_RESOLVER_2_ID = "http://xml.org/sax/features/use-entity-resolver2";
    final static String BUILDER_PROP = "javax.xml.parsers.DocumentBuilderFactory";

    final static String TEMPL_URI = "http://odin.himinbi.org/templ/0.1/";

    // Default metadata
    final static String DEFAULT_SETTINGS = "default.tmpl.metadata";

    static Map<String, String> uris = new Hashtable<String, String>();

    final static String MATHML_DTD_FILE = "mathml.ent";
    final static String FILE_EXT = ".tmpl";

    static DocumentBuilder builder;
    Document container;
    List<TemplHook> hooks = new ArrayList<TemplHook>();
    int hookIndex = 0;
    Map<String, Element> metadata = new HashMap<String, Element>();
    LSSerializer serializer;
    DOMImplementationLS lsFeature;
    LSOutput output;
    String basePath = "." + File.separator;
    String currentPath = basePath;
    XPath xpath;
    File activeFile;

    static {
        uris.put("tmpl", TEMPL_URI);
        uris.put("html", "http://www.w3.org/1999/xhtml");
        uris.put("svg", "http://www.w3.org/2000/svg");
        uris.put("xlink", "http://www.w3.org/1999/xlink");
        uris.put("xsl", "http://www.w3.org/1999/XSL/Transform");
        uris.put("kintera", "http://www.kintera.com/kintera_cms/");

        configureLogging();
    }

    public static void configureLogging() {
        Properties props = new Properties();
        InputStream propsStream = ClassLoader.getSystemClassLoader().getResourceAsStream("log4j.properties");
        if(propsStream != null) {
            try {
                props.load(propsStream);
            } catch(IOException ioe) {
            }
        }
        PropertyConfigurator.configure(props);
    }

    public HooksProcessor() {
        try {
            log.debug("BuilderFactory Class: " + System.getProperty(BUILDER_PROP));
            DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
            log.debug("Document Class: " + dbFactory.getAttribute(DOCUMENT_CLASS_ID));
            dbFactory.setExpandEntityReferences(false);
            dbFactory.setFeature(ENTITY_RESOLVER_2_ID, true);
            dbFactory.setNamespaceAware(true);
            log.debug("Factory: " + (dbFactory.isValidating() ? "is" : "is not") + " validating; " +
                      (dbFactory.isExpandEntityReferences() ? "is" : "is not") + " expanding entites");
            builder = dbFactory.newDocumentBuilder();
            builder.setEntityResolver(new DTDEntityResolver(MATHML_DTD_FILE));

            XPathFactory xpathFactory = XPathFactory.newInstance();
            xpath = xpathFactory.newXPath();
            xpath.setNamespaceContext(new MappedNamespaceContext(uris));

            DOMImplementation implementation =
                DOMImplementationRegistry.newInstance().getDOMImplementation("XML 3.0");
            lsFeature = (DOMImplementationLS)implementation.getFeature("LS", "3.0");

            serializer = lsFeature.createLSSerializer();
            //serializer.getDomConfig().setParameter("normalize-characters", true);
            //serializer.getDomConfig().setParameter("canonical-form", true);
            output = lsFeature.createLSOutput();
            output.setEncoding("ISO-8859-1");
        } catch (ParserConfigurationException pce) {
            pce.printStackTrace();
        } catch(ClassNotFoundException cnfe) {
            log.error(cnfe);
        } catch(InstantiationException ie) {
            log.error(ie);
        } catch(IllegalAccessException iae) {
            log.error(iae);
        }
    }

    public void addHook(Element hookDescription) {
        String className = hookDescription.getAttribute("class");
        log.debug("Loading Hook: " + className);
        try {
            Class hookClass = ClassLoader.getSystemClassLoader().loadClass(className);
            TemplHook hook = null;
            try {
                Constructor constructor = hookClass.getConstructor(this.getClass());
                hook = (TemplHook)constructor.newInstance(this);
            } catch(NoSuchMethodException nsme) {
            } catch(InvocationTargetException ite) {
                log.error("Error Creating " + className + ": " + ite.getMessage());
            }
            if(hook == null) {
                hook = (TemplHook)hookClass.newInstance();
                log.info("Used 0 Argument Constructor: "  + className);
            }
            hook.init(hookDescription, this);
            addHook(hook);
        } catch(ClassNotFoundException cnfe) {
            log.error("Couldn't Find Class: " + cnfe);
        } catch(InstantiationException ie) {
            log.error("Couldn't Instantiate Class: " + ie.getMessage());
        } catch(IllegalAccessException iae) {
            log.error("Hook Access Error: " + iae.getMessage());
        }
    }

    public void addHook(TemplHook hook) {
        log.debug("Add Hook: " + hooks.size() + ": " + hook.getClass().getName());
        hooks.add(hook);
    }

    public boolean hasMetadata(String name) {
        return hasMetadata(name, false);
    }

    public boolean hasMetadata(String name, boolean search) {
        if(!metadata.containsKey(name) && search) {
            // check to see if an appropriately named file exists
            String[] pathes = { basePath + name + ".php.tmpl", basePath + name + ".html.tmpl" };
            for(String path : pathes) {
                if(new File(path).exists()) {
                    try {
                        log.debug("Attempting to load " + path + " for " + name);
                        loadArea(new FileInputStream(path));
                    } catch(IOException ioe) {
                        log.error("Error: " + ioe.getMessage());
                    } catch(SAXException saxe) {
                        log.error("Error: " + saxe.getMessage());
                    }
                }
            }
        }
        return metadata.containsKey(name);
    }

    /**
     * Destructively incorporates a <tmpl:var> element into the metadata
     */
    public void addMetadata(Element metadataDescription) {
        Element existing = metadata.get(metadataDescription.getAttribute("name"));
        if(existing != null && metadataDescription.getAttribute("additive") == "true") {
            log.debug("Adding Metadata: " + metadataDescription.getAttribute("name") +
                      " (" + existing.getChildNodes().getLength() +
                      " + " + metadataDescription.getChildNodes().getLength() + ")");
            while(metadataDescription.getChildNodes().getLength() > 0) {
                existing.appendChild(metadataDescription.getChildNodes().item(0));
            }
        } else {
            log.debug("Replacing Metadata: " + metadataDescription.getAttribute("name") +
                      " (" + metadataDescription.getChildNodes().getLength() + ")");
            metadata.put(metadataDescription.getAttribute("name"), metadataDescription);
        }
    }

    public void setBasePath(String basePath) {
        log.debug("Setting BasePath: " + basePath);
        if(!basePath.endsWith(File.separator)) {
            basePath += File.separator;
        }
        this.basePath = basePath;
    }

    public void setTemplate(String filename) throws IOException {
        setTemplate(new File(filename));
    }

    public void setTemplate(File file) throws IOException {
        log.debug("Setting Template: " + file);

        try {
            container = builder.parse(file);
        } catch(SAXException saxe) {
            Exception e = (saxe.getException() != null ? saxe.getException() : saxe);
            log.error("Template Parsing Error: " + e);
        }
    }

    public void mergeContent(File file) throws IOException {
        activeFile = file;
        // Can't use a foreach because some hooks cause a hook to be added
        for(hookIndex = 0; hookIndex < hooks.size(); hookIndex++) {
            log.debug("Running Hook: " + hookIndex + ": " + hooks.get(hookIndex));
            hooks.get(hookIndex).runHook(this);
        }
    }
    
    public TemplateArea loadArea(String filename) throws SAXException, IOException {
        return loadArea(new FileInputStream(filename));
    }

    public TemplateArea loadArea(InputStream input) throws SAXException, IOException {
        return loadArea(input, true);
    }

    public TemplateArea loadArea(InputStream input, boolean integrateInfo)
        throws SAXException, IOException {
        Node template = builder.parse(input).getDocumentElement();
        if(container != null) {
            template = container.importNode(template, true);
        }
        TemplateArea area = new TemplateArea(template);
        if(integrateInfo) {
            for(Element child : area.metadata.values()) {
                addMetadata(child);
            }
            int i = 0;
            for(Element child : area.hooks) {
                addHook(child);
            }
        }
        return area;
    }


    public void writeTemplate(OutputStream outStream) {
        output.setByteStream(outStream);
        serializer.write(container, output);
    }

    public static void main(String[] args) {
        if(args.length < 1) {
            log.info("Specify a directory");
        } else {
            HooksProcessor processor = new HooksProcessor();
            for(String arg : args) {
                File destination = new File(arg);
                if(!destination.exists()) {
                    log.error("File Not Found: " + arg);
                } else if(destination.isDirectory()) {
                    processor.setBasePath(arg);
                    processor.processDirectory(arg);
                } else {
                    try {
                        processor.setBasePath(destination.getParent() + File.separator);
                        processor.processFile(destination);
                    } catch(IOException ioe) {
                        log.error("Error Processing: " + arg + ": " + ioe.getMessage());
                    }
                }
            }
        }
    }

    public void loadDefaultHooks() {
        try {
            InputStream input = getClass().getClassLoader().getResourceAsStream(DEFAULT_SETTINGS);
            if(input == null) {
                log.error("Could not find default settings: " + DEFAULT_SETTINGS);
            } else {
                loadArea(input);
            }
        } catch(SAXException saxe) {
            log.error(saxe);
        } catch(IOException ioe) {
            log.error(ioe);
        }
    }

    public void processFile(File file) throws IOException {
        log.debug("Clearing Processor Settings");
        metadata.clear();
        hooks.clear();
        loadDefaultHooks();
        mergeContent(file);
        String destDir = currentPath + File.separator + ".templ.cache" + File.separator;
        String outfile = destDir + file.getName().substring(0, file.getName().length() - FILE_EXT.length());
        
        File destDirFile = new File(destDir);
        if(!destDirFile.exists()) {
            destDirFile.mkdir();
            log.debug("Created Our Dir: " + destDir);
        }

        FileOutputStream output = new FileOutputStream(outfile);
        log.debug("Writing " + file + " to: " + outfile);
        writeTemplate(output);
    }

    protected void processDirectory(String outDir) {
        if(!(new File(outDir)).isDirectory()) {
            throw new IllegalArgumentException("Not a Directory: " + outDir);
        }
        if(!outDir.endsWith(File.separator)) {
            outDir += File.separator;
        }
        Stack<String> directories = new Stack<String>();
        currentPath = outDir;
        String destDir = currentPath + File.separator + ".templ.cache" + File.separator;
        try {
            String[] files = new File(outDir).list();
            for(String file : files) {
                File testFile = new File(outDir + file);
                if(testFile.isDirectory()) {
                    if(!file.startsWith(".")) {
                        directories.push(outDir + file + File.separator);
                    }
                } else {
                    if(file.endsWith(FILE_EXT)) {
                        try {
                            processFile(testFile);
                        } catch(IllegalArgumentException iae) {
                            log.info("\"" + file + "\" is not a content template: " + iae.getMessage());
                        }
                    } else {
                        log.debug("Skipped File: " + testFile);
                    }
                }
            }
            for(String dir : directories) {
                processDirectory(dir); 
            }
        } catch(FileNotFoundException fnfe) {
            fnfe.printStackTrace();
        } catch(IOException ioe) {
            ioe.printStackTrace();
        }
    }
}

class MappedNamespaceContext implements NamespaceContext {
    Map namespaces;

    public MappedNamespaceContext(Map namespaces) {
        namespaces.put("xml", XMLConstants.XML_NS_URI);
        this.namespaces = namespaces;
    }

    public String getNamespaceURI(String prefix) {
        if (prefix == null) throw new NullPointerException("Null prefix");
        if(namespaces.containsKey(prefix)) {
            return (String)namespaces.get(prefix);
        }
        return XMLConstants.NULL_NS_URI;
    }

    public String getPrefix(String uri) {
      throw new UnsupportedOperationException();
    }

    public Iterator getPrefixes(String uri) {
        throw new UnsupportedOperationException();
    }
}
