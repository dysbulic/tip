package org.himinbi.util;

import javax.swing.filechooser.FileFilter;
import java.util.StringTokenizer;
import java.io.File;

public class ConfigurableFileFilter extends FileFilter {
    String[] extensions;
    String description = new String();
    boolean appendBlob = true;
    ConfigurableFileFilter[] subFilters;
    int maxExtensions = 4;

    public ConfigurableFileFilter(String extension) {
	setExtension(extension);
	description = toBlob();
    }

    public ConfigurableFileFilter(String extension, String description) {
	setExtension(extension);
	setDescription(description);
    }

    public ConfigurableFileFilter(String[] extensions) {
	setExtensions(extensions, null);
	description = toBlob();
    }

    public ConfigurableFileFilter(String[] extensions, String description) {
	setExtensions(extensions, null);
	setDescription(description);
    }

    public ConfigurableFileFilter(String[] extensions, String[] descriptions) {
	setExtensions(extensions, descriptions);
	description = toBlob();
    }

    public ConfigurableFileFilter(String[] extensions, String[] descriptions, String description) {
	setExtensions(extensions, descriptions);
	setDescription(description);
    }

    public void setExtension(String extension) {
	StringTokenizer tokens = new StringTokenizer(extension, ",");
	extensions = new String[tokens.countTokens()];
	int i = 0;
	while(tokens.hasMoreElements()) {
	    extensions[i++] = tokens.nextToken();
	}
    }	

    public void setExtensions(String[] extensions, String[] descriptions) {
	this.extensions = null;
	subFilters = new ConfigurableFileFilter[extensions.length];
	for(int i = 0; i < extensions.length; i++) {
	    if(descriptions != null && i < descriptions.length) {
		subFilters[i] = new ConfigurableFileFilter(extensions[i], descriptions[i]);
	    } else {
		subFilters[i] = new ConfigurableFileFilter(extensions[i]);
	    }
	}
    }

    public boolean accept(File file) {
	boolean matched = false;
	if(extensions != null) {
	    if(file.isDirectory()) {
		matched = true;
	    }
	    String extension = getFilenameExtension(file.getName());
	    for(int i = 0; i < extensions.length && !matched; i++) {
		matched = extension.equals(extensions[i]);
	    }
	} else {
	    for(int i = 0; i < subFilters.length && !matched; i++) {
		matched = subFilters[i].accept(file);
	    }
	}
	return matched;
    }

    public String getDescription() {
	return description;
    }

    public void setDescription(String description) {
	if(appendBlob) {
	    description += " (" + toBlob() + ")";
	}
	this.description = description;
    }

    public String toBlob() {
	String blob = new String();
	if(extensions != null && extensions.length > 0 && maxExtensions > 0) {
	    int i = 0;
	    blob += "*." + extensions[i];
	    for(i = 1; i < maxExtensions && i < extensions.length; i++) {
		blob += ", *." + extensions[i];
	    }
	    if(i < extensions.length) {
		blob += ", ...";
	    }
	} else if(extensions == null && subFilters.length > 0 && maxExtensions > 0) {
	    int i = 0;
	    blob += subFilters[i].toBlob();
	    for(i = 1; i < maxExtensions && i < subFilters.length; i++) {
		blob += ", " + subFilters[i].toBlob();
	    }
	    if(i < subFilters.length) {
		blob += ", ...";
	    }
	}
	return blob;
    }

    public ConfigurableFileFilter[] getSubFilters() {
	return subFilters;
    }

    public static String getFilenameExtension(String filename) {
	int index = filename.lastIndexOf('.');
	String extension = null;
	if(index >= filename.length()) {
	    extension = "";
	} else if(index < 0) {
	    extension = filename.toLowerCase();
	} else {
	    extension = filename.substring(index + 1).toLowerCase();
	}
	return extension;
    }
}
