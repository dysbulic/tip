package jhu.welch.atis.utils.httpchain;

public class PostBean {
    private String key;
    private String value;
    private String filename;

    public String getKey() {
        return key;
    }

    public void setKey( String key ) {
        this.key = key;
    }

    public String getValue() {
        return value;
    }

    public void setValue( String value ) {
        this.value = value;
    }

    public String getFile() {
        return filename;
    }

    public void setFile( String filename ) {
        this.filename = filename;
    }
}
