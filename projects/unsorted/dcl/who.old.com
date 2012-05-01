$! Who.com: Will Holcomb: This program has been written in about
$!  10 differnt ways over the last two years. This incarnation
$!  uses another program called getnames.com to define a list
$!  of names to search for in global symbols search_line_* and
$!  then searches for those names. The intended use is for
$!  getnames to be run one at login and do some other work that
$!  does and thereafter the searches are done from the global
$!  variables which cuts the file parsing and greatly improves
$!  run time. 10/12/99 wjh
$!
$ gn = "@user:[wjh3957.honors]getnames.com silent"
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
$ SETUP SCRATCH
$!
$ IF f$type(search_line_count) .eqs. ""
$ THEN
$    gn
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
$ IF fast .nes. "TRUE"
$ THEN
$    FINGER /SUBPROCESS /LOCATION /NOLOGIN /NOTERM /SORT=LAST_NAME -
      /NOCPUTIME /PERSONALNAME /PROCESSNAME
$ ELSE
$    SHOW USERS/FULL/INTERACTIVE/SUBPROCESS
$ ENDIF
$!
$ DEASSIGN SYS$OUTPUT
$!
$ IF quiet .eqs. "FALSE"
$ THEN
$    IF search_local_addresses .eq. 1
$    THEN
$       WRITE SYS$OUTPUT "Searching for " -
                         + f$string(search_local_addresses) -
                         + " local user..."
$    ELSE
$       WRITE SYS$OUTPUT "Searching for " -
                         + f$string(search_local_addresses) -
                         + " local users..."
$    ENDIF
$ ENDIF
$!
$ count = 1
$!
$ CREATE SYS$SCRATCH:search_results.txt
$ saved_message = f$environment("MESSAGE")
$ SET MESSAGE/NOSEVERITY/NOTEXT/NOFACILITY/NOIDENTIFICATION
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
   /key=(position=17,size=10) SYS$SCRATCH:users_temp.txt 'search_line'
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
$ SORT/KEY=(POS:1, SIZE:15)/NODUPLICATE -
   SYS$SCRATCH:search_results.txt SYS$SCRATCH:search_results.txt
$ IF fast .eqs. "FALSE"
$ THEN
$    SORT/KEY=(POS:17, SIZE:10)/DUPLICATE -
      SYS$SCRATCH:search_results.txt  SYS$SCRATCH:search_results.txt
$ ENDIF
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
$    bbs = "WHO.COM v.1.4"
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
$ IF fast .eqs. "FALSE"
$ THEN
$    GOSUB CREATE_HEADER
$!
$    WRITE SYS$OUTPUT top_line
$    WRITE SYS$OUTPUT middle_line
$    WRITE SYS$OUTPUT bottom_line
$!
$    WRITE SYS$OUTPUT ""
$!
$!
$    argument = "NOTIME"
$    GOSUB ARGUMENT_CHECK
$!
$    IF fast .eqs. "FALSE" .and. notime .eqs. "FALSE"
$    THEN
$       run USER:[WJH3957.PROGRAMS]time.exe
$    ENDIF   
$!
$ ENDIF
$!
$ WRITE SYS$OUTPUT ""
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
$       IF f$edit(f$extract(15,10,nextline),"TRIM") -
         .nes. f$edit(f$extract(15,10,previous_line),"TRIM")
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
$ IF fast .eqs. "FALSE" .and. nopopularity .eqs. "FALSE"
$ THEN
$    GOSUB WRITE_POPULARITY_RATING
$ ELSE
$    WRITE SYS$OUTPUT ""
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
$ WRITE SYS$OUTPUT ""
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
$ READ data nextline
$ READ data nextline
$ READ data nextline
$ READ data nextline
$!
$ total_users = f$extract(f$locate("Users,", nextline) - 5, -
                                   5, -
                                   nextline)
$ IF f$locate(",", total_users) .ne. f$length(total_users)
$ THEN
$    total_users = f$extract(f$locate(",", total_users), -
          f$length(total_users) - f$locate(",", total_users), -
          total_users)
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
