/**
 * 
 */
package jhu.welch.atis.utils.httpchain;

import org.apache.http.Header;
import org.apache.http.HttpRequest;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.ProtocolException;
import org.apache.http.impl.client.DefaultRedirectStrategy;
import org.apache.http.protocol.HttpContext;

/**
 * CustomRedirectStrategy allows redirection from a post request.
 * 
 * RFC standard doesn't allow redirection in a post request.
 *   
 *  
 * 
 * @author fwong3
 * 
 */
public class CustomRedirectStrategy extends DefaultRedirectStrategy {

	public boolean isRedirected(final HttpRequest request,
			final HttpResponse response, final HttpContext context)
			throws ProtocolException {
		if (response == null) {
			throw new IllegalArgumentException("HTTP response may not be null");
		}

		int statusCode = response.getStatusLine().getStatusCode();
		Header locationHeader = response.getFirstHeader("location");
		switch (statusCode) {
		case HttpStatus.SC_MOVED_TEMPORARILY:
			return locationHeader != null;
		case HttpStatus.SC_MOVED_PERMANENTLY:
		case HttpStatus.SC_TEMPORARY_REDIRECT:
		case HttpStatus.SC_SEE_OTHER:
			return true;
		default:
			return false;
		} // end of switch
	}

}
