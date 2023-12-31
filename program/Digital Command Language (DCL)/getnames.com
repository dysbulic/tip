$! GETNAMES.COM:
$!    Originally attributed to Robert Hood.
$!    Modernization by Bill Langston and Mike Renfro,
$!    with greatful acknowledgement to Mike Wheeler  (Fall 1990).
$! 30-Aug-93 Revision and cleanup by Bill Langston
$!    Function: Read file named "names.dat" and form logicals and symbols
$!    from it.  Revised to form logicals and symbols from a central default
$!    file before using SYS$LOGIN:NAMES.DAT --wfl
$! 01-Sep-99 dysbulic: E-mail addresss need not be quoted; distribution
$!    lists not defined as symbols; searches directory tree for names.dat;
$! 05-Sep-99 dysbulic: All kinds of command line options added and
$!    optimizations. Run "who help" for a list of the options they are too
$!    extensive to list here. See the inline comments for more internal
$!    information
$!
$ max_symbol_length = 350
$ default_file = "USER:[HONORS.PUBLIC]NAMES_DEFAULT.DAT"
$ example_file = "USER:[HONORS.PUBLIC]EXAMPLE_NAMES.DAT"
$ normal_file = "NAMES.DAT"
$ on warning THEN GOTO ERROR_MESSAGE
$ on control_y THEN GOTO ERROR_MESSAGE
$!
$ esc[0,8] = 27
$ bold_on = esc + "[1m"
$ all_off = esc + "[0m"
$!
$!***********************
$!** Begin the program **
$!***********************
$!
$ argument = "HELP"
$ gosub ARGUMENT_CHECK
$ IF help .eqs. "TRUE"
$ THEN
$    TYPE SYS$INPUT:

 [silent]         - Do not display any output
 [verbose]        - List all definitions
 [nonotification] - Do not present status messages
 [nostatistics]   - Do not display final statistics
 [nogetfile]      - Do not prompt to copy a names.dat if none is present
 [nodefault]      - Do not use the default honors definitions
 [nolocal]        - Do not use a local names.dat
 [nowho]          - Do not define names for who.com
 [filename.ext]   - Read filename.ext for data 

Getnames.com -- Defines symbols for use with mail and other VMS programs.
 This programs uses the file names.dat; for an example names.dat see
 user:[honors.public]example_names.dat

Uses:
 send DEFINED_NAME
 talk DEFINED_NAME
 mail/send DEFINED_NAME
 finger 'DEFINED_NAME
 phone DEFINED_NAME

$    EXIT
$ ENDIF
$!
$!***********************
$!** Begin the program **
$!***********************
$!
$ START:
$ saved_directory = f$environment("DEFAULT")
$ SET DEFAULT SYS$LOGIN
$ file_count = 0
$ internet_addresses = 0
$ local_addresses = 0
$ distribution_lists = 0
$ line_count = 1
$!
$ argument = "SILENT"
$ gosub ARGUMENT_CHECK
$ argument = "NONOTIFICATION"
$ gosub ARGUMENT_CHECK
$ argument = "VERBOSE"
$ gosub ARGUMENT_CHECK
$ argument = "NOWHO"
$ gosub ARGUMENT_CHECK
$!
$ IF nowho .eqs. "FALSE"
$ THEN
$    gosub CLEAR_SYMBOLS
$ ENDIF
$!
$!**************************************
$!** Reads the file NAMES_DEFAULT.DAT **
$!**************************************
$!
$ argument = "NODEFAULT"
$ gosub ARGUMENT_CHECK
$ names_dat = f$search(default_file)
$ IF nodefault .eqs. "FALSE" .and. names_dat .nes. ""
$ THEN
$    gosub READ_FILE
$ ENDIF
$!
$!**********************************************************************
$!** Searches to see if a NAMES.DAT is present in the login directory **
$!**  or a subdirectory thereof and reads it if necessary.            **
$!**********************************************************************
$!
$ argument = "NOLOCAL"
$ gosub ARGUMENT_CHECK
$ IF nolocal .eqs. "FALSE"
$ THEN
$    names_dat = f$search("[...]" + normal_file)
$    IF names_dat .eqs. ""
$    THEN
$       argument = "NOGETFILE"
$       gosub ARGUMENT_CHECK
$       IF nogetfile .eqs. "FALSE"
$       THEN
$          names_dat = "names.dat"
$          gosub NOFILE
$       ENDIF
$    ELSE
$       gosub READ_FILE
$    ENDIF
$ ENDIF
$!
$!******************************************************************
$!** Checks for the global variable names_data and reads the file **
$!******************************************************************
$!
$ argument = "NOGLOBAL"
$ gosub ARGUMENT_CHECK
$ IF noglobal .eqs. "FALSE" .and. f$type(names_data) .nes. ""
$ THEN
$    names_dat = f$search(names_data)
$    IF names_dat .nes. ""
$    THEN
$       gosub READ_FILE
$    ENDIF
$ ENDIF
$!
$!*********************************************************
$!** Checks for files in the command line and reads them **
$!*********************************************************
$!
$ count = 0
$ CHECK_FOR_FILE_ARGUMENTS:
$ count = count + 1
$ names_dat = f$search(p'count')
$ IF names_dat .nes. ""
$ THEN
$    gosub READ_FILE
$ ENDIF
$ IF p'count' .nes "" THEN GOTO CHECK_FOR_FILE_ARGUMENTS
$!
$!**************************
$!** Beginning of the end **
$!**************************
$!
$ END:
$ SET DEFAULT 'saved_directory
$!
$!****************************
$!** Display the statistics **
$!****************************
$!
$ argument = "NOSTATISTICS"
$ gosub ARGUMENT_CHECK
$ IF silent .eqs. "FALSE" .and. nostatistics .eqs. "FALSE"
$ THEN
$    user_count = internet_addresses + local_addresses + distribution_lists
$    IF user_count .eq. 1
$    THEN
$       user_word = "user"
$    ELSE
$       user_word = "users"
$    ENDIF
$    IF file_count .eq. 1
$    THEN
$       file_word = "file"
$    ELSE
$       file_word = "files"
$    ENDIF
$       WRITE SYS$OUTPUT "  ''bold_on'Getnames:''all_off' " + -
                          f$string(user_count) + " " + -
                          user_word + " defined, from " + -
                          f$string(file_count) + " " + file_word + "." 
$ ENDIF
$!
$ IF nowho .eqs. "FALSE"
$ THEN
$    gosub GLOBALIZE_VARIABLES
$ ENDIF
$!
$ EXIT
$!
$!********************
$!** Error Handling **
$!********************
$!
$ ERROR_MESSAGE:
$ WRITE SYS$OUTPUT "  GetNames terminated prematurely -- " + names_dat + -
                   " closed."
$ CLOSE data
$ GOTO END
$!
$ NOFILE:
$ WRITE SYS$OUTPUT "  Getnames error: File " + names_dat + " not found"
$ IF f$search("sys$login:" + normal_file) .eqs. ""
$ THEN
$    INQUIRE/NOPUNCTUATION response "  Would you like to get a ''normal_file'? [N] "
$    IF response .eqs. "y" .or. response .eqs. "Y"
$    THEN
$       COPY/LOG 'example_file' 'normal_file'
$       WRITE SYS$OUTPUT "  To add names edit the file: " + f$search(normal_file)
$    ENDIF
$ ENDIF
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
$ IF p'count' .eqs. f$extract(0, f$length(p'count'), "''argument'")
$ THEN
$     'argument = "TRUE"
$     RETURN
$ ENDIF
$ GOTO CHECK_LOOP
$!
$!**************************************************
$!** Subroutine for reading the next line of data **
$!**************************************************
$!
$ GET_NEXTLINE:
$ READ/END_OF_FILE=EndOFile data nextline
$ nextline = f$edit(nextline, "UNCOMMENT, COLLAPSE")
$!
$ REMOVE_QUOTES_LOOP:
$ quote_offset = f$locate("""", nextline)
$ IF quote_offset .ne. f$length(nextline)
$ THEN
$    nextline = f$extract(0, quote_offset, nextline) + -
                f$extract(quote_offset + 1, -
                 f$length(nextline) - quote_offset - 1, nextline)
$    GOTO REMOVE_QUOTES_LOOP
$ ENDIF
$!
$ IF f$locate("IN%", f$edit(nextline, "UPCASE")) .eq. 0
$ THEN
$    nextline = f$extract(3, f$length(nextline) - 3, nextline)
$ ENDIF
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
$ file_count = file_count + 1
$ IF silent .eqs. "FALSE" .and. nonotification .eqs. "FALSE"
$ THEN
$    WRITE SYS$OUTPUT "  Definition file: " + names_dat
$ ENDIF
$!
$ OPEN data 'names_dat
$ USER_NAMES_DATA_LOOP:
$!
$ gosub GET_NEXTLINE
$ name = nextline
$ IF nextline .eqs. "" THEN goto BREAK_USER_LOOP
$ gosub GET_NEXTLINE
$ symbol = nextline
$ IF nextline .eqs. "" THEN goto BREAK_USER_LOOP
$!
$! *********************************************************
$! * There are now several cases to be handled:            *
$! * Case: Contains an @                                   *
$! *  Either an off campus internet address (non-0 offset) *
$! *  Or a distribution list (0 offset)                    *
$! *********************************************************
$!
$ IF f$locate("@", symbol) .ne. f$length(symbol) 
$ THEN
$    IF f$locate("@", symbol) .eq. 0
$    THEN
$       distribution_lists = distribution_lists + 1
$       logical = """" + symbol + """"
$       symbol = ""
$    ELSE
$       internet_addresses = internet_addresses + 1
$       logical = """IN%""""''symbol'"""""""
$    ENDIF
$ ELSE
$!
$! **********************************************
$! * Case: Contains a :: (local node address)   *
$! *  Either a gemini address (TTU at 0 offset) *
$! *  Or another node                           *
$! **********************************************
$!
$    IF(f$locate("::", symbol) .ne. f$length(symbol))
$    THEN
$       IF(f$locate("TTU::",f$edit(symbol, "UPCASE")) .eq. 0)
$       THEN
$          local_addresses = local_addresses + 1
$          logical = symbol
$          symbol = f$extract(5, f$length(symbol) - 5, symbol)
$          IF nowho .eqs. "FALSE"
$          THEN
$             gosub ADD_SYMBOL
$          ENDIF
$       ELSE
$          internet_addresses = internet_addresses + 1
$          logical = symbol
$       ENDIF
$    ELSE
$!
$! ******************************************
$! * Case: Contains a , (considered to be a *
$! *  collection of other definitions) This *
$! *  is treated like a distribution list.  *
$! ******************************************
$!
$       IF(f$locate(",", symbol) .ne. f$length(symbol))
$       THEN
$          distribution_lists = distribution_lists + 1
$          logical = symbol
$          symbol = ""
$       ELSE
$!
$! ********************************
$! * Case: Everything else        *
$! *  Considered a gemini address *
$! ********************************
$!
$          local_addresses = local_addresses + 1
$          logical = symbol
$          IF nowho .eqs. "FALSE"
$          THEN
$             gosub ADD_SYMBOL
$          ENDIF
$       ENDIF
$    ENDIF
$ ENDIF
$!
$ IF symbol .nes. ""
$ THEN
$    'name == "''symbol'"
$ ENDIF
$!
$ IF logical .nes. ""
$ THEN
$    DEFINE/NOLOG 'name 'logical
$ ENDIF
$!
$ IF silent .eqs. "FALSE" .and. verbose .eqs. "TRUE"
$ THEN
$    WRITE SYS$OUTPUT "  Defined: " + name
$ ENDIF
$!
$ GOTO USER_NAMES_DATA_LOOP
$ BREAK_USER_LOOP:
$ CLOSE data
$ RETURN
$!
$! *********************************************************
$! * Subroutine to add a symbol to the set of search lines *
$! *********************************************************
$!
$ ADD_SYMBOL:
$ IF f$type(search_line_'line_count') .eqs. ""
$ THEN
$    search_line_'line_count' = "''symbol'"
$ ELSE
$    IF f$length(search_line_'line_count') + f$length(symbol) .gt. max_symbol_length
$    THEN
$       line_count = line_count + 1
$       search_line_'line_count' = "''symbol'"
$    ELSE
$       search_line_'line_count' = search_line_'line_count' + ",''symbol'"
$    ENDIF
$ ENDIF
$ RETURN
$!
$! ***********************************************
$! * Subroutine to set local variables as global *
$! ***********************************************
$!
$ GLOBALIZE_VARIABLES:
$!
$ loop_count = 1
$!
$ SET_VARIABLES_AS_GLOBAL_LOOP:
$ IF f$type(search_line_'loop_count') .nes. ""
$ THEN
$    search_line_'loop_count' == search_line_'loop_count'
$    loop_count = loop_count + 1
$    GOTO SET_VARIABLES_AS_GLOBAL_LOOP
$ ENDIF
$!
$ search_local_addresses == local_addresses
$ search_internet_addresses == internet_addresses
$ search_distribution_lists == distribution_lists
$!
$ search_line_count == line_count
$!
$ RETURN
$!
$! *******************************************
$! * Subroutine to clear symbols to be reset *
$! *******************************************
$!
$ CLEAR_SYMBOLS:
$!
$ loop_count = 1
$!
$ DELETE_SYMBOLS_LOOP:
$ IF f$type(search_line_'loop_count') .nes. ""
$ THEN
$    DELETE/SYMBOL/GLOBAL search_line_'loop_count'
$    loop_count = loop_count + 1
$    GOTO DELETE_SYMBOLS_LOOP
$ ENDIF
$!
$ RETURN
