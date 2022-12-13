package org.himinbi.velocity;

import java.io.IOException;
import java.io.FileNotFoundException;
import java.io.FileInputStream;
import java.io.File;

import java.util.Properties;
import java.util.Enumeration;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.RequestDispatcher;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.velocity.servlet.VelocityServlet;
import org.apache.velocity.app.Velocity;
import org.apache.velocity.context.Context;

/**
 * Servlet base which loads configuration infomation
 */
public class ConfiguredVelocityServlet extends VelocityServlet {
    protected Properties loadConfiguration(ServletConfig config)
        throws IOException, FileNotFoundException {
        String propertiesFile = config.getInitParameter(INIT_PROPS_KEY);
        Properties properties = new Properties();

        if(propertiesFile != null) {
            String realPath =
                getServletContext().getRealPath(propertiesFile);
            if(realPath != null) {
                propertiesFile = realPath;
            }
            properties.load(new FileInputStream(propertiesFile));
        }

        setPropertyPath(properties,
                        Velocity.RUNTIME_LOG);
        setPropertyPath(properties,
                        Velocity.FILE_RESOURCE_LOADER_PATH);
        return properties;
    }

    protected void setPropertyPath(Properties properties, String key) {
        String path = properties.getProperty(key);
        if(path != null) {
            path = getServletContext().getRealPath(path);
            if(path != null) {
                properties.setProperty(key, path);
            }
        }
    }

    protected void forwardToLogin(HttpServletRequest request,
                                  HttpServletResponse response,
                                  Context context)
        throws ServletException, IOException {
        RequestDispatcher dispatcher =
            getServletContext().getRequestDispatcher("/login");
        if(dispatcher != null) {
            dispatcher.forward(request, response);
        } else {
            throw new NullPointerException("Unable to get dispatcher for /login");
        }
    }
    
    public static void resendParameters(HttpServletRequest request,
                                        Context context) {
        Enumeration parameters = request.getParameterNames();
        while(parameters.hasMoreElements()) {
            String parameter = (String)parameters.nextElement();
            context.put(parameter.replace('.', '_'), request.getParameter(parameter));
        }
    }
}
