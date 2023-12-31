package org.himinbi.templ.test;

import org.himinbi.templ.DocumentSet;

import org.junit.Test;
import org.junit.BeforeClass;
import org.junit.AfterClass;
import static org.junit.Assert.assertTrue;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.fail;

import java.util.Collection;
import java.util.Enumeration;
import java.util.ArrayList;
import java.net.URL;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import org.xmldb.api.base.XMLDBException;
import java.net.URISyntaxException;

/**
 */
public class DocumentSetTest {
    final static String JAR_BASE = "templ";

    static DocumentSet documents;
    String[] sampleDocuments = { "test/test_environment.1.tmpl",
                                 "test/test_environment.2.tmpl",
                                 "test/test_environment.3.tmpl" };

    @BeforeClass public static void loadDocuments() throws FileNotFoundException, IOException, XMLDBException {
        String containingJarName = getContainingJarName();
        if(containingJarName == null) {
            String message = "Could not find containing jar starting with: " + JAR_BASE;
            fail(message);
            throw new FileNotFoundException(message);
        }
        documents = new DocumentSet(containingJarName);
    }

    @AfterClass public static void shutdown() {
        documents.shutdown();
    }

    @Test public void testSamplesLoaded() throws URISyntaxException, XMLDBException {
        for(String file : sampleDocuments) {
            assertNotNull(documents.getDocument(file, false));
        }
    }

    @Test public void testSampleLength() throws URISyntaxException, XMLDBException {
        assertNotNull(documents.getDocument(sampleDocuments[0]));
    }

    /**
     * Searches the classpath for a jar beginning with the name 'templ'
     */
    public static String getContainingJarName() {
        for(String path : System.getProperty("java.class.path").split(File.pathSeparator)) {
            File jarFile = new File(path);
            if(jarFile.exists() && jarFile.getName().startsWith(JAR_BASE)) {
                return path;
            }
        }
        return null;
    }

    
    public static void main(String[] args) throws Exception {
    }
}
