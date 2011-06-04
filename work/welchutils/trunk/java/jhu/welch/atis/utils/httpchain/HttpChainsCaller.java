package jhu.welch.atis.utils.httpchain;

import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

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
import org.apache.http.impl.client.BasicCookieStore;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.apache.log4j.Logger;


/**
 * 
 * HttpChainsCaller automated a HTTP client access. 
 * It executes a chain of URLs based on a httpchains.xml file.  
 * 
 *
 *  <httpchains>
 *     <chains>
 *      <chain>
 *			<enabled>true/false</enabled>
 *			<url>TARGET_URL</url>
 *			<posts>
 *				<post>
 *					<key>KEY_1</key>
 *					<value>VALUE_1</value>
 *				</post>
 *				<post>
 *					<key>KEY_2</key>
 *					<value>VALUE_2</value>
 *				</post>
 *			</posts>			
 *          <postRedirect>true/false</postRedirect>			
 *			<saveContentFile>SAVEED_CONTENT</saveContentFile>
 *			<wait>WAIT_TIME_BEFORE_NEXT_REQUEST</wait>          
 *          <repeat>REPEAT_URL_REQUEST</repeat>
 *		</chain>
 *      <chain>
 *      .
 *      .
 *      .
 *      </chain>
 *	   </chains>
 *  </httpchains>
 *  
 *  REMARK: 
 *  <repeat> tag available only usable in runall() API.  
 * 
 * 
 * 
 * @author fwong3 
 *
 */
public class HttpChainsCaller {

	private Logger log = Logger.getLogger(this.getClass().getName());
	
	
	private HttpClient httpclient = null;	
	
	private final String VARSEPERATOR = "@";
	
	private final HashMap<String,String> varUrlMap = new HashMap<String, String>();
	
	private HttpUrlChainBean httpUrlChains = null;
	
	private ArrayList<UrlChainBean> urlChainBeans = null;
	
	private UrlChainBean currentUrlChain = null;

	private int chainPosition = -1;
	
	private String currentContent = null;
	
	

	/**
	 * 
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
	public void loadConfig(String inConfig) {
		
		if (inConfig == null){
			throw new IllegalArgumentException("inconfig cannot be null!");
		}

		XMLBeanLoader xmlBeanLoader = XMLBeanLoader.getInstance();
		Object obj = xmlBeanLoader.loadBean(HttpUrlChainBean.class, inConfig);

		httpUrlChains = (HttpUrlChainBean) obj;
		urlChainBeans = httpUrlChains.getAChains();

	}

	/**
	 * Move to the next item in a chain
	 * 
	 * @return int Return 1, if there is a next item.  Otherwise return 0.
	 * 
	 */
	public int next() {
		chainPosition++;
		if (urlChainBeans.size() > chainPosition){ 
			setCurrentChain();
		} else { 
			return 0;
		}
		
		return 1; 
	}
	
	/**
	 * Reload current URL chain.  It can use to re-run an executed URL chain.
	 * 
	 */
	public void reloadChain(){ 
		if (urlChainBeans.size() > chainPosition){ 
			setCurrentChain();
		}
	}
	
	/**
	 * setup current url chain.
	 */
	private void setCurrentChain(){ 
		currentUrlChain = urlChainBeans.get(chainPosition);
	}

	/**
	 * Call the current url in a chain
	 */
	public void call() {

		if (currentUrlChain == null){
			return; 
		}

		// Reset current content
		currentContent = null;
		
		if (currentUrlChain.isEnabled()) {
			try {
				
				// Enable redirect in a post request.  
				// REMARK: RFC standard does not allowed redirect on a post request.
				if (currentUrlChain.isPostRedirect()) {
				   ((DefaultHttpClient)httpclient).setRedirectStrategy(new CustomRedirectStrategy());
				}
				
				// setup get/post HTTPRequest based on current URL Chain
				HttpRequestBase httpRequest = getHttpRequest(currentUrlChain);
				
				// Execute a HTTP request
				HttpResponse response = httpclient.execute(httpRequest);
				
				if (log.isDebugEnabled()){
	               log.debug(String.format("Status Code: %s", response.getStatusLine().getStatusCode()));
				}		
				
				// Handle http client requested content
				handleHttpContent(response.getEntity());

				// sleep for a moment as need before the next request
				waiting(currentUrlChain);

			} catch (ClientProtocolException e) {
				e.printStackTrace();
				String errMsg = "Failed to load url: " + currentUrlChain.getUrl() + " with message: " + e.getMessage();
				log.error(errMsg, e);
				throw new RuntimeException(errMsg, e);

			} catch (IOException e) {
				e.printStackTrace();
				String errMsg = "Failed to load url: " + currentUrlChain.getUrl() + " with message: " + e.getMessage();
				log.error(errMsg, e);
				throw new RuntimeException(errMsg, e);

			} // END TRY/CATCH			
		}// END if (currentUrlChain.isEnabled())
		
		// Reset currentUrlChain
		currentUrlChain = null;
		
	}
	
	/**
	 * Handle httpclient entity content
	 * 
	 * @param entity HttpEntity object
	 */
	private void handleHttpContent(HttpEntity entity) {
		
		// If entity is not null
		if (entity != null) {
			try {

				currentContent = EntityUtils.toString(entity);

				//  If needs to preserve the content.
				if (currentUrlChain.needSaveContent()) {
					String fileName = currentUrlChain.getSaveContentFile();
					File aFile = new File(fileName);
					if (aFile.isDirectory()){ 
						System.out.println("It is a directory");
					} else {
						FileIO.getInstance().Write(fileName, currentContent);	
					}
					
				}
				
			} catch (ParseException e) {
				e.printStackTrace();
				String errMsg = "Failed to load content with message: " + e.getMessage();
				log.error(errMsg, e);
				throw new RuntimeException(errMsg, e);
				
			} catch (IOException e) {
				e.printStackTrace();
				String errMsg = "Failed to load content with message: " + e.getMessage();
				log.error(errMsg, e);
				throw new RuntimeException(errMsg, e);				
			}

		}
	}

	/**
	 * close an httpclient connection.
	 */
	public void shutdown() {
		if (httpclient != null) {
			httpclient.getConnectionManager().shutdown();
			httpclient = null;
		}
	}
	
	/**
	 * Open a httpclient connection.
	 */
	public void open(){
		
		// make sure it close any active connection.
		shutdown();
		
		httpclient = new DefaultHttpClient();		
		((DefaultHttpClient) httpclient).setCookieStore(new BasicCookieStore());

	}
	
	/**
	 * Get a HttpGet or HttpPost object base on the url request
	 * 
	 * @param aChain A ChainBean object
	 * 
	 * @return HttpReqhestBase Return HttpGet or HttpPost object base on the url request
	 */
	private HttpRequestBase getHttpRequest(UrlChainBean aChain){
		
		HttpRequestBase rtn = null;		
		
		rtn = getHttpRequest(currentUrlChain.getUrl(), 
				             currentUrlChain.needPostMethod(), 
				             getPostData(currentUrlChain.getAposts()));

		return rtn;
		
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
	private HttpRequestBase getHttpRequest(String aURL, boolean isPost, UrlEncodedFormEntity urlEncodedFormEntity){
		
		if (aURL == null){ 
			throw new IllegalArgumentException("aURL cannot be null!");
		}
		
		HttpRequestBase rtn = null;
		
		String targetURL = parseDynamicURL(aURL);
		
		if (log.isDebugEnabled()){
	           log.debug(String.format("[%s]: %s", 
	                                    currentUrlChain.isEnabled()?"ENABLED":"DISABLED",
	                                    currentUrlChain.getUrl()));
	           log.debug(String.format("Parsed URL %s", targetURL));
		}
		
		
		if (isPost){ 
			rtn = new HttpPost(targetURL);
			((HttpPost) rtn).setEntity(urlEncodedFormEntity);			
		}
		else {
			rtn = new HttpGet(aURL);
		}
		
		return rtn;
		
	}
	
	/**
	 * Dynamically update a URL string.  Replace first matching dynamic variable in a url string.
	 * 
	 * @return String A parsed url string
	 */
	private String parseDynamicURL(String aUrl){
		
		String rtn = aUrl;
		
		for (String key: varUrlMap.keySet().toArray(new String[varUrlMap.size()])) {
			
			String value = varUrlMap.get(key);
			String matcher = String.format("%s%s%s", VARSEPERATOR, key, VARSEPERATOR);
			rtn = rtn.replaceFirst(matcher, value);
		}
		
		return rtn;
		
	}
	
	
	
	/**
	 * Wait for a specific amount of time defined in a ChainBean.
	 * 
	 * @param UrlChainBean A ChainBean
	 */
	private void waiting(UrlChainBean aChain) {
		
		log.debug(String.format("wait time: " + aChain.getWait())); 
		
		try {
			Thread.sleep(aChain.getWait());
		} catch (InterruptedException e) {
			// do nothing
		}

	}
	
	
	/**
	 * 
	 * @param posts An ArrayList of PostBean
	 * 
	 * @return UrlEncodedFormEntity Return an UrlEncodedFormEntity object.  Otherwise return null. 
	 */
	private UrlEncodedFormEntity getPostData(ArrayList<PostBean> posts) {

		UrlEncodedFormEntity rtn = null;
		
		if (!posts.isEmpty()) {

			List<NameValuePair> formparams = new ArrayList<NameValuePair>();

			for (PostBean pb : posts) {
				formparams.add(new BasicNameValuePair(pb.getKey(), 
						                              pb.getValue()));
			}

			try {

				rtn = new UrlEncodedFormEntity(formparams, FileIO.UTF8);

			} catch (UnsupportedEncodingException e) {
				String errmsg = "UrlEncodedFormEntity failed with message: " + e.getMessage();
				log.error(errmsg, e);
				e.printStackTrace();
				throw new RuntimeException(errmsg);
			}
		}
		
		return rtn;
		
	}
	
	
	/**
	 * Run all url defined in a urlchains.xml file.
	 * 
	 */
	public void runall() {

		while (next() == 1) {
			
			int repeatCount = currentUrlChain.getRepeat();
			
			while (repeatCount > 0) {
				setCurrentChain();
				call();
				repeatCount--;				
			} 
			
		} // END WHILE LOOP

	}
	
	
	/**
	 * Add variable url key-value pairs mapping.  All keys are converted to lower case. 
	 * 
	 * @param key Variable url key
	 * @param value Variable url value
	 */
	public void putUrlVar(String key, String value){

		if (key == null){ 
			throw new IllegalArgumentException("key cannot be null!");
		}

		varUrlMap.put(key.toLowerCase(), value);
		
	}
	
	/**
	 * Remove variable url key-value pairs mapping 
	 * 
	 * @param key Variable url key
	 * 
	 */	
	public void removeUrlVar(String key){
		
		if (key == null){ 
			throw new IllegalArgumentException("key cannot be null!");
		}
		
		varUrlMap.remove(key.toLowerCase());		
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
