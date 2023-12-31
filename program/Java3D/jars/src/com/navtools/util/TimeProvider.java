/*
 * $Id: TimeProvider.java,v 1.1.1.1 2001/07/02 02:06:17 will Exp $
 * $Log: TimeProvider.java,v $
 * Revision 1.1.1.1  2001/07/02 02:06:17  will
 * Java Utilities for different things
 *
 * Revision 1.1  2001/04/04 15:46:35  wurp
 * Added SNTP client to sync time from client to server.
 * Still have to run SNTP server on LoginServer, and may need to change from default SNTP port. (since it's below 1024 and thus not accessible to non-root processes on *nix)
 *
 */

package com.navtools.util;

public interface TimeProvider
{
    public long currentTimeMillis();
}

