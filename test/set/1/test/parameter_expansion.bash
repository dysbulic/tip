#!/usr/bin/env bash

define param
function print_param() {
    if 

       ${parameter:-word}
              Use Default Values.  If parameter is unset or null, the expansion of word is substituted.  Otherwise, the value of parameter is substituted.
       ${parameter:=word}
              Assign  Default  Values.   If  parameter is unset or null, the expansion of word is assigned to parameter.  The value of parameter is then substituted.  Posi‐
              tional parameters and special parameters may not be assigned to in this way.
       ${parameter:?word}
              Display Error if Null or Unset.  If parameter is null or unset, the expansion of word (or a message to that effect if word is not present) is written  to  the
              standard error and the shell, if it is not interactive, exits.  Otherwise, the value of parameter is substituted.
       ${parameter:+word}
              Use Alternate Value.  If parameter is null or unset, nothing is substituted, otherwise the expansion of word is substituted.
       ${parameter:offset}
       ${parameter:offset:length}
              Substring  Expansion.   Expands  to  up to length characters of parameter starting at the character specified by offset.  If length is omitted, expands to the
              substring of parameter starting at the character specified by offset.  length and offset are arithmetic expressions (see ARITHMETIC EVALUATION below).  length
              must  evaluate  to a number greater than or equal to zero.  If offset evaluates to a number less than zero, the value is used as an offset from the end of the
              value of parameter.  Arithmetic expressions starting with a - must be separated by whitespace from the preceding : to be distinguished from  the  Use  Default
              Values  expansion.   If  parameter is @, the result is length positional parameters beginning at offset.  If parameter is an array name indexed by @ or *, the
              result is the length members of the array beginning with ${parameter[offset]}.  A negative offset is taken relative to one greater than the maximum  index  of
              the  specified array.  Note that a negative offset must be separated from the colon by at least one space to avoid being confused with the :- expansion.  Sub‐
              string indexing is zero-based unless the positional parameters are used, in which case the indexing starts at 1.

       ${!prefix*}
       ${!prefix@}
              Expands to the names of variables whose names begin with prefix, separated by the first character of the IFS special variable.

       ${!name[@]}
       ${!name[*]}
              If name is an array variable, expands to the list of array indices (keys) assigned in name.  If name is not an array, expands to 0 if name  is  set  and  null
              otherwise.  When @ is used and the expansion appears within double quotes, each key expands to a separate word.

       ${#parameter}
              The  length in characters of the value of parameter is substituted.  If parameter is * or @, the value substituted is the number of positional parameters.  If
              parameter is an array name subscripted by * or @, the value substituted is the number of elements in the array.

       ${parameter#word}
       ${parameter##word}
