package jhu.welch.atis.utils.httpchain;

import java.util.ArrayList;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlElementWrapper;

public class UrlChainBean {
	
	private String url = null; 
	
    @XmlElementWrapper(name="posts")
    @XmlElement(name="post")	
	private ArrayList<PostBean> aPosts = new ArrayList<PostBean>();
	
	private String fileUpload = null; 
	
	private long wait = 0;
	
	private String saveContentFile = null;
	
	private int repeat = 1;
	private boolean enabled = true;
	
	private boolean postRedirect = false;
	
	

	public String getUrl() {
		return url;
	}

	public void setUrl(String url) {
		this.url = url;
	}

	public ArrayList<PostBean> getAposts() {
		return aPosts;
	}

	public void setAposts(ArrayList<PostBean> posts) {
		this.aPosts = posts;
	}

	public String getFileUpload() {
		return fileUpload;
	}

	public void setFileUpload(String fileUpload) {
		this.fileUpload = fileUpload;
	}

	public long getWait() {
		return wait;
	}

	public void setWait(long wait) {
		this.wait = wait;
	}
	

	public String getSaveContentFile() {
		return saveContentFile;
	}

	public void setSaveContentFile(String saveContentFile) {
		this.saveContentFile = saveContentFile;
	}

	public boolean needSaveContent(){ 
		return  this.saveContentFile != null && !this.saveContentFile.equals("");
	}
	
	public int getRepeat() {
		return repeat;
	}

	public void setRepeat(int repeat) {
		this.repeat = repeat;
	}
	

	public boolean isEnabled() {
		return enabled;
	}

	public void setEnabled(boolean enabled) {
		this.enabled = enabled;
	}
	

	public boolean isPostRedirect() {
		return postRedirect;
	}

	public void setPostRedirect(boolean postRedirect) {
		this.postRedirect = postRedirect;
	}

	/**
	 * 
	 * @return boolean Return true, if it needs to post data, otherwise return false.   
	 */
	public boolean needPostMethod(){ 
		return !aPosts.isEmpty();
	}
	

	/**
	 * 
	 * @return boolean Return true, if it needs to post multipart data(file upload), otherwise return false.   
	 */
	public boolean needMultipartPostMethod(){ 
		return !aPosts.isEmpty();
	}


}
