/*
 * $Id: WindowsTimeProvider.java,v 1.1.1.1 2001/07/02 02:06:17 will Exp $
 * $Log: WindowsTimeProvider.java,v $
 * Revision 1.1.1.1  2001/07/02 02:06:17  will
 * Java Utilities for different things
 *
 * Revision 1.1  2001/04/08 23:43:38  wurp
 * Adding accurate timer for windows.  Requires native code.  Will have to implement
 * alternative systems when porting to Linux, MacOS, etc.
 *
 */

package com.navtools.util;

public class WindowsTimeProvider implements TimeProvider
{
    static
    {
        //load the dll that implements currentTimeMillis in C code
        System.loadLibrary("TimeImpl");
    }

    public native long currentTimeMillis();

    public static void main(String[] args)
    {
        TimeProvider tp = new WindowsTimeProvider();

        for(int i = 0; i < 1000; ++i )
        {
            System.out.println("WTP: " + tp.currentTimeMillis());
            System.out.println("JTP: " + System.currentTimeMillis());
        }
    }
}


