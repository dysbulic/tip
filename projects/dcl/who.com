$ IF f$type(gn) .eqs. ""
$ THEN
$   gn = "@user:[honors.public]getnames.com"
$ ENDIF
$ IF f$type(time_program) .eqs. ""
$ THEN
$   time_program = "run user:[wjh3957.programs]time.exe"
$ ENDIF
$!
$ esc[0,8] = 27
$!
$ argument = "HELP"
$ GOSUB ARGUMENT_CHECK
$!
$ IF help .eqs. "TRUE"
$ THEN
$    GOSUB PRINT_HELP
$ ENDIF
$!
$ argument = "CLEAR"
$ GOSUB ARGUMENT_CHECK
$!
$ IF clear .eqs. "TRUE"
$ THEN
$    erase
$ ENDIF
$!
$ SETUP SCRATCH
$!
$ IF f$type(search_line_count) .eqs. ""
$ THEN
$   'gn'
$ ENDIF
$!
$ argument = "FAST"
$ GOSUB ARGUMENT_CHECK
$ argument = "QUIET"
$ GOSUB ARGUMENT_CHECK
$!
$ GOSUB CONDUCT_SEARCHES
$ GOSUB WRITE_OUTPUT
$!
$ EXIT:
$ IF f$search("SYS$SCRATCH:users_temp.txt") .nes. "" -
   THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:users_temp.txt;
$ IF f$search("SYS$SCRATCH:search_results.temp") .nes. "" -
   THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:search_results.temp;
$ IF f$search("SYS$SCRATCH:search_results.txt") .nes. "" -
   THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:search_results.txt;
$ EXIT
$!
$!
$! ************************************************
$! * Subroutine to search through a list of users *
$! ************************************************
$!
$ CONDUCT_SEARCHES:
$!
$ IF quiet .eqs. "FALSE"
$ THEN
$    WRITE SYS$OUTPUT "Creating list of all users logged in..."
$ ENDIF
$!
$ DEFINE/USER SYS$OUTPUT SYS$SCRATCH:users_temp.txt
$!
$ argument = "NODUPLICATES"
$ GOSUB ARGUMENT_CHECK
$!
$ IF noduplicates .eqs. "FALSE"
$ THEN
$   IF fast .eqs. "FALSE" ! /sub w/ show users does only subprocesses
$   THEN
$     extra_options = "/SUBPROCESS"
$   ENDIF
$ ELSE
$   extra_options = "/NOSUBPROCESS"
$ ENDIF
$!
$ IF fast .nes. "TRUE"
$ THEN
$    FINGER /ALL /LOCATION /NOLOGIN /NOTERM -
      /NOCPUTIME /PERSONALNAME /PROCESSNAME 'extra_options'
$ ELSE
$    SHOW USERS /FULL 'extra_options'
$ ENDIF
$!
$ DEASSIGN SYS$OUTPUT
$!
$ IF quiet .eqs. "FALSE"
$ THEN
$    IF search_local_addresses .eq. 1
$    THEN
$      sses = ""
$    ELSE
$      sses = "s"
$    ENDIF
$    WRITE SYS$OUTPUT "Searching for " -
                       + f$string(search_local_addresses) -
                       + " local user''sses'..."
$ ENDIF
$!
$ CREATE SYS$SCRATCH:search_results.txt
$ saved_message = f$environment("MESSAGE")
$ SET MESSAGE/NOSEVERITY/NOTEXT/NOFACILITY/NOIDENTIFICATION
$!
$ IF fast .eqs. "TRUE"
$ THEN
$   name_position = 2
$   proc_position = 14
$ ELSE
$   name_position = 17
$   proc_position = 1
$ ENDIF
$ name_size = 11
$ proc_size = 15
$!
$ argument = "AMBIGUOUS"
$ GOSUB ARGUMENT_CHECK
$!
$ IF ambiguous .eqs. "TRUE"
$ THEN
$   search_key_line = ""
$ ELSE
$   search_key_line = "/key=(position=''name_position', size=''name_size')"
$ ENDIF
$!
$ IF noduplicates .eqs. "TRUE"
$ THEN
$   sort_key_line = "/key=(position:''name_position', size:''name_size')"
$ ELSE
$   sort_key_line = "/key=(position:''name_position', size:''name_size')" + -
                    "/key=(position:''proc_position', size:''proc_size')" 
$ ENDIF
$!
$ count = 1
$!
$ SEARCHING_LOOP:
$ search_line = search_line_'count'
$!
$ IF f$search("SYS$SCRATCH:search_results.temp") .nes. ""
$ THEN
$    DELETE/NOCON/NOLOG SYS$SCRATCH:search_results.temp;
$ ENDIF
$!
$ SEARCH/MATCH=OR/OUTPUT=SYS$SCRATCH:search_results.temp -
   'search_key_line' SYS$SCRATCH:users_temp.txt 'search_line'
$!
$ IF f$search("SYS$SCRATCH:search_results.temp") .nes. ""
$ THEN
$    APPEND SYS$SCRATCH:search_results.temp -
      SYS$SCRATCH:search_results.txt
$ ENDIF
$!
$ IF count .lt. search_line_count
$ THEN
$    count = count + 1
$    GOTO SEARCHING_LOOP
$ ENDIF
$ SET MESSAGE 'saved_message'
$!
$ SORT 'sort_key_line' /NODUPLICATES -
   SYS$SCRATCH:search_results.txt SYS$SCRATCH:search_results.txt
$!
$ RETURN
$!
$! **************************************************
$! * Subroutine for checking command line arguments *
$! **************************************************
$!
$ ARGUMENT_CHECK:
$ count = 0
$ CHECK_LOOP:
$ count = count + 1
$ IF p'count' .eqs. ""
$ THEN
$     'argument = "FALSE"
$     RETURN
$ ENDIF
$ IF p'count' .eqs. f$extract(0, f$length(p'count'), argument)
$ THEN
$     'argument = "TRUE"
$     RETURN
$ ENDIF
$ GOTO CHECK_LOOP
$!
$! ************************************************
$! * Subroutine to create a header for the output *
$! ************************************************
$!
$ CREATE_HEADER:
$!
$ IF f$type(bbs_alias) .nes. ""
$ THEN
$    bbs = bbs_alias
$ ELSE
$    bbs = "WHO.COM v.1.5"
$ ENDIF
$ length = 35
$!
$ IF f$length(bbs) .gt. length
$ THEN
$    bbs = f$extract(0, length - 1, bbs) + "~"
$ ENDIF
$!
$ process = f$getjpi("","PRCNAM")
$!
$ address_statistics = "U: " + f$string(search_local_addresses) -
                       + " " -
                       + "I: " + f$string(search_internet_addresses) -
                       + " " -
                       + "D: " + f$string(search_distribution_lists)'
$!
$ string = " " + esc + "(0" + esc + "[1ml"
$ length = f$length(process) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "w"
$ length = f$length(bbs) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "w"
$ length = f$length(address_statistics) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "k" + esc + "[0m" + esc + "(B"
$!
$ top_line  = string
$!
$ bold_line = esc + "(0" + esc + "[1mx" + esc + "[0m" + esc + "(B"
$ middle_line = " " +  bold_line + " " + process + " " + bold_line + -
                " " + bbs + " " + bold_line + " " + address_statistics + -
                " " + bold_line
$!
$ string = " " + esc + "(0" + esc + "[1mm"
$ length = f$length(process) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "v"
$ length = f$length(bbs) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "v"
$ length = f$length(address_statistics) + 2
$!
$ GOSUB ADD_QS
$!
$ string = string + "j" + esc + "[0m" + esc + "(B"
$!
$ bottom_line  = string
$!
$ RETURN
$!
$! ******************************************
$! * Loop to add q's to the end of a string *
$! ******************************************
$!
$ ADD_QS:
$!
$ loop_count = 0
$!
$ Q_LOOP:
$ string = string + "q"
$ loop_count = loop_count + 1
$ IF loop_count .lt. length
$ THEN
$    GOTO Q_LOOP
$ ENDIF
$!
$ RETURN
$!
$! ************************************************
$! * Subroutine to write who output to the screen *
$! ************************************************
$!
$ WRITE_OUTPUT:
$!
$ argument = "NOHEADER"
$ GOSUB ARGUMENT_CHECK
$!
$ IF noheader .eqs. "FALSE"
$ THEN
$   GOSUB CREATE_HEADER
$!
$   WRITE SYS$OUTPUT top_line
$   WRITE SYS$OUTPUT middle_line
$   WRITE SYS$OUTPUT bottom_line
$   WRITE SYS$OUTPUT ""
$ ENDIF
$!
$ argument = "NOTIME"
$ GOSUB ARGUMENT_CHECK
$!
$ IF notime .eqs. "FALSE"
$ THEN
$   'time_program'
$   WRITE SYS$OUTPUT ""
$ ENDIF   
$!
$ count = 0
$!
$ IF f$search("SYS$SCRATCH:search_results.txt") .nes. ""
$ THEN
$    OPEN/READ/ERROR=EXIT -
       output_data SYS$SCRATCH:search_results.txt
$!
$    previous_line = ""
$    nextline = ""
$!
$    SEARCH_USERS_LOOP:
$!
$    READ/END_OF_FILE=BREAK_SEARCH_LOOP output_data nextline
$!
$    IF f$edit(nextline, "TRIM") .nes. ""
$    THEN
$       WRITE SYS$OUTPUT f$extract(0, 80 ,nextline)
$!
$       IF f$edit(f$extract(name_position, name_size, nextline),"TRIM") -
         .nes. f$edit(f$extract(name_position, name_size, previous_line),"TRIM")
$       THEN
$          count = count + 1
$       ENDIF
$!
$       previous_line = nextline
$    ENDIF
$!
$    GOTO SEARCH_USERS_LOOP
$    BREAK_SEARCH_LOOP:
$!
$    CLOSE output_data
$ ENDIF
$!
$ known_users = count
$!
$ argument = "NOPOPULARITY"
$ GOSUB ARGUMENT_CHECK
$!
$ IF nopopularity .eqs. "FALSE"
$ THEN
$    GOSUB WRITE_POPULARITY_RATING
$ ENDIF
$!
$ IF nopopularity .eqs. "FALSE" .and. noheader .eqs. "FALSE"
$ THEN
$   write sys$output ""
$ ENDIF
$!
$ RETURN
$!
$! *********************************************
$! * Subroutine to write the popularity rating *
$! *********************************************
$!
$ WRITE_POPULARITY_RATING:
$!
$ GOSUB GET_TOTAL_USERS
$!
$ IF known_users .gt. 0
$ THEN
$    WRITE SYS$OUTPUT ""
$ ENDIF
$!
$ whole_part = known_users * 100 / total_users
$ decimal_part = known_users * 10000 / total_users
$!
$ IF whole_part .ne. 0
$ THEN
$    IF whole_part .ge. 10
$    THEN
$       decimal_part = f$extract(2, 2, decimal_part)
$    ELSE
$       decimal_part = f$extract(1, 2, decimal_part)
$    ENDIF
$ ENDIF
$!
$ IF known_users .eq. 1
$ THEN
$    user_string = "user"
$ ELSE
$    user_string = "users"
$ ENDIF
$!
$ bold_on = esc + "[1m"
$ bold_off = esc + "[0m"
$ WRITE SYS$OUTPUT "You know " + -
                    bold_on + f$string(known_users) + bold_off + -
                    " " + user_string + " of " + -
                    bold_on + f$string(search_local_addresses -
                       + search_internet_addresses -
                       + search_distribution_lists) + bold_off + -
                    " defined and " + -
                    bold_on + f$string(total_users) + bold_off + -
                    " logged on. Popularity Rating: " + -
                    bold_on + f$string(whole_part) + "." + -
                    f$string(decimal_part) + "%" + bold_off
$ RETURN
$!
$!
$!
$! *********************************************************
$! * Subroutine to get the total number of users logged on *
$! *********************************************************
$!
$ GET_TOTAL_USERS:
$!
$ OPEN/READ/ERROR=NO_TOTAL_USERS data SYS$SCRATCH:users_temp.txt
$ IF fast .eqs. "FALSE"
$ THEN
$   READ data nextline
$   READ data nextline
$   READ data nextline
$   READ data nextline
$!
$   total_users = f$extract(0, f$locate("Users,", nextline), -
                                   nextline)
$ ELSE
$   READ data nextline
$   READ data nextline
$!
$   total_users = f$extract(f$locate("=", nextline) + 1, -
                     f$locate(",", nextline) - f$locate("=", nextline) - 1, -
                     nextline)
$ ENDIF
$ total_users = f$integer(f$edit(total_users, "TRIM"))
$!
$ CLOSE data
$ RETURN
$!
$ NO_TOTAL_USERS:
$ total_users = local_users
$ RETURN
$!
$! ****************************************
$! * Subroutine to print help information *
$! ****************************************
$!
$ PRINT_HELP:
$!
$ TYPE SYS$INPUT:
  I forget all the options for this program, but what I do remember is:
  who [help] [clear] [fast] [quiet] [ambiguous] [notime] [nopopularity]
      [noheader] [noduplicates]

  help -> prints this message
  clear -> clears the screen before displaying
  fast -> searches from show users instead of finger (which displays
           faster but includes less information)
  quiet -> does not tell about the process of the rpogram running
  ambiguous -> will search the entire listing for usernames
                instead of just the column with the username
  notime -> doesn't display time
  nopopularity -> no popularity rating
  noheader -> the header is the box which has your process name, your
               bbs_alias (if that is not set, then the version of who),
               and then the number of gemini users in your names.dat (U),
               the number of internet addresses (I), and the number of
               distribution lists (D).
  noduplicates -> each person logged on will get no more than one line in
                   the listing

  That should be about all of it I think. =)

   If you have any questions or bugs to report e-mail me at WJH3957.

$ EXIT
$ RETURN
$!
$!
$!
$! ******************************
$! * Subroutine to print totals *
$! ******************************
$!
$ PRINT_TOTALS:
$!
$ temp_internet_addresses = search_internet_addresses
$ temp_local_addresses = search_local_addresses
$ temp_distribution_lists = search_distribution_lists
$!
$ GOSUB SET_ADDRESS_OUTPUT_STRINGS
$!
$ IF quiet .eqs. "TRUE" -
   .and. set_symbols .eqs. "TRUE"
$ THEN
$    WRITE SYS$OUTPUT "  [1mWho:[0m " -
                       + f$string(search_local_addresses) -
                       + la_string -
                       + f$string(search_internet_addresses) -
                       + ia_string -
                       + f$string(search_distribution_lists) -
                       + dl_string
$ ELSE
$    WRITE SYS$OUTPUT "Parsed: " -
                      + f$string(search_local_addresses) -
                      + la_string -
                      + f$string(search_internet_addresses) -
                      + ia_string -
                      + f$string(search_distribution_lists) -
                      + dl_string
$ ENDIF
$!
$ RETURN
$!
$!
$!
$! ********************************************
$! * Subroutine to set the strings for output *
$! ********************************************
$!
$ SET_ADDRESS_OUTPUT_STRINGS:
$!
$ IF temp_local_addresses .eq. 1
$ THEN
$    la_string = " local address, "
$ ELSE
$    la_string = " local addresses, "
$ ENDIF
$!
$ IF temp_internet_addresses .eq. 1
$ THEN
$    ia_string = " internet address, "
$ ELSE
$    ia_string = " internet addresses, "
$ ENDIF
$!
$ IF temp_distribution_lists .eq. 1
$ THEN
$    dl_string = " distribution list..."
$ ELSE
$    dl_string = " distribution lists..."
$ ENDIF
$!
$ RETURN
