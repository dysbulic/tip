package org.himinbi.templ;

import org.apache.log4j.Logger;

import java.util.Iterator;
import java.util.Date;

import java.io.File;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.IOException;

import org.w3c.dom.DOMImplementation;
import org.w3c.dom.bootstrap.DOMImplementationRegistry;
import org.w3c.dom.ls.DOMImplementationLS;
import org.w3c.dom.ls.LSSerializer;
import org.w3c.dom.ls.LSOutput;

import org.exist.xquery.XPathException;
import org.xmldb.api.base.XMLDBException;

/**
 */
public class TemplEngine {
    public static Logger log = Logger.getLogger(TemplEngine.class);

    TemplMetadata metadata = new TemplMetadata();

    { HooksProcessor.configureLogging(); }

    public TemplEngine() {
    }

    public TemplEngine(String baseURI, String driver) {
    }

    /*
    public void addMetadata(InputStream data) throws SAXException, IOException {
        try {
            DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
            //log.debug("Document Class: " + dbFactory.getAttribute(DOCUMENT_CLASS_ID));
            dbFactory.setExpandEntityReferences(false);
            dbFactory.setFeature(HooksProcessor.ENTITY_RESOLVER_2_ID, true);
            dbFactory.setNamespaceAware(true);
            log.debug("Factory: " + (dbFactory.isValidating() ? "is" : "is not") + " validating; " +
                      (dbFactory.isExpandEntityReferences() ? "is" : "is not") + " expanding entites");
            DocumentBuilder builder = dbFactory.newDocumentBuilder();
            builder.setEntityResolver(new DTDEntityResolver(MATHML_DTD_FILE));
            metadata.mergeMetadata(builder.parse(data));
        } catch(ParserConfigurationException pce) {
            log.error(pce);
        }
    }

    public static void main(String[] args) throws Exception {
        TemplEngine engine = new TemplEngine();
        engine.addMetadata(ClassLoader.getSystemClassLoader().getResourceAsStream(engine.DEFAULT_SETTINGS));

        DOMImplementation implementation =
            DOMImplementationRegistry.newInstance().getDOMImplementation("XML 3.0");
        DOMImplementationLS lsFeature = (DOMImplementationLS)implementation.getFeature("LS", "3.0");
        LSSerializer serializer = lsFeature.createLSSerializer();
        LSOutput output = lsFeature.createLSOutput();
        output.setEncoding("ISO-8859-1");
        output.setByteStream(System.out);
        serializer.write(engine.metadata.getMetadata(), output);

    }
    */
}
