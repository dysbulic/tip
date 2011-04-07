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
 * @date February 2008
 * 
 * Iterate over the files in a directory tree.
 */
public class FileList implements Iterable<File>, Iterator<File> {
    LinkedList<File> directories = new LinkedList<File>();
    LinkedList<File> currentFiles = new LinkedList<File>();

    public FileList(File baseDir) {
        directories.add(baseDir);
    }

    public boolean hasNext() {
        return !(directories.isEmpty() && currentFiles.isEmpty());
    }

    public void remove() {
        throw new UnsupportedOperationException();
    }

    public File next() {
        // Send all files first, so make sure the next element isn't a directory
        while(!currentFiles.isEmpty() && currentFiles.peek().isDirectory()) {
            directories.add(currentFiles.remove());
        }
        if(currentFiles.isEmpty()) {
            File directory = directories.remove();
            currentFiles.addAll(Arrays.asList(directory.listFiles()));
            return directory;
        } else {
            return currentFiles.remove();
        }
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
            FileList files = new FileList(baseDir);
            for(File file : files) {
                System.out.println(file);
            }
        }
    }
}
