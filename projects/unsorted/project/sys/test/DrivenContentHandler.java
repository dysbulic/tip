/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: February 2008
 *
 * Attempt at a fake content handler to generate some XML without
 * building a tree in memory.
 *
 * Uses StAX API from: http://stax.codehaus.org
 */

import javax.xml.stream.XMLOutputFactory;
import javax.xml.stream.XMLStreamWriter;
import javax.xml.stream.XMLStreamException;
import java.io.OutputStream;
//import java.io.PrintOutputStream;
import java.text.SimpleDateFormat;
import java.util.Date;
import javax.xml.transform.OutputKeys;

public class DrivenContentHandler {
    public static void printElement(XMLStreamWriter staxWriter, String tag,
                                    String content)
        throws XMLStreamException {
        staxWriter.writeStartElement(tag);
        staxWriter.writeCharacters(content);
        staxWriter.writeEndElement();
    }

    public static void main(String[] args) throws XMLStreamException {
        //OutputStream out = new PrintOutputStream(System.out);
        XMLOutputFactory factory = XMLOutputFactory.newInstance();
        if(factory.isPropertySupported(OutputKeys.INDENT)) {
            factory.setProperty(OutputKeys.INDENT, "yes");
        }
        XMLStreamWriter staxWriter = factory.createXMLStreamWriter(System.out);

        staxWriter.writeStartDocument("UTF-8", "1.0");
        staxWriter.writeStartElement("feed");
        staxWriter.writeNamespace("", "http://www.w3.org/2005/Atom");
        printElement(staxWriter, "title", "Simple Atom Feed File");
        printElement(staxWriter, "subtitle", "Using StAX to read feed files");
        staxWriter.writeStartElement("link");
        staxWriter.writeAttribute("href","http://example.org/");
        staxWriter.writeEndElement();
        printElement(staxWriter, "updated",
                     new SimpleDateFormat().format(new Date(System.currentTimeMillis())));
        staxWriter.writeEndElement(); // end feed
        staxWriter.writeEndDocument();
        staxWriter.flush();
        staxWriter.close();
    }
}
