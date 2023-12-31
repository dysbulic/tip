package org.himinbi.antlr;

import java.io.*;
import org.himinbi.bebnf.*;
import antlr.Parser;
import antlr.collections.AST;

public class XMLPrinter {
    public static void printASTasXML(AST ast, Parser parser) {
        printASTatDepth(ast, parser, System.out, 0);
    }

    protected static void printASTatDepth(AST ast, Parser parser, PrintStream out, int depth) {
        out.printf("%1$-" + (depth * 2 + 1) + "s<%2$s>", "", parser.getTokenName(ast.getType()));
        if(ast.getFirstChild() == null) { // a terminal
            out.print(ast.getText());
        } else {
            out.println("");
            printASTatDepth(ast.getFirstChild(), parser, out, depth + 1);
            out.printf("\n%1$-" + (depth * 2 + 1) + "s", "");
        }
        out.printf("</%1$s>", parser.getTokenName(ast.getType()));
        if(ast.getNextSibling() != null) {
            out.println("");
            printASTatDepth(ast.getNextSibling(), parser, out, depth);
        }
    }
}
