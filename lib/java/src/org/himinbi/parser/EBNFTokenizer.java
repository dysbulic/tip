package org.himinbi.parser;

import java.io.PushbackReader;
import java.io.IOException;

import org.himinbi.tokenizer.basic.BasicTokenizer;
import org.himinbi.tokenizer.basic.CharacterClass;
import org.himinbi.tokenizer.basic.TokenType;
import org.himinbi.tokenizer.basic.MultiTokenType;
import org.himinbi.tokenizer.basic.ListTypeToken;
import org.himinbi.tokenizer.basic.ListCharacterClass;
import org.himinbi.tokenizer.basic.IntervalCharacterClass;
import org.himinbi.tokenizer.basic.QuoteDeliniatedToken;
import org.himinbi.tokenizer.basic.SentenceToken;

public class EBNFTokenizer extends BasicTokenizer
{
    public static String TT_NONTERMINAL = "nonterminal";
    public static String TT_TERMINAL = "terminal";
    public static String TT_WHITESPACE = "whitespace";
    public static String TT_PUNCTUATION = "punctuation";
    public static String TT_NUMBER = "number";
    
    public EBNFTokenizer() {
        /**
         * Array of classes of characters that are allowed in words
         *  for a nonterminal. Currently they may only contain letters.
         */
        CharacterClass[] wordCharacters = {
            new IntervalCharacterClass('A', 'Z'),
            new IntervalCharacterClass('a', 'z') };
        TokenType words = new ListTypeToken(wordCharacters);
        
        char[] spaces = { ' ', '\n', '\t' };
        TokenType whitespace =
            new MultiTokenType(new TokenType[] {
                new ListTypeToken(new ListCharacterClass(spaces)),
                new QuoteDeliniatedToken("(*", "*)"),
                new QuoteDeliniatedToken("#", "\n") });
        whitespace.setClassName(TT_WHITESPACE);

        // Adding this after nonterminal gives the whitespace at the beginning
        //  of a nonterminal precedence, but it will cause problems when the
        //  whitespace does not precede a nonterminal at the end of the file
        addTokenType(whitespace);

        TokenType nonterminal =
            new SentenceToken(words, whitespace, " ");
        nonterminal.setClassName(TT_NONTERMINAL);
        addTokenType(nonterminal);

        TokenType terminal =
            new MultiTokenType(new TokenType[] {
                new QuoteDeliniatedToken("\"", "\""),
                new QuoteDeliniatedToken("'", "'") });
        terminal.setClassName(TT_TERMINAL);
        addTokenType(terminal);

        CharacterClass[] digits = {
            new IntervalCharacterClass('0', '9') };
        TokenType numbers = new ListTypeToken(digits);
        numbers.setClassName(TT_NUMBER);
        addTokenType(numbers);

        char[] delimiters = { ';', ',', '=',
                              '?', '+', '*',
                              '{', '}',
                              '(', ')', '|' };
        for(int i = 0; i < delimiters.length; i++) {
            ListTypeToken punctuation =
                new ListTypeToken(new ListCharacterClass(delimiters[i]));
            punctuation.setClassName(String.valueOf(delimiters[i]));
            addTokenType(punctuation);
        }
    }
}
