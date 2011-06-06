package jhu.welch.atis.utils.httpchain;

import java.io.File;
import java.io.InputStream;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import java.net.UnknownHostException;

import javax.xml.bind.JAXBException;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.Unmarshaller;

import jhu.welch.atis.utils.FileIO;
import jhu.welch.atis.utils.XMLBeanLoader;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpRequestBase;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.BasicCookieStore;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.apache.log4j.Logger;

/**
 * HttpChainsCaller automated a HTTP client access. 
 * It executes a chain of URLs based on a httpchains.xml file.  
 * 
 *  <httpchains>
 *    <chains>
 *      <chain>
 *        <enabled>true/false</enabled>
 *        <url>TARGET_URL</url>
 *        <posts>
 *          <post>
 *            <key>KEY_1</key>
 *            <value>VALUE_1</value>
 *          </post>
 *          <post>
 *            <key>KEY_2</key>
 *            <value>VALUE_2</value>
 *          </post>
 *        </posts>            
 *        <postRedirect>true/false</postRedirect>            
 *        <saveContentFile>SAVEED_CONTENT</saveContentFile>
 *        <wait>WAIT_TIME_BEFORE_NEXT_REQUEST</wait>          
 *        <repeat>REPEAT_URL_REQUEST</repeat>
 *      </chain>
 *      <chain>...</chain>
 *    </chains>
 *  </httpchains>
 *  
 *  REMARK: 
 *  <repeat> tag available only usable in runall() API.  
 * 
 * @author fwong3 
 */
public class HttpChainsCaller {
    private Logger log = Logger.getLogger( this.getClass() );
    
    private HttpClient httpclient = null;    

    private final String VARSEPERATOR = "@";

    private final HashMap<String,String> varUrlMap = new HashMap<String, String>();
    
    private HttpUrlChainBean httpUrlChains = null;
    
    private ArrayList<UrlChainBean> urlChainBeans = null;
    
    private UrlChainBean currentUrlChain = null;

    private int chainPosition = -1;
    
    private String currentContent = null;

    /**
     * @return HttpChainCaller Return an HttpChainCaller object
     */
    public static HttpChainsCaller getInstance() {
        return new HttpChainsCaller();
    }

    /**
     * Private constructor
     */
    private HttpChainsCaller() {
        open();        
    }

    /**
     * Load URL chains xml file.
     * 
     * @param inConfig urlchains.xml file
     */
    public void loadConfig( String configFile ) {
        if( configFile == null ) {
            throw new IllegalArgumentException( "configuration cannot be null" );
        }

        //XMLBeanLoader xmlBeanLoader = XMLBeanLoader.getInstance();
        //Object obj = xmlBeanLoader.loadBean( HttpUrlChainBean.class, configFile );
        //httpUrlChains = (HttpUrlChainBean)obj;

        InputStream input = getClass().getResourceAsStream( configFile );
        if( input == null ) { // Check if resource is in jar
            input = getClass().getResourceAsStream( "/" + configFile );
        }
        if( input == null ) {
            throw new IllegalArgumentException( "could not locate resource: " + configFile );
        }

        try {
            Unmarshaller unmarshaller = JAXBContext.newInstance( HttpUrlChainBean.class ).createUnmarshaller();
            httpUrlChains = (HttpUrlChainBean)unmarshaller.unmarshal( input );
            urlChainBeans = httpUrlChains.getAChains();
        } catch( JAXBException e ) {
            log.error( e );
        }
    }

    /**
     * Move to the next item in a chain
     * 
     * @return boolean Return , if there is a next item.  Otherwise return 0.
     */
    public boolean next() {
        if( urlChainBeans.size() > ++chainPosition ){ 
            setCurrentChain();
            return true;
        }

        return false;
    }
    
    /**
     * Reload current URL chain.  It can use to re-run an executed URL chain.
     */
    public void reloadChain() {
        if( urlChainBeans.size() > chainPosition ) {
            setCurrentChain();
        }
    }
    
    /**
     * setup current url chain.
     */
    private void setCurrentChain() {
        currentUrlChain = urlChainBeans.get( chainPosition );
    }

    /**
     * Call the current url in a chain
     */
    public void call() {
        if( currentUrlChain == null ) {
            return;
        }

        // Reset current content
        currentContent = null;
        
        if( currentUrlChain.isEnabled() ) {
            try {
                // Enable redirect in a post request.  
                // REMARK: RFC standard does not allow redirect on a post request.
                if( currentUrlChain.isPostRedirect() ) {
                   ((DefaultHttpClient)httpclient).setRedirectStrategy( new CustomRedirectStrategy() );
                }
                
                // setup get/post HTTPRequest based on current URL Chain
                HttpRequestBase httpRequest = getHttpRequest( currentUrlChain );
                
                // Execute a HTTP request
                HttpResponse response = httpclient.execute( httpRequest );
                
                log.debug( String.format( "Status Code: %s", response.getStatusLine().getStatusCode() ) );
                
                // Handle http client requested content
                handleHttpContent( response.getEntity() );

                // sleep for a moment as need before the next request
                waiting( currentUrlChain );
            } catch( ClientProtocolException e ) {
                String msg = ( "Failed to load url: "
                               + currentUrlChain.getUrl()
                               + " with message: " + e.getMessage() );
                log.error( msg, e );
                throw new RuntimeException( msg, e );
            } catch( UnknownHostException e ) {
                log.error( "Unknown Host: " + e.getMessage() );
            } catch( IOException e ) {
                String msg = ( "Failed to load url: "
                               + currentUrlChain.getUrl()
                               + " with message: " + e.getMessage() );
                log.error( msg, e );
                throw new RuntimeException( msg, e );
            }
        }
        
        // Reset currentUrlChain
        currentUrlChain = null;
    }
    
    /**
     * Handle httpclient entity content
     * 
     * @param entity HttpEntity object
     */
    private void handleHttpContent( HttpEntity entity ) {
        log.debug( "handleHttpContent" );
        if( entity != null ) {
            try {
                currentContent = EntityUtils.toString( entity );

                if( currentUrlChain.needSaveContent() ) {
                    String filename = currentUrlChain.getSaveContentFile();
                    File output = new File( filename ); 

                    log.debug( "handleHttpContent: " + filename );

                    if( output.isDirectory() ) {
                        log.error( filename + " is a directory" );
                    } else {
                        FileIO.Write( filename, currentContent );    
                    }
                }
            } catch( ParseException e ) {
                String msg = "Failed to load content: " + e.getMessage();
                log.error( msg, e );
                throw new RuntimeException( msg, e );
            } catch( IOException e ) {
                String msg = "Failed to load content: " + e.getMessage();
                log.error( msg, e );
                throw new RuntimeException( msg, e );                
            }
        }
    }

    /**
     * close an httpclient connection.
     */
    public void shutdown() {
        if( httpclient != null ) {
            httpclient.getConnectionManager().shutdown();
            httpclient = null;
        }
    }
    
    /**
     * Open a httpclient connection.
     */
    public void open() {
        shutdown(); // close any active connections
        httpclient = new DefaultHttpClient();        
        ((DefaultHttpClient)httpclient).setCookieStore( new BasicCookieStore() );
    }
    
    /**
     * Get a HttpGet or HttpPost object base on the url request
     * 
     * @param aChain A ChainBean object
     * 
     * @return HttpReqhestBase Return HttpGet or HttpPost object base on the url request
     */
    private HttpRequestBase getHttpRequest( UrlChainBean chain ) {
        return getHttpRequest( currentUrlChain.getUrl(), 
                               currentUrlChain.needPostMethod(), 
                               getPostData( currentUrlChain.getAposts() ) );
    }
    
    /**
     * Get a HttpGet or HttpPost object base on the url request
     * 
     * @param aURL
     * @param isPost
     * @param urlEncodedFormEntity 
     * 
     * @return HttpReqhestBase Return HttpGet or HttpPost object base on the url request
     */
    private HttpRequestBase getHttpRequest( String url,
                                            boolean isPost,
                                            HttpEntity postData ) {
        if( url == null ) { 
            throw new IllegalArgumentException( "URL cannot be null" );
        }
        
        HttpRequestBase request = null;
        
        String targetURL = parseDynamicURL( url );
        
        log.debug( String.format( "[%s]: %s", 
                                  currentUrlChain.isEnabled() ? "ENABLED" : "DISABLED",
                                  currentUrlChain.getUrl() ) );
        log.debug( "Parsed URL: " + targetURL );
        
        if( isPost ) {
            request = new HttpPost( targetURL );
            ((HttpPost)request).setEntity( postData );
        } else {
            request = new HttpGet( url );
        }
        
        return request;
    }
    
    /**
     * Dynamically update a URL string.  Replace first matching dynamic variable in a url string.
     * 
     * @return String A parsed url string
     */
    private String parseDynamicURL( String url ) {
        String newURL = url;
        
        for( String key : varUrlMap.keySet().toArray( new String[ varUrlMap.size() ] ) ) {
            String value = varUrlMap.get( key );
            String matcher = String.format( "%s%s%s", VARSEPERATOR, key, VARSEPERATOR );
            newURL = newURL.replaceFirst( matcher, value );
        }
        
        return newURL;
    }
    
    /**
     * Wait for a specific amount of time defined in a ChainBean.
     * 
     * @param UrlChainBean A ChainBean
     */
    private void waiting( UrlChainBean aChain ) {
        log.debug( String.format( "wait time: " + aChain.getWait() ) );
        
        try {
            Thread.sleep( aChain.getWait() );
        } catch( InterruptedException e ) {
        }
    }
    
    /**
     * 
     * @param posts An ArrayList of PostBean
     * 
     * @return UrlEncodedFormEntity Return an UrlEncodedFormEntity object.  Otherwise return null. 
     */
    protected HttpEntity getPostData( ArrayList<PostBean> posts ) {
        MultipartEntity entity = null;
        
        if( ! posts.isEmpty() ) {
            List<NameValuePair> formParams = new ArrayList<NameValuePair>();

            entity = new MultipartEntity();

            for( PostBean pb : posts ) {
                //formParams.add( new BasicNameValuePair( pb.getKey(), pb.getValue() ) );
                try {
                    if( pb.getValue() != null ) {
                        entity.addPart( pb.getKey(), new StringBody( pb.getValue(), FileIO.UTF8 ) );
                    }
                    if( pb.getFile() != null ) {
                        entity.addPart( pb.getKey(), new FileBody( new File( pb.getFile() ) ) );
                    }
                } catch( UnsupportedEncodingException e ) {
                    log.error( e );
                }
            }
        }
        
        return entity;
    }
    
    
    /**
     * Run all url defined in a urlchains.xml file.
     * 
     */
    public void runall() {
        while( next() ) {
            int repeatCount = currentUrlChain.getRepeat();
            while( repeatCount-- > 0 ) {
                reloadChain();
                call();
            }
        }
    }
    
    /**
     * Add variable url key-value pairs mapping.  All keys are converted to lower case. 
     * 
     * @param key Variable url key
     * @param value Variable url value
     */
    public void putUrlVar( String key, String value ) {
        if( key == null ) { 
            throw new IllegalArgumentException( "key cannot be null" );
        }

        varUrlMap.put( key.toLowerCase(), value );
    }
    
    /**
     * Remove variable url key-value pairs mapping 
     * 
     * @param key Variable url key
     * 
     */    
    public void removeUrlVar( String key ) {
        if( key == null ) { 
            throw new IllegalArgumentException( "key cannot be null" );
        }
        
        varUrlMap.remove( key.toLowerCase() );
    }

    /**
     * Get current downloaded content.
     * 
     * @return String Return a downloaded content from a URL.
     */
    public String getCurrentContent() {
        return currentContent;
    }
}
