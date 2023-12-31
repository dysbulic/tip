package org.himinbi.templ.test;

import org.junit.Test;
import static org.junit.Assert.assertTrue;

import java.util.Collection;
import java.util.ArrayList;

/**
 * Verifies junit is installed and functioning
 */
public class BasicFrameworkTest {
    @Test public void testEmptyCollection() {
        Collection collection = new ArrayList();
        assertTrue(collection.isEmpty());
    }

    public static void main(String args[]) {
        org.junit.runner.JUnitCore.main("org.himinbi.templ.test.BasicFrameworkTest");
    }
}
