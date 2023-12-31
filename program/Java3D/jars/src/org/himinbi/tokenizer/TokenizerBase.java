package org.himinbi.tokenizer;

import org.apache.log4j.Logger;

public abstract class TokenizerBase implements TokenStream
 {
     Token nextToken;

     static Logger log = Logger.getLogger(TokenizerBase.class);

     /**
      * Tracks whether an initial token has been queued
      */
     protected boolean initialized = false;

     public TokenizerBase()
     {
     }
         
     protected void setNextToken(Token nextToken)
     {
         this.nextToken = nextToken;
     }

     /**
      * Returns true until there are no more tokens
      */
     public boolean hasNext()
     {
         log.debug("has next called: " + getClass().getName());
         if(!initialized)
         {
             queueToken();
             initialized = true;
         }
         return nextToken != null;
     }
     
     /**
      * @return the next token destructively or null if one does not exist
      */
     public Token nextToken()
     {
         if(!hasNext())
         {
             return null;
         }
         Token nextToken = this.nextToken;
         queueToken();
         return nextToken;
     }

     /**
      * Returns the type of the next token
      */
     public String nextTokenType()
     {
         return nextToken.getType();
     }

     /**
      * Returns the value of the next token
      */
     public String nextTokenValue()
     {
         return nextToken.getValue();
     }

     /**
      * Non-destructively returns the next token
      */
     public Token lookAhead()
     {
         if(!hasNext())
         {
             return null;
         }
         return nextToken;
     }

     /**
      * Does nothing
      */
     public void initialize()
     {
     }

     /**
      * Sets the next token to be returned using setNextToken().
      * If no more tokens are avaialable then it should set the
      * next token to null.
      */
     protected abstract void queueToken();
}
