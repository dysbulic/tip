header {
  package org.himinbi.bebnf;
  import java.io.*;
  import antlr.CommonAST;
  import antlr.Parser;
  import antlr.debug.misc.ASTFrame;
  import antlr.MismatchedTokenException;
}

class BEBNFParser extends Parser;
options {     
  k = 5; // the lookahead is the maximum number of words that can be in a nonterminal
         // because if it is followed by a comma it is a nonterminal, a parenthesis
         // and it is a named group
  buildAST = true;
}

tokens { Grammar;
         Rule;
         Choice;
         ElementList;
         Element;
         Nonterminal; }

{
    public static void main(String[] args) {
        boolean graphical = false;
        boolean manual = true;
        try {
            BasicLexer lexer = new BasicLexer(new DataInputStream(System.in));
            BEBNFParser parser = new BEBNFParser(lexer);
            parser.grammar();
            CommonAST ast = (CommonAST)parser.getAST();
            
            if(graphical) {
                (new ASTFrame("BEBNF Test", ast)).setVisible(true);
            } else if(manual) {
                org.himinbi.antlr.XMLPrinter.printASTasXML(ast, parser);
            } else {
                Writer writer = new OutputStreamWriter(System.out);
                ast.xmlSerialize(writer);
                writer.write("\n");
                writer.flush();
            } 
        } catch(MismatchedTokenException mte) {
            if(mte.token.getText() == null) {
                System.err.println("Caught error: line " + mte.getLine() + ":" + mte.getColumn() +
                                   ": end of input when expecting " + BEBNFParser._tokenNames[mte.expecting]);
            } else {  
                System.err.println("Error: " + mte.toString());
            }
        } catch(Exception e) {
            System.err.println("Exception: " + e);
        }
    }
}

grammar: (rule)*
  { ## = #( [Grammar, "Grammar"], ## ); };
rule: nonterminalChoice EQUALS^ elementList SEMICOLON!
  { ## = #( [Rule, "Rule"], ## ); };
nonterminalChoice: nonterminal (BAR! nonterminal)*
  { ## = #( [Choice, "Choice"], ## ); };
elementList: elementChoice (COMMA! elementChoice)*
  { ## = #( [ElementList, "Element List"], ## ); };
elementChoice: element (BAR! element)*
  { ## = #( [Choice, "Choice"], ## ); };
nonterminal: (WORD)+
  { ## = #( [Nonterminal, "Nonterminal"], ## ); };

//element: (group | nonterminal | namedGroup | terminal) (repetition)?; /* confuses antlr */
element: (group | nonterminalOrNamedGroup | terminal) (repetition)?;
nonterminalOrNamedGroup: nonterminal (group)?;
group: OPENPAREN elementList CLOSEPAREN;
namedGroup: nonterminal group;
repetition: (STAR | PLUS | QUESTIONMARK | (OPENBRACE expression (COMMA! expression)? CLOSEBRACE));

expression: sum;
sum: term ((PLUS | MINUS) term)*;
term: shift ((STAR | SLASH) shift)*;
shift: operand | (operand ("<<" | ">>") operand);
operand: (OPENPAREN expression CLOSEPAREN) | namedGroup | NUMBER;

terminal: SINGLEQUOTEDSTRING | DOUBLEQUOTEDSTRING | CHARLIST | DOT;

class BasicLexer extends Lexer;

EQUALS: '=';
SEMICOLON: ';';
COMMA: ',';
SLASH: '\\';
PLUS: '+';
MINUS: '-';
STAR: '*';
QUESTIONMARK: '?';
OPENPAREN: '(';
CLOSEPAREN: ')';
OPENBRACE: '{';
CLOSEBRACE: '}';
//DOUBLEQUOTE: '"';
//SINGLEQUOTE: '\'';

NUMBER: (DIGIT)+;

protected DIGIT: '0'..'9';
protected HEXDIGIT: '0'..'9'|'A'..'F'|'a'..'f'|'x'|'X';
protected BITDIGIT: '0'|'1'|'x'|'X';

WHITESPACE: (('\t'|' '|'\r'|'\n'{newline();}))+
  { $setType(Token.SKIP); };
WORD: ('A'..'Z'|'a'..'z') (~(' '|'|'|';'|','|'('|')'|'{'|'}'|'*'|'+'|'?'))*;

LINECOMMENT : "//" (~('\n'|'\r'))* ('\n'|'\r');
BAR: '|';

DOUBLEQUOTEDSTRING: '"' (ESCAPE | ~('\\'|'"'))* '"';
SINGLEQUOTEDSTRING: '\'' (ESCAPE | ~('\\'|'\''))* '\'';
protected ESCAPE: (BASICESCAPE | UNICODEESCAPE | HEXESCAPE | BINARYESCAPE);
BASICESCAPE: '\\' ~('N'|'u'|'U'|'x'|'b'|'e'|'E');
UNICODEESCAPE: UNICODENAME | UNICODE16ESCAPE | UNICODE32ESCAPE;
UNICODENAME: "\\N{" (~('}'))+ "}";
UNICODE16ESCAPE: "\\u" HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT;
UNICODE32ESCAPE: "\\U" HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT HEXDIGIT;
HEXESCAPE: FIXEDHEXESCAPE | VARIABLEHEXESCAPE;
FIXEDHEXESCAPE: "\\x" HEXDIGIT HEXDIGIT;
VARIABLEHEXESCAPE: "\\x{" (HEXDIGIT)+ "}";
BINARYESCAPE: SIMPLEBINARYESCAPE | LITTLEENDIANESCAPE | BIGENDIANESCAPE;
SIMPLEBINARYESCAPE: FIXEDBINARYESCAPE | VARIABLEBINARYESCAPE;
FIXEDBINARYESCAPE: "\\b" BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT;
VARIABLEBINARYESCAPE: "\\b{" (BITDIGIT)+ "}";
LITTLEENDIANESCAPE: FIXEDLITTLEENDIAN | VARIABLELITTLEENDIAN;
FIXEDLITTLEENDIAN: "\\e" BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT;
VARIABLELITTLEENDIAN: "\\e{" (BITDIGIT)+ "}";
BIGENDIANESCAPE: FIXEDBIGENDIAN | VARIABLEBIGENDIAN;
FIXEDBIGENDIAN: "\\E" BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT BITDIGIT;
VARIABLEBIGENDIAN: "\\E{" (BITDIGIT)+ "}";

CHARLIST: "[" ((ESCAPE | ~(']'|'\\') | CHARCLASS))+ "]";
CHARCLASS: "[:" ("alpha"|"alnum"|"blank"|"cntrl"|"digit"|"graph"|"lower"|"upper"|"punct"|"space"|"xdigit") ":]";

/*
class GrammarBuilder extends TreeParser;

grammar returns [org.himinbi.parser.Grammar grammar]
  { grammar = new org.himinbi.parser.BasicGrammar(); }
  : ;
*/
/*
expr returns [double r]
  { double a,b; r=0; }

  : #(PLUS a=expr b=expr)  { r=a+b; }
  | #(MINUS a=expr b=expr) { r=a-b; }
  | #(MUL  a=expr b=expr)  { r=a*b; }
  | #(DIV  a=expr b=expr)  { r=a/b; }
  | #(MOD  a=expr b=expr)  { r=a%b; }
  | #(POW  a=expr b=expr)  { r=Math.pow(a,b); }
  | i:INT { r=(double)Integer.parseInt(i.getText()); }
  ;
*/
