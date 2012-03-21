$! Program to check which helpdeskers are logged on
$! 2002/01/16 - wjh
$!
$! On lines where the location name would make the total line length
$!  greater than 80 characters the location is placed on the next line
$!  indented such that the last character is in column 80.
$! This is an issue when the search is perfomed on the location and
$!  the line containing the location is blank except for the location.
$! This program preprocesses the output so that the location is on the
$!  same line as the username so that an adequate record may be kept.
$!
$! The output for show users has different column lengths depending on
$!  the longest name in the column.
$!
$! The output from define sys$output is different depending on if the
$!  job is being run in interactive or batch mode. If the /user_mode
$!  flag is not set then in batch mode not only will the interactive
$!  output be sent to sys$output, but also any lines of the program
$!  that are processed.
$!
$! Added two debuggin options. One, if the variable clean_up is
$!  set to "false" then the files that are created will not be
$!  deleted. (Filenames are now distinct for each iteration except
$!  for the output.) Also the program will now take a numeric
$!  argument and it will only loop that many times. If no argument
$!  is provided then it runs forever.
$!
$ setup scratch
$!
$ start_time = f$time()  ! Exe: 30-JAN-2002 10:03:07.94
$ timestamp = f$cvtime(start_time, "ABSOLUTE", "HOUR") + -
   "_" + f$cvtime(start_time, "ABSOLUTE", "MINUTE") + -
   "_" + f$cvtime(start_time, "ABSOLUTE", "SECOND")
$!
$ show_users_file = "sys$scratch:show_users-''timestamp'.temp"
$ finger_file     = "sys$scratch:finger-''timestamp'.temp"
$ search_file     = "sys$scratch:search-''timestamp'.temp"
$ temp_file       = "sys$scratch:search_temp-''timestamp'.temp"
$ output_file    := sys$login:helpdesk_check.txt ! disk$research:[pjt.lab]whd.dat
$!
$ helpdesk_locations = "LPSVR2," + -
                       "ch313-hd.pclab," + -
                       "ch215-hd.pclab," + -
                       "ph204-hd.pclab," + -
                       "-ts.pclab"
$!
$! For debugging purposes allow the detetion of temporary files
$!  to be disabled. Set this to "false" to leave temporary files
$!
$ clean_up = "true"
$!
$! First get a list of the users logged on
$!
$ define /user_mode sys$output 'show_users_file'
$ show users /full
$ deassign sys$output
$!
$ define /user_mode sys$output 'finger_file'
$ finger /personalname /noterminal /nottt /nolocation /noprocess -
   /nocpu /all
$ deassign sys$output
$!
$! Next process the file to be suitable for searching
$!
$ open /read /error=NO_FILE data 'show_users_file'
$!
$ SKIP_SHOW_USERS_HEADER
$ read /end_of_file=END_READ_LOOP data nextline
$ if f$locate("Username", nextline) .eq. f$length(nextline) -
   then goto SKIP_SHOW_USERS_HEADER
$!
$ username_length = f$locate("Process Name", nextline) - 1
$ pid_index = f$locate("PID", nextline) - 2
$ terminal_index = f$locate("Terminal", nextline)
$ location_index = terminal_index + f$length("Terminal")
$!
$ open /write /error=NO_FILE output 'search_file'
$!
$ location = "start"
$ READ_LOOP:
$ read /end_of_file=END_READ_LOOP data nextline
$!
$! Skip subprocesses
$!
$ if f$extract(terminal_index + 1, 10, nextline) .eqs. "subprocess" -
   then goto READ_LOOP
$!
$! Batch processes also have no location
$!
$ if f$extract(terminal_index + 1, 5, nextline) .eqs. "Batch" -
   then goto READ_LOOP
$!
$ if location .nes. ""
$ then
$   username = f$extract(1, username_length, nextline)
$   location = f$extract(location_index, -
                         f$length(nextline) - location_index, -
                         nextline)
$ else
$   location = nextline
$ endif
$ location = f$edit(location, "TRIM")
$!
$ if location .nes. ""
$ then
$   location = f$extract(1, f$length(location) - 2, location)
$   write output username, location
$ endif
$!
$ goto READ_LOOP
$!
$ END_READ_LOOP:
$ close output
$ close data
$ if clean_up .nes. "false" then delete /nolog /noconfirm 'show_users_file';
$!
$! Search the processed output
$!
$ search /nohighlight /key=(position:'username_length') -
   /output='temp_file' 'search_file' 'helpdesk_locations'
$!
$ if clean_up .nes. "false" then delete /noconfirm /nolog 'search_file';
$!
$! The info from show users is not sufficient so it needs to be
$!  combined with info from finger to look correct.
$!
$ open /read /error=NO_FILE data 'temp_file'
$ GET_NAMES_LOOP:
$ read /end_of_file=END_GET_NAMES_LOOP data nextline
$ username = f$extract(0, 'username_length', nextline)
$ if f$type(location_'username') .nes. "" then goto GET_NAMES_LOOP
$ location_'username' = -
   f$extract(username_length, -
             f$length(nextline) - username_length, -
             nextline)
$ goto GET_NAMES_LOOP
$!
$ END_GET_NAMES_LOOP:
$ close data
$ if clean_up .nes. "false" then delete /nolog /noconfirm 'temp_file';
$!
$ if f$search(output_file) .eqs. ""
$ then
$   create 'output_file'
$   file_created = "true"
$ else
$   file_created = "false"
$ endif
$!
$ open /append /error=NO_FILE output 'output_file'
$ if file_created .eqs. "false" then write output ""
$ write output f$edit(f$extract(0, 20, start_time), "TRIM")
$!
$ open /read /error=NO_FILE data 'finger_file'
$ count = 0
$ SKIP_FINGER_HEADER:
$ read /end_of_file=END_PRINT_LOOP data nextline
$ count = count + 1
$ if count .lt. 7 then goto SKIP_FINGER_HEADER
$!
$ PRINT_LOOP:
$ read /end_of_file=END_PRINT_LOOP data nextline
$ username = f$edit(f$extract(0, 12, nextline), "TRIM")
$ if username .eqs. "<login>" then goto PRINT_LOOP
$ if f$type(location_'username') .eqs. "" then goto PRINT_LOOP
$ if location_'username' .eqs. "" then goto PRINT_LOOP
$ write output nextline, " ", location_'username'
$ location_'username' = ""
$ goto PRINT_LOOP
$!
$ END_PRINT_LOOP:
$ close output
$ close data
$ if clean_up .nes. "false" then delete /noconfirm /nolog 'finger_file';
$!
$ END:
$!
$! Set the program to run again in one hour.
$! Added an optional count to allow the program to run a limited
$!  number of times for testing puproses.
$!
$ continue = "true"
$ if p1 .nes. ""
$ then
$   count_arg = f$integer(p1) - 1
$   if count_arg .gt. 0
$   then
$     count_arg = "/parameters=(" + f$string(count_arg) + ")"
$   else
$     continue = "false"
$   endif
$ else
$   count_arg = ""
$ endif
$!
$ if continue .eqs. "true"
$ then
$   submit /nolog_file /queue=batch10 /after="''start_time' + 01:00" -
     'count_arg 'f$environment("PROCEDURE")
$ endif
$ exit
$!
$ NO_FILE:
$ goto END
