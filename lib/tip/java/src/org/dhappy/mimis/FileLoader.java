package org.dhappy.mimis;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory; 

import java.io.File;
import java.io.IOException;
import java.util.Iterator;
import java.util.Date;
import java.text.DateFormat;

import org.apache.tools.ant.types.FileSet;
import org.apache.tools.ant.types.Resource;
import org.apache.tools.ant.types.resources.FileResource;
import org.apache.tools.ant.Project;

public class FileLoader {
    private static Log log = LogFactory.getLog( FileLoader.class );

    public static void main( String[] args ) {
        log.debug( "Starting: FileLoader" );

        FileSet files = new FileSet();
        Project project = new Project();
        project.setBasedir( "." );
        files.setProject(project);
        files.setDir( project.getBaseDir() );
        files.setFollowSymlinks( false );

        files.setIncludes( "**/*ml" );

        log.debug( "Matched " + files.size() + " files" );

        for( Iterator iter = files.iterator(); iter.hasNext(); ) {
            Resource resource = (Resource)iter.next();
            try {
                if( resource instanceof FileResource ) {
                    Mimis.load( resource.getName(),
                                ((FileResource)resource).getFile() );
                } else {
                    Mimis.load( resource.getName(), resource.getInputStream() );
                }
            } catch( IOException ioe ) {
                log.error( "Loading: " + resource.getName(), ioe );
            }
        }
        
        Mimis.shutdown();
        log.debug( "Finished: FileLoader" );
    }
}
