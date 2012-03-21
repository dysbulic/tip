$! !
$! !*** Log user information ***
$! !
$! log_file = "USER:[WJH3957.PROGRAMS]utilities_users.dat"
$! username = f$edit(f$getjpi("","USERNAME"),"COLLAPSE")
$! program_name = "GRAPH.COM"
$! gosub LOG_USER
$ !
$ !*** Get current default directory, then set directory to sys$login ****
$ !
$ saved_directory = f$environment("DEFAULT")
$ SET DEFAULT SYS$LOGIN
$ !
$ !*** change this to the length of the graph desired ****
$ !
$ graph_size = 73  
$ !
$ !*** reassign output and get quota info ***
$ !
$ ASSIGN SYS$SCRATCH:quota.txt SYS$OUTPUT
$ SHOW QUOTA
$ DEASSIGN SYS$OUTPUT
$ !
$ ! Set the escape character to a variable so the program can be mailed
$ !
$ E[0,8] = 27
$ !
$ ! *** open file for input, read line, extract quota info ***
$ !
$ OPEN/READ data SYS$SCRATCH:quota.txt
$ READ data nextline
$ blocks_used = f$extract(f$locate("has ",nextline) + 4, -
                f$locate("blocks",nextline) - f$locate("has ",nextline) - 5, -
                nextline)
$ IF f$locate("OVERDRAWN", nextline) .eq. f$length(nextline)
$ THEN
$    blocks_left = f$extract(f$locate("used,",nextline) + 6, -
                   f$locate("available",nextline) - f$locate("used,",nextline) - 6, -
                   nextline)
$    blocks_total = f$integer(blocks_used) + f$integer(blocks_left)
$ ELSE
$    blocks_left = f$extract(f$locate("used,",nextline) + 6, -
                   f$locate("OVERDRAWN",nextline) - f$locate("used,",nextline) - 6, -
                   nextline)
$    WRITE SYS$OUTPUT "''e'#3''e'[1m " + -
                      f$edit(f$getjpi("","USERNAME"),"COLLAPSE") + -
                      " is " + f$edit(blocks_left,"TRIM") + " blocks overdrawn." 
$    WRITE SYS$OUTPUT "''e'#4''e'[1m " + -
                      f$edit(f$getjpi("","USERNAME"),"COLLAPSE") + -
                      " is " + f$edit(blocks_left,"TRIM") + " blocks overdrawn." 
$    blocks_total = f$integer(blocks_used)
$ ENDIF
$ BREAK_READ:
$ CLOSE data
$!
$ DELETE/NOCONFIRM/NOLOG SYS$SCRATCH:quota.txt;
$ !
$ ! *** create the graph ***
$ !
$ graph_line = ""
$!
$ GRAPH_LINE_LOOP:
$ IF f$length(graph_line) * blocks_total / graph_size .le. blocks_used
$ THEN
$    graph_line = graph_line + "a"
$ ELSE
$    graph_line = graph_line + "q"
$ ENDIF 
$!
$ IF f$length(graph_line) .lt. graph_size
$ THEN
$    GOTO GRAPH_LINE_LOOP
$ ENDIF
$!
$ SET_COLORS:
$ first_third = f$extract(0, f$integer(graph_size / 3), graph_line)
$ second_third = f$extract(f$integer(graph_size / 3), -
                           f$integer(graph_size / 3), -
                           graph_line)
$ third_third = f$extract(f$integer(2 * graph_size / 3), -
                          f$integer(graph_size / 3 + 1), -
                          graph_line)
$!
$ IF f$extract(f$length(first_third) - 1, 1, first_third) .eqs. "q"
$ THEN
$    graph_line = "''e'[32;1m''e'(0" + -
                  f$extract(0, f$locate("q",first_third), first_third) + -
                  "''e'[m" + -
                  f$extract(f$locate("q",first_third), -
                            f$length(first_third) - f$locate("q",first_third), -
                            first_third) + -
                  second_third + -
                  third_third 
$    GOTO FORM_NUMBER_LINE
$ ENDIF
$!
$ IF f$extract(f$length(second_third) - 1, 1, second_third) .eqs. "q"
$ THEN
$    graph_line = "''e'[32;1m''e'(0" + -
                  first_third + -
                  "''e'[33m" + -
                  f$extract(0, f$locate("q",second_third), second_third) + -
                  "''e'[m" + -
                  f$extract(f$locate("q",second_third), -
                            f$length(second_third) - f$locate("q",second_third), -
                            second_third) + -
                  third_third
$    GOTO FORM_NUMBER_LINE
$ ENDIF
$!
$ graph_line = "''e'[32;1m''e'(0" + -
                first_third + -
                "''e'[33m" + -
                second_third + -
                "''e'[31m" + -
                f$extract(0, f$locate("q",third_third), third_third) + -
                "''e'[m" + -
                f$extract(f$locate("q",third_third), -
                          f$length(third_third) - f$locate("q",third_third), -
                          third_third)
$!
$!
$!
$ FORM_NUMBER_LINE:
$ IF f$locate("5",blocks_total) .eq. 1
$ THEN
     num_divisions = 10
$ ELSE
$    num_divisions = 9
$ ENDIF
$ IF f$integer(p1) .gt. 1 THEN num_divisions = p1
$!
$ segment_length = f$integer(graph_size / (num_divisions - 1))
$ marker_line = ""
$ marker_line_temp = ""
$ MARKER_LINE_LOOP:
$ IF f$length(marker_line_temp) .lt. (segment_length - 1)
$ THEN
$    marker_line_temp = marker_line_temp + " "
$    GOTO MARKER_LINE_LOOP
$ ELSE
$    marker_line_temp = marker_line_temp + "^"
$ ENDIF
$ marker_line = marker_line + marker_line_temp
$ IF f$length(marker_line) .lt. (graph_size - segment_length)
$ THEN
$    marker_line_temp = ""
$    GOTO MARKER_LINE_LOOP
$ ENDIF       
$!
$!
$ number_line = ""
$ number_line_temp = ""
$ num_between_divisions = f$integer(blocks_total / (num_divisions - 1))
$ current_num = f$string(num_between_divisions)
$ NUMBER_LINE_LOOP:
$ IF f$length(number_line_temp) + f$length(current_num) .lt. (segment_length)
$ THEN
$    number_line_temp = f$string(number_line_temp) + " "
$    GOTO NUMBER_LINE_LOOP
$ ELSE
$    number_line_temp = f$string(number_line_temp + current_num)
$    IF f$length(number_line) .ge. (graph_size - 2 * segment_length) - 1
$    THEN
$       current_num = f$string(blocks_total)
$    ELSE
$       current_num = f$string(f$integer(current_num + num_between_divisions))
$    ENDIF
$ ENDIF
$ number_line = f$string(number_line + number_line_temp)
$ IF f$length(number_line) .lt. (graph_size - segment_length)
$ THEN
$    number_line_temp = ""
$    GOTO NUMBER_LINE_LOOP
$ ENDIF       
$!
$!
$!
$!
$ PRINT_GRAPH:
$ SET DEFAULT 'saved_directory
$ WRITE SYS$OUTPUT ""
$ WRITE SYS$OUTPUT " " + graph_line + "''e'[m''e'(B"
$ WRITE SYS$OUTPUT " ^" + marker_line
$ WRITE SYS$OUTPUT " 0" + number_line
$!
$ IF f$locate("DISK$SCRATCH",saved_directory) .eqs. f$length(saved_directory)
$ THEN
$    WRITE SYS$OUTPUT f$extract(1, f$locate("of", nextline) - 3, nextline) + -
                      f$extract(f$locate("of",nextline) - 1, -
                                f$length(nextline) - f$locate("of",nextline) + 1, -
                                nextline)                                                   
$ ELSE
$    WRITE SYS$OUTPUT " " + saved_directory + " does not have disk quotas enabled."
$ ENDIF
$ EXIT
$ !
$ !
$ !
$ LOG_USER:
$ !
$ USERNAME_LENGTH_LOOP:
$ IF f$length(username) .lt. 10
$ THEN
$    username = username + " "
$    GOTO USERNAME_LENGTH_LOOP
$ ENDIF
$ !
$ PROGRAM_NAME_LENGTH_LOOP:
$ IF f$length(program_name) .lt. 25
$ THEN
$    program_name = program_name + " "
$    GOTO PROGRAM_NAME_LENGTH_LOOP
$ ENDIF
$ !
$ new_user = "TRUE"
$!
$ IF f$search("''log_file'") .nes. ""
$ THEN
$ !
$    COPY/NOLOG 'log_file' SYS$SCRATCH:logfile.tmp
$    OPEN/READ old_log_file SYS$SCRATCH:logfile.tmp
$    OPEN/WRITE new_log_file SYS$SCRATCH:logfile.new
$ !
$    WRITE_LOG_FILE_LOOP:
$    READ/END_OF_FILE=BREAK_LOG_FILE_LOOP old_log_file nextline
$    IF f$edit(nextline,"COLLAPSE") .eqs. ""
$    THEN
$       GOTO WRITE_LOG_FILE_LOOP
$    ENDIF
$    IF f$edit(f$extract(0,10,nextline),"TRIM") .eqs. f$edit(username,"TRIM") -
     .and. f$edit(f$extract(10,23,nextline),"TRIM") .eqs. f$edit(program_name,"TRIM") 
$    THEN
$       accesses = f$extract(f$locate("Accesses: ",nextline) + 10, -
                   f$length(nextline) - f$locate("Accesses: ",nextline) - 10, -
                   nextline)
$       accesses = f$string(accesses + 1)
$       nextline = username + program_name + f$time() + "    Accesses: " + accesses
$       IF new_user = "TRUE"
$       THEN
$          WRITE new_log_file nextline
$          new_user = "FALSE"
$       ENDIF
$    ELSE
$       WRITE new_log_file nextline
$    ENDIF
$!
$    GOTO WRITE_LOG_FILE_LOOP
$!
$    BREAK_LOG_FILE_LOOP:
$!
$    IF new_user .eqs. "TRUE"
$    THEN
$       nextline = username + program_name + f$time() + "    Accesses: 1"
$       WRITE new_log_file nextline 
$       program_virgin = "TRUE"
$    ENDIF
$    CLOSE new_log_file
$    CLOSE old_log_file
$    COPY/NOLOG SYS$SCRATCH:logfile.new 'log_file' 
$    DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:logfile.new;
$    DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:logfile.tmp;
$ ENDIF
$ RETURN
