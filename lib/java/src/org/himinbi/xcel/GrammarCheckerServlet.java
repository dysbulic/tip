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

import java.io.FileReader;
import java.io.StringWriter;
import java.io.BufferedReader;
import java.io.PrintWriter;
*/
/**
 * Servlet to check a grammar
 */
public class GrammarCheckerServlet
{
//     DataSource dataSource;
//     String grammarString;
//     Grammar grammar = GrammarGenerator.ebnfGrammar();
//     LLParser parser = new LLParser();
//     Stack errors = new Stack();
    
//     public void init() throws ServletException {
//         String path = getServletContext().getRealPath("docs/iso_ebnf.txt");
//         try {
//             BufferedReader reader = new BufferedReader(new FileReader(path));
//             StringWriter string = new StringWriter();
//             PrintWriter writer = new PrintWriter(string);
//             String nextLine;
//             while((nextLine = reader.readLine()) != null) {
//                 writer.println(nextLine);
//             }
//             grammarString = string.toString();
//         } catch(IOException ioe) {
//             throw new ServletException("IO Error: " + ioe);
//         }
        
//         try {
//             parser.setGrammar(grammar);
//         } catch(Throwable t) {
//             errors.push("Error loading grammar: " + t);
//         }
//     }
    
//     public Template handleRequest(HttpServletRequest request,
//                                   HttpServletResponse response,
//                                   Context context)
//         throws IOException, ServletException {
        
//         context.put("errors", errors);
//         context.put("grammar", grammarString);
//         context.put("rules", grammar.getRules());
        
//         String templateName = "grammar.vm";
        
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
