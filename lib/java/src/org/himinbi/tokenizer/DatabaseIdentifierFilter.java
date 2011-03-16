package org.himinbi.tokenizer;

import java.util.List;
import java.util.ArrayList;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.DriverManager;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

public class DatabaseIdentifierFilter extends TokenizerBase
    implements TokenFilter
{
    TokenStream incomingStream;
    String incomingType;
    String unknownType;
    List incomingElements;
    Token holdingToken;
    
    Connection connection;
    PreparedStatement statement;

    String sql;
    String url;
    String username;
    String password;

    static Logger log =
        Logger.getLogger(DatabaseIdentifierFilter.class);

    public DatabaseIdentifierFilter()
    {
    }
    
    public void initialize()
    {
        try
        {
            connection =
                DriverManager.getConnection(url, username, password);
            statement = connection.prepareStatement(sql);
        }
        catch(SQLException sqle)
        {
            IllegalStateException ise =
                new IllegalStateException("Exception thrown: " + sqle);
            ise.initCause(sqle);
            throw ise;
        }
    }
    
    public void setIncomingType(String incomingType)
    {
        this.incomingType = incomingType;
    }
    
    public void setUrl(String url)
    {
        this.url = url;
    }

    public void setUsername(String username)
    {
        this.username = username;
    }

    public void setPassword(String password)
    {
        this.password = password;
    }

    public void setSql(String sql)
    {
        this.sql = sql;
    }

    public void setUnknownType(String unknownType)
    {
        this.unknownType = unknownType;
    }

    public void setDriver(String driver)
    {
        try
        {
            Class.forName(driver);
        }
        catch(ClassNotFoundException cnfe)
        {
            IllegalArgumentException iae =
                new IllegalArgumentException("Could not load driver");
            iae.initCause(cnfe);
            throw iae;
        }
    }

    public void setIncomingStream(TokenStream incomingStream)
    {
        this.incomingStream = incomingStream;
    }

    protected void queueToken()
    {
        if(statement == null)
        {
            throw new IllegalStateException("Not initialized");
        }

        Token testToken = incomingStream.nextToken();
        if(incomingStream.hasNext())
        {
            if(testToken.getType().equals(incomingType))
            {
                try
                {
                    statement.setString(1, testToken.getValue());
                    ResultSet results = statement.executeQuery();
                    if(!results.next())
                    {
                        if(unknownType != null)
                        {
                            testToken.setType(unknownType);
                        }
                        else
                        {
                            log.warn("No result for: " + testToken.getValue());
                        }
                    }
                    else
                    {
                        String value = results.getString(1);
                        testToken.setType(value);
                    }
                }
                catch(SQLException sqle)
                {
                    IllegalStateException ise =
                        new IllegalStateException("Exception thrown");
                    ise.initCause(sqle);
                    throw ise;
                }
            }
        }
        setNextToken(testToken);
            
        return;
    }
}
