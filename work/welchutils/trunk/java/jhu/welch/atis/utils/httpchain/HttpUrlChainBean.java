package jhu.welch.atis.utils.httpchain;

import java.util.ArrayList;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlElementWrapper;
import javax.xml.bind.annotation.XmlRootElement;

@XmlRootElement(name="httpchains")
public class HttpUrlChainBean {
	

	private boolean debug = false;
	
    @XmlElementWrapper(name="chains")
    @XmlElement(name="chain")
	private ArrayList<UrlChainBean> aChains = new ArrayList<UrlChainBean>();
	

	public boolean isDebug() {
		return debug;
	}

	public void setDebug(boolean debug) {
		this.debug = debug;
	}


	public ArrayList<UrlChainBean> getAChains() {
		return aChains;
	}

	public void setAchains(ArrayList<UrlChainBean> chains) {
		this.aChains = chains;
	}

}
