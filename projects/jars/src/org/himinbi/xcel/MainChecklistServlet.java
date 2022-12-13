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
 * Servlet to track if everything has been prepared to generate xml
 */
public class MainChecklistServlet
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

//         HttpSession session = request.getSession();

//         if("Restart".equals(request.getParameter("action"))) {
//             session.invalidate();
//         } else {
//             DictionaryLexer lexer =
//                 (DictionaryLexer)session.getAttribute("tokens");
//             if(lexer != null && lexer.ready()) {
//                 context.put("xcel_ok", new Boolean(true));
//             } else if(lexer != null) {
//                 context.put("xcel_check_needed", new Boolean(true));
//             }
//         }
        
//         String templateName = "index.vm";
        
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
