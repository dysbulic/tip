package org.dhappy.mimis.cocoon;

import java.io.IOException;
import java.util.Map;
import java.util.Stack;

import org.apache.avalon.framework.parameters.Parameters;

import org.apache.cocoon.ProcessingException;
import org.apache.cocoon.generation.ServiceableGenerator;
import org.apache.cocoon.environment.SourceResolver;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory; 

import org.xml.sax.helpers.AttributesImpl;
import org.xml.sax.SAXException;

import org.neo4j.kernel.EmbeddedGraphDatabase;
import org.neo4j.graphdb.RelationshipType;
import org.neo4j.graphdb.GraphDatabaseService;
import org.neo4j.graphdb.Node;
import org.neo4j.graphdb.Relationship;
import org.neo4j.graphdb.Traverser;
import org.neo4j.graphdb.Traverser.Order;
import org.neo4j.graphdb.Transaction;
import org.neo4j.graphdb.StopEvaluator;
import org.neo4j.graphdb.ReturnableEvaluator;
import org.neo4j.graphdb.Direction;
import org.neo4j.graphdb.NotFoundException;
 
public class GraphWalkGenerator extends ServiceableGenerator {
    //implements CacheableProcessingComponent { 
    AttributesImpl emptyAttr = new AttributesImpl();
    String dbPath = "var/neo4j";
    GraphDatabaseService graphDb;

    public enum TagRelationshipTypes implements RelationshipType {
        CHILD, DOCUMENT
    }

    public void setup(SourceResolver resolver, Map objectModel, String src, Parameters params)
        throws ProcessingException, SAXException, IOException {
        super.setup(resolver, objectModel, src, params);
        graphDb = new EmbeddedGraphDatabase(dbPath);
    }

    public void recycle() {
        super.recycle();
        graphDb.shutdown();
    }

    public void generate() throws SAXException {
        Node doc;
        Transaction tx = graphDb.beginTx();
        
        try {
            Node root = graphDb.getReferenceNode();
            if( root.hasRelationship(TagRelationshipTypes.DOCUMENT) ) {
                doc = root.getRelationships(TagRelationshipTypes.DOCUMENT).iterator().next().getEndNode();
            } else {
                doc = graphDb.createNode();
                root.createRelationshipTo(doc, TagRelationshipTypes.DOCUMENT);
                Node child = graphDb.createNode();
                Relationship relationship = doc.createRelationshipTo(child, TagRelationshipTypes.CHILD);
            
                doc.setProperty("tag", "example");
                child.setProperty("data", "world");
                relationship.setProperty("owner", "will");
            }
            tx.success();
        } catch(NotFoundException nfe) {
            getLogger().error("Could not retrieve reference node for " + dbPath);
            throw new IllegalStateException(nfe);
        } finally {
            tx.finish();
        }

        contentHandler.startDocument();
        tx = graphDb.beginTx();
        Stack<String> pathToRoot = new Stack<String>();
        try {
            Traverser tree = doc.traverse(Order.DEPTH_FIRST,
                                          StopEvaluator.END_OF_GRAPH, ReturnableEvaluator.ALL,
                                          TagRelationshipTypes.CHILD, Direction.OUTGOING);
            for(Node child : tree) {
                if(child.hasProperty("tag")) {
                    String tagName = (String)child.getProperty("tag");
                    contentHandler.startElement("", tagName, tagName, emptyAttr);
                    pathToRoot.push(tagName);
                } else if(child.hasProperty("data")) {
                    String data = (String)child.getProperty("data");
                    contentHandler.characters(data.toCharArray(), 0, data.length());
                }
            }
            tx.success();
            for(String tagName : pathToRoot) {
                contentHandler.endElement("", tagName, tagName);
            }
        } finally {
            tx.finish();
        }
        contentHandler.endDocument();
    }
}
