package org.himinbi.test;

import java.util.Enumeration;

import java.io.Reader;
import java.io.FileReader;
import java.io.IOException;
import java.io.FileNotFoundException;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

import org.himinbi.tokenizer.TokenizerFactory;
import org.himinbi.tokenizer.StreamTokenizer;
import org.himinbi.tokenizer.Token;

public class TokenizerTester
{
    public static void main(String[] args) throws Exception
    {
        if(args.length < 2)
        {
            System.out.println("Usage:");
            System.out.println("  TokenizerTester <tokeizer file name> [file name]+");
            System.out.println(" Will load a tokenizer stream from the file and");
            System.out.println("  step through the token produced from the listed files");
            return;
        }

        StreamTokenizer tokenizer =
            new TokenizerFactory().getTokenizer(args[0]);
        
        for(int i = 1; i < args.length; i++)
        {
            System.out.println("Processing: " + args[i]);
            try
            {
                Reader reader = new FileReader(args[i]);
                tokenizer.setInputReader(reader);
                int count = 0;
                while(tokenizer.hasNext())
                {
                    Token nextToken = tokenizer.nextToken();
                    System.out.println
                        ("  " + (++count) + ": " +
                         "(" + nextToken.getType() + ")" +
                         " \"" + nextToken.getValue() + "\"");
                }
            }
            catch(FileNotFoundException fnfe)
            {
                System.err.println("Error: File not found: " + args[i]);
            }
            catch(IOException ioe)
            {
                System.err.println("Error: " + ioe.getMessage());
            }
        }
    }
}
