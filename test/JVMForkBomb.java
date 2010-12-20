/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: May 2008
 *
 * Simple class to test forking a new JVM.
 */

import java.io.IOException;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.logging.Logger;
import java.lang.reflect.Array;

public class JVMForkBomb {
    public static void main(String[] args) throws IOException, InterruptedException {
        String className = JVMForkBomb.class.getName();
        Logger log = Logger.getLogger(className);
        
        boolean error = args.length == 0;
        int forkCount = 0;

        if(!error) {
            forkCount = Integer.valueOf(args[0]);
            error = forkCount < 0 || forkCount > 10;
        }
            
        if(error) {
            System.out.println("Fork a new JVM <count> times and pause <pause> milliseconds before exiting the last one.");
            System.out.println("Usage: " + className + " [fork count] <pause>");
            System.exit(-1);
        }


        if(forkCount > 0) {
            String classPath = System.getProperty("java.class.path");

            System.out.println(forkCount + ": Forking JVM: " + classPath);

            String[] command = new String[] { "java", className, String.valueOf(forkCount - 1),
                                              "-classpath", classPath,
                                              args.length > 1 ? args[1] : "0" };
            Process javaProcess = Runtime.getRuntime().exec(command);
            javaProcess.waitFor();
            
            BufferedReader out = new BufferedReader(new InputStreamReader(javaProcess.getInputStream()));
            String line = null;
            while((line = out.readLine()) != null) {
                System.out.println(forkCount + ": Forked Output: '" + line + "'");
            }
            
            System.out.println(forkCount + ": Forked JVM: " + className + " (" + javaProcess.exitValue() + ")");
        } else {
            int pauseTime = 0;
            if(args.length > 1) {
                pauseTime = Integer.parseInt(args[1]);
            }
            if(pauseTime > 0) {
                Thread.sleep(pauseTime);
            }
        }
    }
}
