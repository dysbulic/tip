package org.himinbi.parser;

import java.util.Map;

import java.io.FileReader;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.FileNotFoundException;
import java.io.IOException;

import org.xml.sax.helpers.AttributesImpl;
import org.xml.sax.SAXException;

import org.apache.log4j.Logger;

import org.apache.cocoon.ProcessingException;
import org.apache.cocoon.environment.Source;
import org.apache.cocoon.environment.SourceResolver;
import org.apache.cocoon.generation.AbstractGenerator;
import org.apache.cocoon.generation.ComposerGenerator;
import org.apache.avalon.framework.parameters.Parameters;
import org.apache.avalon.framework.parameters.ParameterException;
import org.apache.avalon.framework.configuration.Configurable;
import org.apache.avalon.framework.configuration.Configuration;
import org.apache.avalon.framework.configuration.ConfigurationException;

import org.himinbi.tokenizer.TokenizerFactory;
import org.himinbi.tokenizer.StreamTokenizer;

public class ParserCocoonGenerator extends ComposerGenerator
    implements ParseEventListener, ParseErrorListener, Configurable
{
    public static final String SBNF_TCML_KEY = "sbnf-tokenizer";
    public static final String GRAMMAR_KEY = "grammar";
    public static final String TCML_KEY = "input-tokenizer";

    AttributesImpl emptyAttributes = new AttributesImpl();

    /**
     * Holding place for an error during parsing. If one is
     *  throw internally, it goes here and is rethrown once
     *  the parse exits
     */
    SAXException errorHolder;

    Parser parser;
    String tokenizerFile;
    StreamTokenizer tokenizer;

    static Logger log = Logger.getLogger(ParserCocoonGenerator.class);

    public void configure(Configuration config)
        throws ConfigurationException
    {
        /*
        URLFactory urlFactory = null;
        Source configSource = null;
        try
        {
            urlFactory = (URLFactory)this.manager.lookup(URLFactory.ROLE);
            URLFactorySourceResolver urlResolver =
                new URLFactorySourceResolver(urlFactory, this.manager);
            configSource = urlResolver.resolve(configUrl);
            log.debug("Loading configuration from " + configSource.getSystemId());
            this.properties = new Properties();
            this.properties.load(configSource.getInputStream());
        }
        catch (Exception e)
        {
            log.warn("Cannot load configuration from " + configUrl);
            throw new ConfigurationException("Cannot load configuration from " + configUrl, e);
            }
        finally
        {
            this.manager.release(urlFactory);
            if (configSource != null)
            {
                configSource.recycle();
            }
        }
        */
        
        try
        {
            String sbnfTokenizerFile = config.getChild(SBNF_TCML_KEY).getValue(null);
            if(sbnfTokenizerFile == null)
            {
                throw new ConfigurationException("Must specify: " + SBNF_TCML_KEY);
            }
            
            String grammarFile = config.getChild(GRAMMAR_KEY).getValue(null);
            if(grammarFile == null)
            {
                throw new ConfigurationException("Must specify: " + GRAMMAR_KEY);
            }
            
            //String
            tokenizerFile = config.getChild(TCML_KEY).getValue(null);
            if(tokenizerFile == null)
            {
                throw new ConfigurationException("Must specify: " + TCML_KEY);
            }

            log.debug("Setting up tokenizer from:" +
                      " sbnf tokenizer = " + sbnfTokenizerFile +
                      " grammar = " + grammarFile +
                      " tokenizer = " + tokenizerFile);

            TokenizerFactory tokenizerFactory = new TokenizerFactory();

            StreamTokenizer sbnfTokenizer =
                tokenizerFactory.getTokenizer(sbnfTokenizerFile);
            
            parser = new LLParser();
            parser.addParseEventListener(this);
            parser.addParseErrorListener(this);

            sbnfTokenizer.setInputReader(new FileReader(grammarFile));

            Grammar grammar =
                new GrammarGenerator().generateGrammar(sbnfTokenizer);
            parser.setGrammar(grammar);
        }
        catch(SAXException saxe)
        {
            throw new ConfigurationException("Parse error", saxe);
        }
        catch(FileNotFoundException fnfe)
        {
            throw new ConfigurationException("File not found", fnfe);
        }
        catch(IOException ioe)
        {
            throw new ConfigurationException("IO Exception", ioe);
        }
    }

    public void setup(SourceResolver resolver, Map objectModel,
                      String source, Parameters parameters)
        throws ProcessingException, SAXException, IOException 
    {
        super.setup(resolver, objectModel, source, parameters);

        try
        {
            InputStreamReader reader =
                new InputStreamReader(resolver.resolve(source).getInputStream());
            
            tokenizer =
                new TokenizerFactory().getTokenizer(tokenizerFile);

            tokenizer.setInputReader(reader);

            log.debug("Preparing to parse: " + source +
                      " with" + 
                      (!tokenizer.hasNext() ? " empty" : "") +
                      " tokenizer " + tokenizer);
        }
        catch(FileNotFoundException fnfe)
        {
            throw new ProcessingException(fnfe);
        }
    }
    
    public synchronized void generate() throws SAXException
    {
        // contentHandler comes from the superclass
        errorHolder = null;
        try
        {
            log.debug("Starting parse with tokenizer: " +
                      tokenizer);
            parser.parse(tokenizer);
        }
        catch(ParseException pe)
        {
            if(errorHolder != null)
            {
                throw errorHolder;
            }
            else
            {
                throw new SAXException(pe);
            }
        }
        catch(IOException ioe)
        {
            if(errorHolder != null)
            {
                throw errorHolder;
            }
            else
            {
                throw new SAXException(ioe);
            }
        }
    }

    public void parseStarted() throws ParseException
    {
        try
        {
            log.debug("Firing document start");
            contentHandler.startDocument();
        }
        catch(SAXException saxe)
        {
            errorHolder = saxe;
            throw new ParseException(saxe);
        }
    }

    public void parseFinished() throws ParseException
    {
        try
        {
            log.debug("Firing document end");
            contentHandler.endDocument();
        }
        catch(SAXException saxe)
        {
            errorHolder = saxe;
            throw new ParseException(saxe);
        }
    }

    public void ruleStarted(String nonterminal) throws ParseException
    {
        startElement(nonterminal);
    }
    
    public void ruleFinished(String nonterminal) throws ParseException
    {
        endElement(nonterminal);
    }

    protected void startElement(String name) throws ParseException
    {
        try
        {
            name = name.replaceAll(" ", "-");
            log.debug("Firing element start: " + name);
            contentHandler.startElement("", name, name, emptyAttributes);
        }
        catch(SAXException saxe)
        {
            errorHolder = saxe;
            throw new ParseException(saxe);
        }
    }

    protected void endElement(String name) throws ParseException
    {
        try
        {
            name = name.replaceAll(" ", "-");
            log.debug("Firing element end: " + name);
            contentHandler.endElement("", name, name);
        }
        catch(SAXException saxe)
        {
            errorHolder = saxe;
            throw new ParseException(saxe);
        }
    }

    /**
     * The terminal is the only thing that does not have a direct
     * mapping. A terminal maps to the terminal value inside of
     * tags with the terminal name.
     */
    public void terminal(String terminalClass, String terminalValue)
        throws ParseException
    {
        startElement(terminalClass);
        try
        {
            contentHandler.characters(terminalValue.toCharArray(), 0,
                                      terminalValue.length());
        }
        catch(SAXException saxe)
        {
            errorHolder = saxe;
            throw new ParseException(saxe);
        }            
        endElement(terminalClass);
    }

    public void parseError(ParseError error) throws ParseException
    {
        log.error(error);
    }
}
