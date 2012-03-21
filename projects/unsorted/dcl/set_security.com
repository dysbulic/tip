$ saved_directory = f$environment("DEFAULT")
$! SET DEFAULT SYS$LOGIN
$ saved_message = f$environment("MESSAGE")
$! SET MESSAGE /notext/nofac/noid/nose
$!
$ IF p1 .eqs. "" .and. p2 .eqs. ""
$ THEN
$    TYPE SYS$INPUT
 set_security.com  Written by Will Holcomb, February 23, 1998

 Usage: set_security filename1.ext filename2.ext

 Where filename1.ext is the name of a .dis list that you wish to
 create an access control list from and filename2.ext in the file which
 you wish to set the protections on.

$ GOTO END
$ ENDIF
$!
$!
$ access_type = ""
$!
$!
$ INQUIRE read_access "Do you wish to give the users read access? [y]/n"
$!
$ IF f$edit(read_access, "UPCASE") .nes. "N"
$ THEN
$    IF f$length(access_type) .eq. 0
$    THEN
$       access_type = "r" 
$    ELSE
$       access_type = access_type + "+" + "r" 
$    ENDIF
$ ENDIF
$!
$ INQUIRE write_access "Do you wish to give the users write access? y/[n]"
$!
$ IF f$edit(write_access, "UPCASE") .eqs. "Y"
$ THEN
$    IF f$length(access_type) .eq. 0
$    THEN
$       access_type = "w"
$    ELSE
$       access_type = access_type + "+" + "w" 
$    ENDIF
$ ENDIF
$!
$ INQUIRE execute_access "Do you wish to give the users execute access? [y]/n"
$!
$ IF f$edit(execute_access, "UPCASE") .nes. "N"
$ THEN
$    IF f$length(access_type) .eq. 0
$    THEN
$       access_type = "e" 
$    ELSE
$       access_type = access_type + "+" + "e"
$    ENDIF
$ ENDIF
$!
$ INQUIRE delete_access "Do you wish to give the users delete access? y/[n]"
$!
$ IF f$edit(delete_access, "UPCASE") .eqs. "Y"
$ THEN
$    IF f$length(access_type) .eq. 0
$    THEN
$       access_type = "d" 
$    ELSE
$       access_type = access_type + "+" + "d" 
$    ENDIF
$ ENDIF
$!
$!
$ OPEN/ERROR=NO_FILE dis_data 'p1
$!
$ SET_SECURITY_LOOP:
$ READ/END_OF_FILE=BREAK_SET_SECURITY_LOOP dis_data dis_line
$ dis_line = f$edit(dis_line, "UNCOMMENT,TRIM,UPCASE")
$!
$ IF dis_line .eqs. "" THEN GOTO SET_SECURITY_LOOP
$!
$ IF f$locate("IN%", dis_line) .eq. 0
$ THEN
$    GOTO SET_SECURITY_LOOP
$ ENDIF
$!
$ IF f$locate("TTU::", dis_line) .nes. f$length(dis_line)
$ THEN
$    dis_line = -
     f$extract(f$locate("TTU::",dis_line) + 5, f$length(dis_line) - 5, dis_line)
$ ENDIF
$!
$ write sys$output "Giving " + dis_line + " " + access_type + " access"
$ SET SECURITY/ACL=(IDENTIFIER='dis_line', ACCESS='access_type') 'p2'
$!
$ GOTO SET_SECURITY_LOOP
$ BREAK_SET_SECURITY_LOOP:
$ CLOSE dis_data
$!
$ END:
$ SET DEFAULT 'saved_directory'
$ SET MESSAGE 'saved_message'
$ EXIT
$ NO_FILE:
$ WRITE SYS$OUTPUT "Error: File not found."
$ GOTO END
