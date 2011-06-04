package jhu.welch.atis.utils;

import java.io.FileWriter;
import java.io.IOException;

public class FileIO {

	// 
	private static FileIO instance = null;
	
	public static final String UTF8 = "UTF-8";
	

	/**
	 * Get an instance of FileIO object
	 * 
	 * @return FileIO Return a FileIO object.
	 */
	public static FileIO getInstance() {
		
		if (instance == null) {
			instance = new FileIO();
		}
		
		return instance;
	}
	
	/**
	 * Private construction - Singleton design 
	 */
	private FileIO(){ 
		// DO nothing
	}

	
	/**
	 * 
	 * Write a String buffer to a file.
	 * 
	 * @param outf A output file name.
	 * @param buf String buffer. 
	 */
	public void Write(String outf, String buf){
		
		Write(outf, buf.toCharArray());
	}
	
	/**
	 * 
	 * Write a character buffer to a file. 
	 * 
	 * @param outf A output file name.
	 * @param buf Character buffer. 
	 * 
	 */
	public void Write(String outf, char[] buf) { 
	
		if (outf == null) { 
			throw new NullPointerException("outf is null.");
		}
		
		if (buf == null) {
			throw new NullPointerException("buf is null");
		}
		
		FileWriter fileWriter = null;
		
		
		try {
			
			fileWriter = new FileWriter(outf);
			
			fileWriter.write(buf);
			
		} catch (IOException e) {
			e.printStackTrace();
			throw new RuntimeException(e.getMessage(), e);
		} finally {
			try {
				fileWriter.close();
			} catch (IOException e) {
				// Do nothing
			}
			
			fileWriter = null;
		}
		
		
	}


}
