$ saved_directory = f$environment("DEFAULT")
$ SET DEFAULT SYS$LOGIN
$ saved_message = f$environment("MESSAGE")
$ SET MESSAGE /notext/nofac/noid/nose
$!
$! !
$! !*** Log user information ***
$! !
$! log_file = "USER:[WJH3957.PROGRAMS]utilities_users.dat"
$! username = f$edit(f$getjpi("","USERNAME"),"COLLAPSE")
$! program_name = "DISWHO.COM"
$! gosub LOG_USER
$!
$! Get a directory listing of all .dis lists
$!
$ ASSIGN SYS$SCRATCH:dis_lists.txt SYS$OUTPUT
$ DIRECTORY/COLUMNS=1/WIDTH=(FILENAME=60) USER:[...]*.dis
$ DEASSIGN SYS$OUTPUT
$!
$! Read the listing of .dis lists and set them up in an array
$! 
$ OPEN/ERROR=NO_FILE data SYS$SCRATCH:dis_lists.txt
$ count = 0
$ GET_FILES_LOOP:
$ READ/END_OF_FILE=BREAK_FILES_LOOP data nextline
$!
$ IF f$length(nextline) .eqs. f$locate(";",nextline)
$ THEN
$    GOTO GET_FILES_LOOP
$ ELSE
$    count = count + 1
$    file_'count' = -
     f$search("USER:[...]''f$extract(0, f$locate(";", nextline), nextline)'")
$    GOTO GET_FILES_LOOP
$ ENDIF
$ BREAK_FILES_LOOP:
$ CLOSE data
$!
$! Add the honors everyone.dis to the end of the array
$!
$ IF (p1 .nes. "") .and. (p1 .eqs. f$extract(0, f$length(p1), "NOHONORS"))
$ THEN
$    GOTO SKIP_HONORS_DIS
$ ENDIF
$ IF f$search("USER:[HONORS.PUBLIC]EVERYONE.DIS") .nes. ""
$ THEN
$    count = count + 1
$    file_'count' = -
     f$search("USER:[HONORS.PUBLIC]EVERYONE.DIS")    
$ ENDIF
$ list_num = 0
$ HONORS_101_DIS_LISTS:
$ list_num = list_num + 1
$ IF f$length(list_num) .eq. 1 THEN list_num = "0" + f$string(list_num)
$ IF f$search("USER:[HONORS.PUBLIC]HON101''list_num'.DIS;") .nes. ""
$ THEN
$    count = count + 1
$    file_'count' = -
     f$search("USER:[HONORS.PUBLIC]HON101''list_num'.DIS")
$    GOTO HONORS_101_DIS_LISTS
$ ENDIF
$!
$ SKIP_HONORS_DIS:
$ max_dis_lists = count
$ TTU_SYSTEM:ERASE
$ IF max_dis_lists .eq. 0
$ THEN
$    WRITE SYS$OUTPUT ""
$    WRITE SYS$OUTPUT "No .dis lists found."
$    GOTO EXIT
$ ENDIF
$ BEGIN_MENU_LOOP:
$ WRITE SYS$OUTPUT ""
$ count = 1
$ MENU_LOOP:
$ IF f$length(count) .lt. 2
$ THEN WRITE SYS$OUTPUT "#''count':  " + file_'count'
$ ELSE WRITE SYS$OUTPUT "#''count': " + file_'count'
$ ENDIF
$ IF count .lt. max_dis_lists
$ THEN 
$    count = count + 1
$    GOTO MENU_LOOP
$ ENDIF
$ WRITE SYS$OUTPUT ""
$ INQUIRE file "Which file would you like to search"
$ WRITE SYS$OUTPUT ""
$ IF file .eqs. "" THEN GOTO EXIT
$ IF f$integer(file) .eq. 0 .or. file .gt. max_dis_lists
$ THEN
$    TTU_SYSTEM:ERASE
$    WRITE SYS$OUTPUT "''file' is not a valid response."
$    WRITE SYS$OUTPUT "Press RETURN to exit."
$ GOTO BEGIN_MENU_LOOP
$ ENDIF
$ file = file_'file'
$!
$ DEFINE/USER SYS$OUTPUT SYS$SCRATCH:users.txt
$ FINGER /SUBPROCESS /NOIDLETIME /LOCATION /NOTERMINAL -
  /NOCPUTIME /NOLOGINTIME /IMAGENAME /PERSONALNAME /PROCESSNAME
$ DEASSIGN SYS$OUTPUT
$!
$ dis_line_list = ""
$ OPEN/ERROR=NO_FILE dis_data 'file
$ FIND_USERS_LOOP:
$ READ/END_OF_FILE=BREAK_USERS_LOOP dis_data dis_line
$ dis_line = f$edit(dis_line,"UNCOMMENT,TRIM")
$ IF dis_line .eqs. "" THEN GOTO FIND_USERS_LOOP
$ IF f$locate("IN%",dis_line) .eq. 0
  .or. f$locate("in%",dis_line) .eq. 0
$ THEN GOTO FIND_USERS_LOOP
$ ENDIF
$ IF f$locate("TTU::",dis_line) .nes. f$length(dis_line)
$ THEN
$    dis_line = -
     f$extract(f$locate("TTU::",dis_line) + 5,f$length(dis_line) - 5,dis_line)
$ ENDIF
$ IF f$locate("ttu::",dis_line) .nes. f$length(dis_line)
$ THEN
$    dis_line = -
     f$extract(f$locate("ttu::",dis_line) + 5,f$length(dis_line) - 5,dis_line)
$ ENDIF
$ IF dis_line_list .eqs. ""
$ THEN dis_line_list = dis_line
$ ELSE dis_line_list = dis_line_list + "," + dis_line
$ ENDIF
$ GOTO FIND_USERS_LOOP
$ BREAK_USERS_LOOP:
$ CLOSE dis_data
$!
$ DEFINE/USER SYS$OUTPUT SYS$SCRATCH:search_results.txt
$ SEARCH/MATCH=OR/LOG/STAT SYS$SCRATCH:users.txt 'dis_line_list
$ DEASSIGN SYS$OUTPUT
$!
$ IF f$search("SYS$SCRATCH:search_results.txt") .nes. ""
$ THEN
$    OPEN/READ output_data SYS$SCRATCH:search_results.txt
$    count = 0
$    previous_line = ""
$    SEARCH_USERS_LOOP:
$    READ/END_OF_FILE=BREAK_SEARCH_LOOP output_data next_line
$    WRITE SYS$OUTPUT f$extract(0, 80 ,next_line)
$    IF f$edit(f$extract(15,10,next_line),"TRIM") -
     .nes. f$edit(f$extract(15,10,previous_line),"TRIM")
$    THEN
$       count = count + 1
$    ENDIF
$    previous_line = next_line
$    GOTO SEARCH_USERS_LOOP
$    BREAK_SEARCH_LOOP:
$    CLOSE output_data
$ ELSE
$    WRITE SYS$OUTPUT "Nobody loves you   =("
$    WRITE SYS$OUTPUT "No users in ''file' found."
$ ENDIF
$ known_users = count
$!
$ GOTO END
$ NO_FILE:
$ WRITE SYS$OUTPUT "File not found"
$ GOTO END
$ END:
$ WRITE SYS$OUTPUT ""
$ IF known_users .eq. 1
$ THEN WRITE SYS$OUTPUT "''known_users' person from ''file' is logged in right now."
$ ELSE WRITE SYS$OUTPUT "''known_users' people from ''file' are logged in right now."
$ ENDIF
$ WRITE SYS$OUTPUT ""
$ EXIT:
$ IF f$search("SYS$SCRATCH:dis_lists.txt") .nes. "" -
  THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:dis_lists.txt;
$ IF f$search("SYS$SCRATCH:users.txt") .nes. "" -
  THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:users.txt;
$ IF f$search("SYS$SCRATCH:search_results.txt") .nes. "" -
  THEN DELETE/NOLOG/NOCONFIRM SYS$SCRATCH:search_results.txt;
$ SET DEFAULT 'saved_directory
$ SET MESSAGE 'saved_message 
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
