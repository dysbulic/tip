package org.himinbi.xcel;

/*
import java.io.IOException;

import java.text.SimpleDateFormat;
import java.text.DateFormat;

import javax.sql.DataSource;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import com.codestudio.sql.PoolMan;
import com.codestudio.util.JDBCPool;
import com.codestudio.util.SQLManager;

import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;
import java.util.Calendar;

import java.util.Stack;
import java.util.Vector;
import java.util.Collection;
import java.util.Enumeration;
import java.util.Iterator;
import java.util.SortedSet;
import java.util.HashMap;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.RequestDispatcher;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.velocity.app.VelocityEngine;
import org.apache.velocity.Template;
import org.apache.velocity.context.Context;
import org.apache.velocity.VelocityContext;
import org.apache.velocity.servlet.VelocityServlet;
import org.apache.velocity.app.Velocity;
import org.apache.velocity.exception.ResourceNotFoundException;
import org.apache.velocity.exception.ParseErrorException;
*/

/**
 * Servlet to manage the dictionary
*/
public class DictionaryManagerServlet
{
//     DataSource dataSource;
//     String databaseName = "dictionary";
    
//     public void init() throws ServletException {
//         try {
//             dataSource = PoolMan.findDataSource(databaseName);
//         } catch(SQLException e) {
//             throw new IllegalArgumentException(e.getMessage());
//         }
//     }
    
//     public Template handleRequest(HttpServletRequest request,
//                                   HttpServletResponse response,
//                                   Context context)
//         throws IOException, ServletException {
        
//         Stack errors = new Stack();
//         context.put("errors", errors);
        
//         if(request.getMethod().equals("POST")) {
//             String action = request.getParameter("action");
//             if("Add Word".equals(action)) {
//                 Connection connection = null;
//                 PreparedStatement statement = null;
//                 try {
//                     connection = dataSource.getConnection();
//                     statement =
//                         connection.prepareStatement
//                         ("insert into words (term, part_of_speech, definition) " +
//                          "values (?, ?, ?)");
//                     int type = Integer.parseInt(request.getParameter("part_of_speech"));
//                     if(type >= 0) {
//                         statement.setString(1, request.getParameter("word"));
//                         statement.setInt(2, type);
//                         statement.setString(3, request.getParameter("definition"));
//                         statement.executeUpdate();
//                     }
//                 } catch(SQLException sqle) {
//                     errors.push("SQL Error adding word: " + sqle);
//                 } finally {
//                     JDBCPool.closeStatement(statement);
//                     JDBCPool.closeConnection(connection);
//                 }
//             } else if("Add Part of Speech".equals(action)) {
//                 String partOfSpeech = request.getParameter("part_of_speech");
//                 if(partOfSpeech != null && partOfSpeech.length() > 0) {
//                     Connection connection = null;
//                     PreparedStatement statement = null;
//                     try {
//                         connection = dataSource.getConnection();
//                         statement =
//                             connection.prepareStatement
//                             ("insert into parts_of_speech " +
//                              "(type) values (?)");
//                         statement.setString(1, partOfSpeech);
//                         statement.executeUpdate();
//                     } catch(SQLException sqle) {
//                         errors.push("SQL Error adding part of speech: " + sqle);
//                     } finally {
//                         JDBCPool.closeStatement(statement);
//                         JDBCPool.closeConnection(connection);
//                     }
//                 }
//             } else if("Restart".equals(action)) {
//                 request.getSession().invalidate();
//                 errors.push("Session deleted");
//             } else {
//                 errors.push("Unrecognized action: " + action);
//             }
//         } else {
//             int REQUEST_PARAMETER = 0;
//             int TABLE_NAME = 1;
//             String[][] deletions = {
//                 { "delete_word", "words" },
//                 { "delete_part", "parts_of_speech" } };

//             for(int i = 0; i < deletions.length; i++) {
//                 String parameter =
//                     request.getParameter(deletions[i][REQUEST_PARAMETER]);
//                 if(parameter != null && parameter.length() > 0) {
//                     int id = Integer.parseInt(parameter);
//                     Connection connection = null;
//                     PreparedStatement statement = null;
//                     try {
//                         connection = dataSource.getConnection();
//                         statement =
//                             connection.prepareStatement
//                             ("delete from " + deletions[i][TABLE_NAME] + " " +
//                              "where id=?");
//                         statement.setInt(1, id);
//                         statement.executeUpdate();
//                     } catch(SQLException sqle) {
//                         errors.push("SQL Error removing: " + sqle);
//                     } finally {
//                         JDBCPool.closeStatement(statement);
//                         JDBCPool.closeConnection(connection);
//                     }
//                 }
//             }
//         }

//         Vector words = new Vector();
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select words.id, words.term, parts_of_speech.type, words.definition " +
//                  "from words, parts_of_speech " +
//                  "where words.part_of_speech = parts_of_speech.id");
//             results = statement.executeQuery();
//             while(results.next()) {
//                 HashMap term = new HashMap(4);
//                 term.put("id", new Integer(results.getInt(1)));
//                 term.put("term", results.getString(2));
//                 term.put("type", results.getString(3));
//                 term.put("definition", results.getString(4));
//                 words.add(term);
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retreiving words: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         context.put("words", words);

//         Vector partsOfSpeech = new Vector();
//         connection = null;
//         statement = null;
//         results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select id, type " +
//                  "from parts_of_speech");
//             results = statement.executeQuery();
//             while(results.next()) {
//                 HashMap term = new HashMap(2);
//                 term.put("id", new Integer(results.getInt(1)));
//                 term.put("name", results.getString(2));
//                 partsOfSpeech.add(term);
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retreiving parts of speech: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         context.put("parts_of_speech", partsOfSpeech);

//         String templateName = "dictionary.vm";
        
//         Template template = null;
//         try {
//             template = getTemplate(templateName);
//         } catch(ParseErrorException pee) {
//             System.out.println("Parse error for template: " + pee);
//         } catch(ResourceNotFoundException rnfe) {
//             System.out.println("Template not found: " + rnfe);
//         } catch(Exception e) {
//             System.out.println("Error: " + e);
//         }
//         return template;
//     }
}
