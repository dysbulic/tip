package org.himinbi.tokenizer;

import java.util.Map;
import java.util.List;
import java.util.ArrayList;
import java.util.Hashtable;

import java.lang.reflect.Method;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

import java.io.IOException;

import org.xml.sax.InputSource;
import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;
import org.xml.sax.XMLReader;
import org.xml.sax.helpers.XMLReaderFactory;
import org.xml.sax.helpers.DefaultHandler;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

/**
 * Loads tokenizer chains from an xml file
 */
public class TokenizerFactory extends DefaultHandler
{
    StreamTokenizer tokenizer;
    List filters = new ArrayList();

    // Holder for filter being built
    TokenFilter currentFilter;

    static Logger log = Logger.getLogger(TokenizerFactory.class);    

    public StreamTokenizer getTokenizer(String filename)
        throws SAXException, IOException, IllegalArgumentException
    {
        return getTokenizer(new InputSource(filename));
    }

    public synchronized StreamTokenizer getTokenizer(InputSource input)
        throws SAXException, IOException, IllegalArgumentException
    {
        XMLReader reader = XMLReaderFactory.createXMLReader();
        reader.setContentHandler(this);
        reader.setErrorHandler(this);
        reader.parse(input);
        return new TokenizerChain(tokenizer, filters);
    }
    
    /**
     * @return the end of the filter chain
     */
    public TokenStream getEndOfChain()
    {
        if(filters.size() == 0)
        {
            return tokenizer;
        }
        else
        {
            return (TokenFilter)filters.get(filters.size() - 1);
        }
    }
    
    public void startElement(String namespaceURI,
                             String simpleName,
                             String qualifiedName,
                             Attributes attributes)
        throws SAXException
    {
        if("tokenizer".equals(qualifiedName))
        {
            tokenizer = (StreamTokenizer)getNewInstance(attributes.getValue("class"));
        }
        else if("token".equals(qualifiedName))
        {
            String name = attributes.getValue("name");
            String description = attributes.getValue("description");

            log.debug("Adding new token " + name + " (" + description + ")");

            tokenizer.addTokenType(name, description);
        }
        else if("tokenfilter".equals(qualifiedName))
        {
            currentFilter = (TokenFilter)getNewInstance(attributes.getValue("class"));
        }
        else if("parameter".equals(qualifiedName))
        {
            setParameter(currentFilter,
                         attributes.getValue("name"),
                         attributes.getValue("value"));
        }
    }
    
    public void endElement(String namespaceURI,
                           String simpleName,
                           String qualifiedName)
    {
        if("tokenfilter".equals(qualifiedName))
        {
            currentFilter.setIncomingStream(getEndOfChain());
            filters.add(currentFilter);
            currentFilter.initialize();
        }
    }
    
    public void warning(SAXParseException exception)
             throws SAXException
    {
        log.warn(exception);
    }

    public void error(SAXParseException exception)
        throws SAXException
    {
        log.error(exception);
    }

    public void fatalError(SAXParseException exception)
                throws SAXException
    {
        log.error(exception);
    }

    /**
     * Builds a setter call for the named parameter by capitalizing it
     * and adding a set prefix.
     */
    public static void setParameter(Object holder, String name, String value)
    {
        if(name == null || name.length() == 0)
        {
            throw new IllegalArgumentException("name parameter not set");
        }

        String fieldName = ("set" + name.substring(0, 1).toUpperCase() +
                            name.substring(1));

        log.debug("Setting " + fieldName + " to " + value);

        try
        {
            Method setter = holder.getClass().getMethod(fieldName,
                                                        new Class[] { String.class });
            setter.invoke(holder, new Object[] { value });
        }
        catch(NoSuchMethodException nsme)
        {
            nsme.fillInStackTrace();
            IllegalArgumentException iae =
                new IllegalArgumentException("No such method: " + fieldName + " in " +
                                             holder.getClass().getName());
            iae.initCause(nsme);
            throw iae;
        }
        catch(IllegalAccessException ixe)
        {
            ixe.fillInStackTrace();
            IllegalArgumentException iae = new IllegalArgumentException();
            iae.initCause(ixe);
            throw iae;
        }
        catch(InvocationTargetException ite)
        {
            ite.fillInStackTrace();
            IllegalArgumentException iae = new IllegalArgumentException();
            iae.initCause(ite);
            throw iae;
        }
    }

    public static Object getNewInstance(String className)
        throws IllegalArgumentException
    {
        try
        {
            Class[] argumentTypes = new Class[0];
            Object[] arguments = new Object[0];
            
            Class classReference = Class.forName(className);
            Constructor constructor =
                classReference.getConstructor(argumentTypes);
            
            return constructor.newInstance(arguments);
        }
        catch(ClassNotFoundException cnfe)
        {
            IllegalArgumentException iae =
                new IllegalArgumentException("No such class: " + className);
            iae.initCause(cnfe);
            throw iae;
        }
        catch(NoSuchMethodException nsme)
        {
            IllegalArgumentException iae =
                new IllegalArgumentException("No zero argument constructor in " + className);
            iae.initCause(nsme);
            throw iae;
        }
        catch(InstantiationException ie)
        {
            IllegalArgumentException iae = new IllegalArgumentException();
            iae.initCause(ie);
            throw iae;
        }
        catch(IllegalAccessException ixe)
        {
            IllegalArgumentException iae = new IllegalArgumentException();
            iae.initCause(ixe);
            throw iae;
        }
        catch(InvocationTargetException ite)
        {
            IllegalArgumentException iae = new IllegalArgumentException();
            iae.initCause(ite);
            throw iae;
        }
    }
}
