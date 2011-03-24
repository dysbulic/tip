package org.himinbi.templ;

import org.apache.log4j.Logger;
import org.apache.log4j.BasicConfigurator;

import java.io.File;
import java.util.Iterator;
import java.util.Date;
import java.text.DateFormat;

import org.apache.tools.ant.types.Resource;
import org.apache.tools.ant.Project;
import org.apache.tools.ant.types.FileSet;

public class FileTimes {
    static Logger log = Logger.getLogger(FileTimes.class);
    static String DEFAULT_EXCLUDE = "**/*.java";

    { BasicConfigurator.configure(); }

    public static void main(String[] args) {
        FileSet files = new FileSet();
        Project project = new Project();
        project.setBasedir(".");
        files.setProject(project);
        files.setDir(project.getBaseDir());

        if(args.length == 0) {
            log.debug("Adding Default Exclude: " + DEFAULT_EXCLUDE);
            files.setExcludes(DEFAULT_EXCLUDE);
        } else {
            for(String arg : args) {
                log.debug("Adding Exclude Pattern: " + arg);
                files.setExcludes(arg);
            }
        }
        log.debug("Matched " + files.size() + " files");

        Resource youngest = null, oldest = null;
        for(Iterator iter = files.iterator(); iter.hasNext(); ) {
            Resource resource = (Resource)iter.next();
            if(youngest == null || resource.getLastModified() > youngest.getLastModified()) {
                youngest = resource;
            }
            if(oldest == null || resource.getLastModified() < oldest.getLastModified()) {
                oldest = resource;
            }
        }
        
        DateFormat format = DateFormat.getDateTimeInstance();
        log.debug("Youngest: (" + format.format(new Date(youngest.getLastModified())) + ") " + youngest.getName());
        log.debug("Oldest: (" + format.format(new Date(oldest.getLastModified())) + ") " + oldest.getName());
    }
}
