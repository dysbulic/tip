$!***************************************************************************
$!**** Process Randomizer -- Author Unknown                              ****
$!**** Menu Function, Max Set Loop, Ability to Accept 999 Names,         ****
$!****  Command Line Capabilities, and Help Information                  ****
$!****                              -added April 1997 by dysbulic        ****
$!****                                                                   ****
$!**** To add new names, copy the last line and change the numbers as    ****
$!**** needed. The program will stop reading at the first empty          ****
$!**** definition. Exe. name_XXX = "" All process names thereafter will  ****
$!**** not be considered.                                                ****
$!****                                                                   ****
$!**** All that is gone now. The program now reads its process names     ****
$!****  from a file. There is no max and the original program has once   ****
$!****  again been lost in my incesant revisions. =) 1999/3/25 dys       ****
$!***************************************************************************
$!
$ on warning then goto end
$ on error then goto end
$ on severe_error then goto end
$ on control_y then goto end
$!
$ argument = "HELP"
$ gosub ARGUMENT_CHECK
$!
$ IF HELP .eqs. "TRUE"
$ THEN
$   TYPE sys$input:

Process Randomizer --
 Usage: process.com [[HELP] [RANDOM] [MENU] [process name]]
                    [SILENT] [STATUS]
   [] -> Provides the user with a prompt for a name
   HELP -> Prints this message
   RANDOM -> Chooses a process name randomly
   MENU -> Allows the user to chose from a menu of avaliable names
   SILENT -> Does not display which process name has been set
   STATUS -> Displays information about which files have been read
              and how the name was chosen
   If no options are specified which affects how the name is chosen and
    there is an unrecognized name as the first argument then that is set
    as the process name

  By default the program searches for the file pnames.dat anywhere in the
   users account. Also defining process_data in your login.com will cause
   that file to be read. For example:

$   WRITE SYS$OUTPUT " $ process_data == " -
     + "user:[wjh3957.programs]process_names.data"
$   EXIT
$ ENDIF
$!
$  saved_dir = f$environment("DEFAULT")
$  SET DEFAULT SYS$LOGIN
$  ON error THEN goto ERRORMESSAGE
$  esc[0,8] = 27
$!
$ normal_file = "pnames.dat"
$ colwidth = 25
$ maxcols = 3
$!
$ argument = "STATUS"
$ gosub ARGUMENT_CHECK
$ argument = "SILENT"
$ gosub ARGUMENT_CHECK
$ argument = "MENU"
$ gosub ARGUMENT_CHECK
$ argument = "RANDOM"
$ gosub ARGUMENT_CHECK
$!
$ IF (MENU .eqs. "TRUE") .or. (RANDOM .eqs. "TRUE") -
   .or. (p1 .eqs. "STATUS") .or (p1 .eqs. "SILENT")
$ THEN
$   GOTO READ_NAMES
$ ENDIF
$!
$ IF  p1 .nes. ""
$ THEN
$   procname = p1
$ ELSE
$   OPEN fin SYS$COMMAND:
$   write sys$output "              123456789012345"
$   read/prompt=" New Process> " fin procname
$ ENDIF
$ IF f$length(procname) .gt. 15
$ THEN
$   nextline = procname
$   GOSUB double_quotes
$   WRITE SYS$OUTPUT "  Error: ""''nextline'"" must be <= 15 characters"
$ ELSE
$   IF f$length(procname) .gt. 0
$   THEN
$     GOTO SET_NAME
$   ENDIF
$ ENDIF
$!  
$ READ_NAMES:
$!
$ max = 0
$ filename = f$search("[...]" + normal_file)
$ IF filename .nes. ""
$ THEN
$   GOSUB READ_FILE
$ ELSE
$   IF STATUS .eqs. "TRUE"
$   THEN
$      WRITE SYS$OUTPUT "  Error: " + normal_file + " not found."
$   ENDIF
$ ENDIF
$!
$ IF f$type(process_data) .nes. ""
$ THEN
$   filename = f$search(process_data)
$   IF filename .nes. ""
$   THEN
$     GOSUB READ_FILE
$   ELSE
$     IF STATUS .eqs. "TRUE"
$     THEN
$        WRITE SYS$OUTPUT "  Error: " + process_data + " not found."
$     ENDIF
$   ENDIF
$ ENDIF
$!
$ IF max .le. 0
$ THEN
$   WRITE SYS$OUTPUT "  Error: No process names read in. Exiting..."
$   GOTO END
$ ENDIF
$!
$ num = 0
$ IF MENU .eqs. "TRUE"
$ THEN
$   GOSUB PRINT_MENU
$   GOSUB GET_NUMBER
$ ENDIF
$!
$ IF num .eq. 0
$ THEN
$   GOSUB SET_RANDOM_NUMBER
$ ENDIF
$!
$ procname = name_'num'
$!
$ SET_NAME:
$ IF SILENT .eqs. "FALSE"
$ THEN
$   WRITE SYS$OUTPUT "  " + esc + "[1mCurrent Process Name" + esc + "[0m: " -
     + procname
$ ENDIF
$ nextline = procname
$ GOSUB DOUBLE_QUOTES
$ procname = nextline
$ set process/name="''procname'"
$!
$ END:
$ SET DEFAULT 'saved_dir
$ EXIT
$!
$ ERRORMESSAGE:                    
$ WRITE SYS$OUTPUT "  Error: Could not set process name."
$ GOTO END
$!
$!
$!**************************************************
$!** Subroutine for reading the next line of data **
$!**************************************************
$!
$ GET_NEXTLINE:
$ READ/END_OF_FILE=EndOFile data nextline
$!
$ IF nextline .eqs. ""
$ THEN
$    GOTO GET_NEXTLINE
$ ELSE
$    RETURN
$ ENDIF
$!
$ EndOFile:
$ nextline = ""
$ RETURN
$!
$!***********************************
$!** Subroutine for reading a file **
$!***********************************
$!
$ READ_FILE:
$ IF status .eqs. "TRUE"
$ THEN
$    WRITE SYS$OUTPUT "  Definition file: " + filename
$ ENDIF
$!
$ OPEN data 'filename
$ READ_FILE_LOOP:
$!
$ gosub GET_NEXTLINE
$ IF nextline .eqs. "" THEN goto BREAK_READ_FILE
$ IF f$length(nextline) .gt. 15
$ THEN
$   IF status .eqs. "TRUE"
$   THEN
$     GOSUB double_quotes
$     WRITE SYS$OUTPUT "  Warning: ""''nextline'"" is too long; name not added"
$   ENDIF
$ ELSE
$   max = max + 1
$   name_'max = nextline
$ ENDIF
$ GOTO READ_FILE_LOOP
$ BREAK_READ_FILE:
$ CLOSE data
$ RETURN
$!
$!****************************************************
$!** Subroutine for checking command line arguments **
$!****************************************************
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
$ IF p'count' .eqs. argument
$ THEN
$     'argument = "TRUE"
$     RETURN
$ ENDIF
$ GOTO CHECK_LOOP
$!
$!********************************************
$!** Subrouting for setting a random number **
$!********************************************
$!
$ SET_RANDOM_NUMBER:
$ seed = f$extract(20, 2, f$cvtime())
$ num = (seed - (seed / max) * max) + 1
$ IF status .eqs. "TRUE"
$ THEN
$   WRITE SYS$OUTPUT "  Randomly chosing process #" + f$string(num) -
     + ": " + name_'num
$ ENDIF
$ RETURN
$!
$!*****************************************************
$!** Subrouting for printing a menu of process names **
$!*****************************************************
$!
$ PRINT_MENU:
$ count = 1
$ col = 1
$ WRITE SYS$OUTPUT ""
$!
$ MENU_LOOP:
$ IF count .gt. max THEN RETURN
$ over_length = (col - 1) * colwidth
$ IF col .eq. 0
$ THEN
$   write sys$output esc + "[A" + f$string(count) + ": " + name_'count
$ ELSE
$   write sys$output esc + "[" + f$string(over_length) + "C" -
                   + esc + "[A#" -
                   + f$string(count) + ": " + name_'count
$ ENDIF
$ count = count + 1
$ IF col .ge. maxcols
$ THEN
$   write sys$output ""
$   col = 1
$ ELSE
$   col = col + 1
$ ENDIF
$ GOTO MENU_LOOP
$!
$!*************************************
$!** Subrouting for reading a number **
$!*************************************
$!
$ GET_NUMBER:
$ inquire num "Process #? "
$ IF num .eqs. ""
$ THEN
$   GOSUB SET_RANDOM_NUMBER
$ ENDIF
$ IF num .gt. max .or num .le. 0
$ THEN
$   write sys$output "  Error: " + num + " is not a valid selection"
$   num = 0
$ ENDIF 
$ RETURN
$!
$!********************************************************
$!** Subrouting for doubling all the quotes in a string **
$!********************************************************
$!
$ DOUBLE_QUOTES:
$ newline = ""
$ DOUBLE_QUOTES_LOOP:
$ quote_offset = f$locate("""", nextline)
$ IF quote_offset .ne. f$length(nextline)
$ THEN
$   newline = newline + f$extract(0, quote_offset, nextline)
$   newline = newline + """"""
    nextline = f$extract(quote_offset + 1, -
                f$length(nextline) - quote_offset - 1, nextline)
$   GOTO DOUBLE_QUOTES_LOOP
$ ELSE
$   newline = newline + nextline
$ ENDIF
$!
$ nextline = newline
$ RETURN
