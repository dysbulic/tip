package org.himinbi.tokenizer;

/*
import javax.sql.DataSource;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import com.codestudio.sql.PoolMan;
import com.codestudio.util.JDBCPool;
import com.codestudio.util.SQLManager;

import java.io.Reader;
import java.io.BufferedReader;
import java.io.IOException;

import java.util.Set;
import java.util.HashSet;
import java.util.Iterator;
import java.util.ArrayList;
import java.util.Collection;
import java.util.StringTokenizer;
*/

public class DictionaryLexer// implements TokenStream
{
//     ArrayList tokens = new ArrayList();
//     DataSource dataSource;
//     String databaseName = "dictionary";
//     boolean allIdentified = false;
//     int currentIndex = 0;

//     public DictionaryLexer() {
//         try {
//             dataSource = PoolMan.findDataSource(databaseName);
//         } catch(SQLException e) {
//             throw new IllegalArgumentException(e.getMessage());
//         }
//     }

//     public void setInputReader(Reader reader)
//         throws IOException {
//         tokens.clear();
//         BufferedReader lineReader = new BufferedReader(reader);
   
//         String delimiters = " \n\t.;,";
//         boolean returnDelimiters = true;
//         String source;
//         while((source = lineReader.readLine()) != null) {
//             StringTokenizer sourceTokens =
//                 new StringTokenizer(source,
//                                     delimiters,
//                                     returnDelimiters);
//             while(sourceTokens.hasMoreTokens()) {
//                 String nextToken = sourceTokens.nextToken();
//                 if(!(nextToken.startsWith(" ") ||
//                      nextToken.startsWith("\n") ||
//                      nextToken.startsWith("\t"))) {
//                     tokens.add(new Token(nextToken));
//                 }
//             }
//         }
//     }

//     public boolean hasMoreTokens() {
//         return currentIndex < tokens.size();
//     }

//     public Token nextToken() {
//         return (Token)tokens.get(currentIndex++);
//     }

//     public String nextTokenType() {
//         return ((Token)tokens.get(currentIndex)).getType();
//     }

//     public String nextTokenValue() {
//         return ((Token)tokens.get(currentIndex)).getValue();
//     }

//     public Token lookAhead() {
//         return (Token)tokens.get(currentIndex);
//     }

//     /**
//      * Not suppoerted currently.
//      */
//     public int getLineCount() {
//         return -1;
//     }

//     /**
//      * Returns if all the words were identified on the last call
//      * to getIdentifiedWords. Does not revaluate the words in
//      * the list.
//      */
//     public boolean ready() {
//         return allIdentified;
//     }

//     /**
//      * Returns all words that are not currently in the dictionary. Because
//      * words may be removed as well as added between iterations an argument
//      * allows the option of revalidating already identified words. The
//      * default is to revalidate.
//      */
//     public Collection getUnidentifiedWords() {
//         return getUnidentifiedWords(true);
//     }

//     public Collection getUnidentifiedWords(boolean revalidate) {
//         Set unidentifiedWords = new HashSet();
//         allIdentified = false;
        
//         Connection connection = null;
//         PreparedStatement statement = null;
//         ResultSet results = null;
//         try {
//             connection = dataSource.getConnection();
//             statement =
//                 connection.prepareStatement
//                 ("select parts_of_speech.type from words, parts_of_speech " +
//                  "where words.part_of_speech = parts_of_speech.id " +
//                  "and words.term = ?");
//             Iterator iterator = tokens.iterator();
//             while(iterator.hasNext()) {
//                 Token token = (Token)iterator.next();
//                 if(!token.isIdentified() || revalidate) {
//                     String value = token.getValue().toLowerCase();
//                     statement.setString(1, value);
//                     results = statement.executeQuery();
//                     if(results.next()) {
//                         token.setType(results.getString(1));
//                     } else {
//                         unidentifiedWords.add(value);
//                     }
//                     results.close();
//                 }
//             }
//             allIdentified = unidentifiedWords.size() == 0;
//         } catch(SQLException sqle) {
//             throw new IllegalArgumentException("SQL Exception: " + sqle);
//         } finally {
//             JDBCPool.closeResources(connection, statement, results);
//         }
//         return unidentifiedWords;
//     }
}
