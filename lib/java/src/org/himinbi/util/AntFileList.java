package org.himinbi.util;

import org.apache.tools.ant.types.Resource;
import org.apache.tools.ant.Project;
import org.apache.tools.ant.types.FileSet;

import java.util.List;
import java.util.Iterator;
import java.util.Arrays;
import java.util.Queue;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Collections;

import java.io.File;
import java.io.FileNotFoundException;

/**
 * @author Will Holcomb <wholcomb@gmail.com>
 * @date March 2007
 * 
 * Iterate over the files in a directory tree.
 */
public class AntFileList implements Iterable<File>, Iterator<File> {
    Iterator fileIterator;
    
    public AntFileList(File baseDir) {
        this(baseDir, null);
    }

    public AntFileList(File baseDir, List<String> excludes) {
        FileSet files = new FileSet();
        Project project = new Project();
        project.setBasedir(baseDir.toString());
        files.setProject(project);
        files.setDir(project.getBaseDir());

        if(excludes != null) {
            for(String exclude : excludes) {
                files.setExcludes(exclude);
            }
        }
        fileIterator = files.iterator();
    }

    public boolean hasNext() {
        return fileIterator.hasNext();
    }

    public void remove() {
        throw new UnsupportedOperationException();
    }

    public File next() {
        Resource resource = (Resource)fileIterator.next();
        return new File(resource.getName());
    }

    public Iterator<File> iterator() { return this; }

    /**
     * @param args - list of directories to be read
     */
    public static void main(String... args) throws FileNotFoundException {
        if(args.length == 0) {
            System.out.println("Recursively list the contents of a directory.");
            System.exit(-1);
        }
        for(String dirName : args) {
            File baseDir = new File(dirName);
            AntFileList files = new AntFileList(baseDir);
            for(File file : files) {
                System.out.println(file);
            }
        }
    }
}

                
