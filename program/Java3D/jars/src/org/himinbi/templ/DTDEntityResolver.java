package org.himinbi.templ;

import org.apache.log4j.Logger;
import org.apache.log4j.BasicConfigurator;

import java.io.InputStream;
import java.io.FileReader;
import java.io.FileNotFoundException;

import org.xml.sax.ext.EntityResolver2;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

public class DTDEntityResolver implements EntityResolver2 {
    static Logger log = Logger.getLogger(DTDEntityResolver.class);

    String dtdFilename;

    public DTDEntityResolver(String dtdFilename) {
        this.dtdFilename = dtdFilename;
    }

    static {
        // BasicConfigurator.configure(); // initialize log4j
    }

    public InputSource getExternalSubset(String name, String baseURI) throws SAXException {
        log.debug("Loading Subset: " + name + " : " + baseURI);
        try {
            InputStream source = ClassLoader.getSystemClassLoader().getResourceAsStream(dtdFilename);
            if(source != null) {
                return new InputSource(source);
            } else {
                return new InputSource(new FileReader(dtdFilename));
            }
        } catch(FileNotFoundException fnfe) {
            log.error(fnfe);
        }
        return null;
    }

    public InputSource resolveEntity(String publicId, String systemId) {
        log.debug("Entity: " + publicId + " : " + systemId);
        return null;
    }

    public InputSource resolveEntity(String name, String publicId, String baseURI, String systemId)
        throws SAXException {
        log.debug("Entity: " + name + ": " + baseURI + " [" + publicId + " : " + systemId + "]");
        return null;
    }
}
