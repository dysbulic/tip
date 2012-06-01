#! /usr/bin/expect -f

expect "hi" { send "You said hi\n" } \
       "hello" { send "Hello yourself\n" } \
       "bye" { send "Good-bye cruel world\n" }
