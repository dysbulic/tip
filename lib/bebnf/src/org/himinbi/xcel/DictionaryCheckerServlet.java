package org.himinbi.xcel;

/*
import java.io.StringReader;
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
import javax.servlet.http.HttpSession;
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
 * Servlet to manage a dictionary database
 */
public class DictionaryCheckerServlet
{
    /*
    DataSource dataSource;
    String databaseName = "dictionary";
    
    public void init() throws ServletException {
        try {
            dataSource = PoolMan.findDataSource(databaseName);
        } catch(SQLException e) {
            throw new IllegalArgumentException(e.getMessage());
        }
    }
    
    public Template handleRequest(HttpServletRequest request,
                                  HttpServletResponse response,
                                  Context context)
        throws IOException, ServletException {
        
        Stack errors = new Stack();
        context.put("errors", errors);

        HttpSession session = request.getSession();
        
        String document =
            (String)session.getAttribute("resource");
        if(document != null) {
            session.removeAttribute("resource");
            DictionaryLexer lexer = new DictionaryLexer();
            lexer.setInputReader(new StringReader(document));
            session.setAttribute("tokens", lexer);
        }

        DictionaryLexer lexer =
            (DictionaryLexer)session.getAttribute("tokens");
        
        if(lexer == null) {
            throw new ServletException("Document has not been set");
        }
        
        if(request.getMethod().equals("POST")) {
            String action = request.getParameter("action");
            if("Update Dictionary".equals(action)) {
                String[] words = request.getParameterValues("word");
                Connection connection = null;
                PreparedStatement statement = null;
                try {
                    connection = dataSource.getConnection();
                    statement =
                        connection.prepareStatement
                        ("insert into words (term, part_of_speech, definition) " +
                         "values (?, ?, ?)");
                    for(int i = 0; i < words.length; i++) {
                        int type = Integer.parseInt(request.getParameter(words[i] + "_type"));
                        if(type >= 0) {
                            statement.setString(1, words[i]);
                            statement.setInt(2, type);
                            statement.setString(3, request.getParameter(words[i] + "_definition"));
                            statement.executeUpdate();
                        }
                    }
                } catch(SQLException sqle) {
                    errors.push("SQL Error adding word: " + sqle);
                } finally {
                    JDBCPool.closeStatement(statement);
                    JDBCPool.closeConnection(connection);
                }
            } else if("Restart".equals(action)) {
                request.getSession().invalidate();
                errors.push("Session deleted");
            } else {
                errors.push("Unrecognized action: " + action);
            }
        }
                
        Collection unidentifiedWords = lexer.getUnidentifiedWords();
        if(unidentifiedWords.size() > 0) {
            context.put("words", unidentifiedWords);
            
            Vector partsOfSpeech = new Vector();
            Connection connection = null;
            PreparedStatement statement = null;
            ResultSet results = null;
            try {
                connection = dataSource.getConnection();
                statement =
                    connection.prepareStatement
                    ("select id, type " +
                     "from parts_of_speech");
                results = statement.executeQuery();
                while(results.next()) {
                    HashMap term = new HashMap(2);
                    term.put("id", new Integer(results.getInt(1)));
                    term.put("name", results.getString(2));
                    partsOfSpeech.add(term);
                }
            } catch(SQLException sqle) {
                errors.push("SQL Error retreiving parts of speech: " + sqle);
            } finally {
                JDBCPool.closeResources(connection, statement, results);
            }
            context.put("parts_of_speech", partsOfSpeech);
        }

        String templateName = "word_list.vm";
        
        Template template = null;
        try {
            template = getTemplate(templateName);
        } catch(ParseErrorException pee) {
            System.out.println("Parse error for template: " + pee);
        } catch(ResourceNotFoundException rnfe) {
            System.out.println("Template not found: " + rnfe);
        } catch(Exception e) {
            System.out.println("Error: " + e);
        }
        return template;
    }
    */
}
