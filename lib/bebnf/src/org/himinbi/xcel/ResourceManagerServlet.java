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
import java.util.Collection;
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
 * Servlet to manage a set of text resources
*/
public class ResourceManagerServlet
{
//     DataSource dataSource;
//     String databaseName = "dictionary";

//     HashMap validators = new HashMap();

//     public void init() throws ServletException {
//         try {
//             dataSource = PoolMan.findDataSource(databaseName);
//         } catch(SQLException e) {
//             throw new IllegalArgumentException(e.getMessage());
//         }

//         /**
//          * This is a list of resourcetypes and the associated
//          * servlet resources for validating them.
//          */
//         validators.put("xcel", "/dictionarycheck");
//         validators.put("grammar", "/grammarcheck");
//         validators.put("actions", "/actioncheck");
//     }
    
//     public Template handleRequest(HttpServletRequest request,
//                                   HttpServletResponse response,
//                                   Context context)
//         throws IOException, ServletException {
        
//         Stack errors = new Stack();
//         context.put("errors", errors);

//         String resourceID = request.getParameter("resource_id");
//         String resourceType = request.getParameter("resource_type");

//         /**
//          * If the page has parameters there are four options:
//          *  1. Save the text as a project (action = "Save Resource")
//          *  2. Proceed to the validator (action = "Use Resource")
//          *  3. Delete a project (action = "delete" and resource_id != null)
//          *  4. Load a project (resource_id != null)
//          *
//          * Otherwise list the available projects. Either of a specific type
//          *  (resource_type != null) or all types.
//          */
//         String action = request.getParameter("action");
//         if("Use Resource".equals(action)) {
//             forwardToValidator(request, response, errors);
//             if(errors.size() == 0) {
//                 return null;
//             }
//         } else if("Save Resource".equals(action)) {
//             String[] args = { "name",
//                               "description",
//                               "text" };
//             String[] values = new String[args.length];

//             for(int i = 0; i < args.length; i++) {
//                 values[i] = request.getParameter(args[i]);
//                 context.put(args[i], values[i]);
//             }

//             if(resourceID == null) {
//                 resourceID = addResource(values[0], values[1], values[2],
//                                          resourceType, errors);
//             } else {
//                 overwriteResource(resourceID,
//                                   values[0], values[1], values[2],
//                                   errors);
//             }
//         } else if("delete".equals(action)) {
//             deleteResource(resourceID, errors);
//             resourceID = null;
//         } else if(resourceID != null) {
//             loadResource(resourceID, context, errors);
//         }

//         context.put("resource_id", resourceID);

//         Collection resources =
//             listResources(resourceType, errors);
        
//         if(resources.size() > 0) {
//             context.put("resources", resources);
//         }

//         if(resourceType == null) {
//             context.put("resource_types", listResourceTypes(errors));
//         } else {
//             context.put("resource_type", resourceType);
//         }

//         String templateName = "resources.vm";
        
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
    
//     protected void forwardToValidator(HttpServletRequest request, 
//                                       HttpServletResponse response,
//                                       Stack errors) {
//         String resourceType = request.getParameter("resource_type");
//         String validator = (String)validators.get(resourceType);

//         HttpSession session = request.getSession();
//         session.setAttribute("resource",
//                              request.getParameter("text"));

//         RequestDispatcher dispatcher =
//             getServletContext().getRequestDispatcher(validator);
//         if(dispatcher != null) {
//             try {
//                 dispatcher.forward(request, response);
//             } catch(Exception e) {
//                 errors.push("Error forwarding: " + e);
//             }
//         } else {
//             errors.push("Unable to get dispatcher for " + validator);
//         }
//     }
    
//     protected String addResource(String name,
//                                  String description,
//                                  String text,
//                                  String resourceType,
//                                  Stack errors) {
//         int resourceTypeID = getResourceTypeID(resourceType, errors);
//         if(resourceTypeID < 0) {
//             throw new IllegalArgumentException("Resource type not set");
//         }

//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         String resourceID = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("insert into projects (name, description, text, resource_type) " +
//                  "values (?, ?, ?, ?)");
                
//             String[] args = { name, description, text };
//             for(int i = 0; i < args.length; i++) {
//                 if(args[i].length() == 0) {
//                     args[i] = null;
//                 }
//                 statement.setString(i + 1, args[i]);
//             }
//             statement.setInt(4, resourceTypeID);
//             statement.executeUpdate();

//             statement.close();

//             /**
//              * There is a race condition here and this should
//              * be replaced with the proper code using the
//              * jdbc 3.0 api when drivers are avaialable
//              */
//             statement =
//                 connection.prepareStatement
//                 ("select id from projects " +
//                  "where name=? order by id desc");
//             statement.setString(1, name);
//             results = statement.executeQuery();
//             results.next();
//             resourceID = results.getString(1);
//         } catch(SQLException sqle) {
//             errors.push("SQL Error adding project: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return resourceID;
//     }

//     protected void overwriteResource(String resourceID,
//                                      String name,
//                                      String description,
//                                      String text,
//                                      Stack errors) {
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("update projects " +
//                  "set name=?, description=?, text=? " +
//                  "where id=?");
                
//             String[] args = { name, description, text, resourceID };
//             for(int i = 0; i < args.length; i++) {
//                 if(args[i].length() == 0) {
//                     args[i] = null;
//                 }
//                 statement.setString(i + 1, args[i]);
//             }
//             statement.executeUpdate();
//         } catch(SQLException sqle) {
//             errors.push("SQL Error adding project: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//     }

//     protected void deleteResource(String resourceID, Stack errors) {
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("delete from projects " +
//                  "where id=?");
//             statement.setString(1, resourceID);
//             statement.executeUpdate();
//         } catch(SQLException sqle) {
//             errors.push("SQL Error removing project: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//     }
    
//     protected void loadResource(String resourceID,
//                                   Context context,
//                                   Stack errors) {
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select projects.name, projects.description, " +
//                  "projects.text, resource_types.name " +
//                  "from projects, resource_types " +
//                  "where projects.id = ? " +
//                  "and projects.resource_type = resource_types.id");
//             statement.setString(1, resourceID);
//             results = statement.executeQuery();
//             if(!results.next()) {
//                 errors.push("Error: no projects found for id: " + resourceID);
//             } else {
//                 context.put("name", results.getString(1));
//                 context.put("description", results.getString(2));
//                 context.put("text", results.getString(3));
//                 context.put("resource_type", results.getString(4));
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retrieving project: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//     }        

//     protected Collection listResources(String resourceType,
//                                        Stack errors) {
//         Vector resources = new Vector();
//         boolean resourcePresent = resourceType != null && resourceType.length() > 0;

//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             StringBuffer sql = new StringBuffer();
//             sql.append("select projects.id, projects.entry_time, ");
//             sql.append("projects.name, projects.description, resource_types.name ");
//             sql.append("from projects, resource_types ");
//             sql.append("where projects.resource_type = resource_types.id ");

//             if(resourcePresent) {
//                 sql.append("and resource_types.name = ? ");
//             }
            
//             sql.append("order by id desc");

//             connection = dataSource.getConnection();
//             statement = connection.prepareStatement(sql.toString());
                
//             if(resourcePresent) {
//                 statement.setString(1, resourceType);
//             }

//             results = statement.executeQuery();
//             while(results.next()) {
//                 HashMap project = new HashMap(4);
//                 project.put("id", results.getString(1));
//                 project.put("creationTime", results.getDate(2));
//                 project.put("name", results.getString(3));
//                 project.put("description", results.getString(4));
//                 project.put("type", results.getString(5));
                
//                 String query = "resource_id=" + project.get("id");
//                 if(resourcePresent) {
//                     query += "&resource_type=" + resourceType;
//                 }
//                 project.put("query", query);

//                 resources.add(project);
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retrieving projects: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return resources;
//     }

//     protected Collection listResourceTypes(Stack errors) {
//         Vector resourceTypes = new Vector();
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select id, name from resource_types");
                
//             results = statement.executeQuery();
//             while(results.next()) {
//                 resourceTypes.add(results.getString(2));;
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retrieving types: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return resourceTypes;
//     }

//     protected int getResourceTypeID(String resourceTypeName,
//                                     Stack errors) {
//         int id = -1;
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select id from resource_types " +
//                  "where name = ?");
//             statement.setString(1, resourceTypeName);
//             results = statement.executeQuery();
//             if(results.next()) {
//                 id = results.getInt(1);
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retrieving type id: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return id;
//     }

//     protected String getResourceTypeName(String resourceType,
//                                          Stack errors) {
//         String name = null;
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select name from resource_types " +
//                  "where id = ?");
//             statement.setString(1, resourceType);
//             results = statement.executeQuery();
//             if(results.next()) {
//                 name = results.getString(1);
//             }
//         } catch(SQLException sqle) {
//             errors.push("SQL Error retrieving type name: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return name;
//     }
 }
