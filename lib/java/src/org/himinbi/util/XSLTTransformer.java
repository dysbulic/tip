package org.himinbi.util;

import org.xml.sax.XMLFilter;
import org.xml.sax.InputSource;
import javax.xml.transform.sax.SAXSource;
import javax.xml.transform.sax.SAXTransformerFactory;
import javax.xml.transform.Result;

import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerConfigurationException;

import javax.xml.transform.stream.StreamSource; 
import javax.xml.transform.stream.StreamResult; 

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileNotFoundException;

import java.util.List;
import java.util.Map;
import java.util.HashMap;
import java.util.ArrayList;
import java.util.Arrays;
import java.lang.reflect.Method;
import java.lang.reflect.InvocationTargetException;

public class XSLTTransformer implements Runnable {
    List<XMLFilter> filters = new ArrayList<XMLFilter>();
    InputSource source;
    Result result;
    SAXTransformerFactory transFactory =
        (SAXTransformerFactory)SAXTransformerFactory.newInstance();

    public void setSource(String filename) throws FileNotFoundException {
        setSource(new InputSource(new FileInputStream(filename)));
    }

    public void setSource(InputSource source) {
        this.source = source;
    }

    public InputSource getSource() {
        if(source == null) {
            source = new InputSource(System.in);
        }
        return source;
    }

    public void setResult(String filename) throws FileNotFoundException {
        setResult(new StreamResult(new FileOutputStream(filename)));
    }

    public void setResult(Result result) {
        this.result = result;
    }

    public Result getResult() {
        if(result == null) {
            result = new StreamResult(System.out);
        }
        return result;
    }

    /**
     * Adds a new transform to the chain of transforms between input
     * and output. Order matters as the transforms are applied
     * sequentially.
     */
    public void addTransform(String xsltFilename) throws TransformerConfigurationException {
        addTransform(transFactory.newXMLFilter(new StreamSource(xsltFilename)));
    }

    public void addTransform(String xsltFilename, Map<String,String> templateParams)
        throws TransformerConfigurationException {
        XMLFilter filter = transFactory.newXMLFilter(new StreamSource(xsltFilename));
        if(templateParams != null && templateParams.size() > 0) {
            try {
                Method getTransformer = filter.getClass().getMethod("getTransformer");
                Transformer transformer = (Transformer)getTransformer.invoke(filter);
                for(Map.Entry<String,String> param : templateParams.entrySet()) {
                    transformer.setParameter(param.getKey(), param.getValue());
                }
            } catch(NoSuchMethodException nsme) {
                System.err.println("No Method getTransformer(): Cannot set parameters for: " + xsltFilename);
            } catch(InvocationTargetException ite) {
                System.err.println("Invocation Exception on getTransformer(): Cannot set parameters for: " + xsltFilename);
            } catch (IllegalAccessException iae) {
                System.err.println("Illegal Access: Cannot set parameters for: " + xsltFilename);
            }
        }
        addTransform(filter);
    }

    public void addTransform(XMLFilter filter) {
        filters.add(filter);
    }

    public void process() throws TransformerConfigurationException, TransformerException { 
        SAXSource saxSource = new SAXSource(getSource());

        XMLFilter lastTransform = null;
        for(XMLFilter transform : filters) {
            if(lastTransform == null) {
                saxSource.setXMLReader(transform);                   
            } else {
                transform.setParent(lastTransform);
            }
            lastTransform = transform;
        }
        Transformer transformer = transFactory.newTransformer();
        transformer.transform(saxSource, getResult());
    }

    public void run() {
        try {
            process();
        } catch(TransformerConfigurationException tce) {
            throw new RuntimeException(tce);
        } catch(TransformerException te) {
            throw new RuntimeException(te);
        }
    }

    /**
     * Run a set of files from the command line in an xsltproc style
     * way. Do very little preocessing and fast fail.
     */
    public static void main(String[] args) throws Exception {
        XSLTTransformer transformer = new XSLTTransformer();
        if(args.length < 2) {
            System.out.println("Java partial clone of the xsltproc utility.\n\n" +
                               "Usage: " + transformer.getClass().getName() + " ([args] [xslt])* (<source> | -)\n\n" +
                               "Arguments:\n" +
                               "  (--param | --stringparam) <key> <value>\n" +
                               "  (-o | --output) (<filename> | -)\n\n" +
                               "Multiple output files and output to a directory is not supported.\n\n" +
                               "Unlike the original, multiple stylesheets may be specified and will be chained.\n" +
                               "If there are multiple styles, the paramters will be cleared each time a new\n" +
                               "stylesheet is added, so a stylesheets parameters must occur before the stylesheet.");
        } else {
            // Java needs pythonesque subscript operators
            //List<String> argList = new ArrayList<String>(Arrays.asList(args));
            Map<String,String> params = new HashMap<String,String>();
            for(int i = 0; i < args.length - 1; i++) {
                if(args[i].equals("--param") || args[i].equals("--stringparam") || args[i].equals("-stringparam")) {
                    params.put(args[++i], args[++i]);
                } else if(args[i].equals("-o") || args[i].equals("--output")) {
                    String outputFilename = args[++i];
                    if(outputFilename == "-") {
                        outputFilename = null;
                    }
                    transformer.setResult(outputFilename);
                } else {
                    transformer.addTransform(args[i], params);
                    params.clear();
                }
            }
            if(!args[args.length - 1].equals("-")) {
                transformer.setSource(args[args.length - 1]);
            }
            transformer.process();
        }
    }
}
