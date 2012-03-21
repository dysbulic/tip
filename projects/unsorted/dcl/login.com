$ IF f$mode() .nes. "INTERACTIVE" THEN EXIT
$ VERIFY = 'f$verify(0)'
$ IF F$MODE() .EQS. "OTHER" THEN EXIT
$ SET NOON
$!
$ username = f$edit(f$getjpi("","USERNAME"),"COLLAPSE")
$ saved_message_status = f$environment("MESSAGE")
$ program_location = "user:[WJH3957.programs]"
$ IF f$search(program_location + "*.*") .eqs. "" -
     THEN program_location = "SYS$LOGIN:"
$!
$ E[0,8] = 27
$!
$ WRITE SYS$OUTPUT "''e'[44;33m*************************************''e'[m"
$ WRITE SYS$OUTPUT "''e'[44;33m*''e'[40;31m  This system runs on DEN-OS v1.1  ''e'[44;33m*''e'[m"
$ WRITE SYS$OUTPUT "''e'[44;33m*************************************''e'[m"
$ WRITE SYS$OUTPUT "" 
$ IF p1 .eqs. "SKIP"
$   THEN
$     WRITE SYS$OUTPUT "         ''e'[44;33m***************''e'[m"
$     WRITE SYS$OUTPUT "         ''e'[44;33m*''e'[40;31m HELLO AGAIN ''e'[44;33m*''e'[m"
$     WRITE SYS$OUTPUT "         ''e'[44;33m***************''e'[m"
$ ENDIF
$ WRITE SYS$OUTPUT "''e'[44;37m''e'[0m"
$!
$ DIR*ECTORY == "DIRECTORY/SIZE=ALL/COLUMNS=2/WIDTH=(FILENAME=26,SIZE=4)"
$ SDIR       == "DIRECTORY/SIZE=ALL/COLUMNS=1/WIDTH=(FILENAME=26,SIZE=3)" -
                 + "/SECURITY"
$ LDIR       == "DIRECTORY/SIZE=ALL/COLUMNS=1/WIDTH=(FILENAME=50,SIZE=6)"
$ CD         == "$TTU_SYSTEM:DOWN/LOG"
$ MD         == "CREATE/DIRECTORY/LOG"
$ MOVE       == "RENAME/LOG"
$ ren        == "RENAME/LOG"
$ COPY       == "COPY/LOG"
$ VT         == "SET TERMINAL/ANSI"
$ VT
$!
$ SETUP SCRATCH
$ SCRATCH == "$TTU_SYSTEM:DOWN/LOG SYS$SCRATCH"
$ HOME    == "$TTU_SYSTEM:DOWN/LOG SYS$LOGIN"
$!
$ hdr == "spawn/nowait/nolog @''program_location'run_helpdesk"
$ hdr
$!
$ LOGIN   == "@SYS$LOGIN:LOGIN.COM"
$ LO*GOUT == "@''program_location'LOGOUT.COM NOLISDELETE"
$ FU*CKIT == "@''program_location'LOGOUT.COM"
$ FIX     == "@USER:[WJH3957]LOGIN.COM SKIP"
$!
$ NAMES_DATA == "USER:[WJH3957.PROGRAMS]NAMES.DATA"
$ GETNAMES   == "@''program_location'GETNAMES.COM"
$ GN         == "@''program_location'GETNAMES.COM verbose"
$!
$ define/nolog ROOMCOUNT_EASY_EXIT TRUE
$!
$ noecho == "set terminal/noecho"
$ echo == "set terminal/echo"
$!
$ PROC*ESS  == "@''program_location'process.com"
$ NPROC*ESS == "@''program_location'process.com random"
$ MPROC*ESS == "@''program_location'process.com menu"
$!
$ CL*EAN   == "@USER:[HONORS.PUBLIC]CLEAN.COM"
$ PACK     == "@''program_location'PACKMAIL.COM COMPRESS"
$ HARDPACK == "@''program_location'PACKMAIL.COM QUOTA"
$ CLEAN    == "@user:[honors.public]clean.com"
$!
$ WH*O    == "@''program_location'WHO.COM"
$ FWH*O   == "@''program_location'WHO.COM FAST"
$ DISWH*O == "@''program_location'DISWHO.COM"
$ GRAPH   == "@''program_location'GRAPH.COM"
$ FIND    == "@''program_location'FIND.COM"
$ PIPE    == "@''program_location'PIPE.COM"
$!
$ TEXTPROCESS  == "$''program_location'TEXT_PROCESS.EXE"
$ TI           == "$''program_location'TIME.EXE"
$ TI24         == "$''program_location'TIME.EXE 24"
$ PITEST       == "$''program_location'PI_TEST.EXE"
$ WT           == "$''program_location'WAITING.EXE"
$ WAITING      == "$''program_location'WAITING.EXE"
$ FLUX         == "$''program_location'flux.exe"
$ SEL          == "$''program_location'flux.exe 344"
$ bday         == "$''program_location'time.exe bday"
$!
$  MAILC*HECK	== "@''program_location'MAILCHECK.COM"
$  DISK 	== "@''program_location'DISK.COM"
$  FUNS*END 	== "@''program_location'SENDING.COM"
$  BOMB   	== "@''program_location'BOMB.COM"
$!
$   GIBB*ERISH ==     "WRITE SYS$OUTPUT ""''e'(0"""
$   WIDEW*RITE ==     "WRITE SYS$OUTPUT ""''e'#6"""
$   NORMALW*RITE ==   "WRITE SYS$OUTPUT ""''e'#5"""
$   NARROWCOL*UMNS == "WRITE SYS$OUTPUT ""''e'[?3h"""
$   WIDECOL*UMNS ==   "WRITE SYS$OUTPUT ""''e'[?3l"""
$   ERS*CREEN ==      "WRITE SYS$OUTPUT ""''e'[1J"""
$   EES ==            "WRITE SYS$OUTPUT ""''e'#8"""
$   FLASHA*BIT ==     "WRITE SYS$OUTPUT ""''e'[?5h''e'[?5l''e'[?5h''e'[?5l"-
                                         + "''e'[?5h''e'[?5l''e'[?5h''e'[?5l"-
                                         + "''e'[?5h''e'[?5l''e'[?5h''e'[?5l"-
                                         + "''e'[?5h''e'[?5l''e'[?5h''e'[?5l"""
$   RESET ==          "WRITE SYS$OUTPUT ""''e'[0m''e'[25h''e'[?5l''e'[?3h"-
                                         + "''e'[?3l''e'[0r''e'[2l''e'[!p"-
                                         + "''e'[m''e'(B"""
$!
$  SSEC*URITY    == "SHOW SECURITY"
$  SS*YMBOL      == "SHOW SYMBOL"
$  SLOG*CAL      == "SHOW LOGICAL"
$  SASS*IGNMENT  == "SHOW TRANSLATION"
$  TIME          == "SHOW TIME"
$  QU*OTA        == "SHOW QUOTA"
$  CHECK         == "$TTU_SYSTEM:FINGER/SUB/NOPROC/NOPERS/IM" +-
                    "/NOPLAN/NOLOC/idle/cpu/pid/sta/siz"
$  LOOK          == "$TTU_SYSTEM:FINGER/SUB/PROC/PERS/NOTTT/NOTERM/NOLOG/NOPLAN"
$  F*INGER       == "$TTU_SYSTEM:FINGER/PERS/NOTERM/NOTTT/LOC/NOPROC/CPU"
$  P*EEK         == "$TTU_SYSTEM:FINGER/SUB/PERS/NOTERM/NOTTT/NOLOC/NOPLAN/PROC/CPU"
$  MFI*NGER      == "MULTINET FINGER"
$  PING          == "MULTINET PING"
$  TRACE         == "MULTINET TRACE"
$  NSLOOKUP      == "MULTINET NSLOOKUP"
$  MSC*ONNECTION == "MULTINET SHOW /CONNECTIONS=PROC"
$  PRINT         == "PRINT/QUE=AVAX_LPA0/NOTIFY/EXCLUDE=(*.EXE,*.OBJ)"
$  DEL           == "DELETE/CONFIRM"
$  ALLMESS*AGE   == "SET MESSAGE/DELETE/FACILITY/IDENTIFICATION/SEVERITY/TEXT"
$  RESETMESS     == "SET MESSAGE/DELETE/FACILITY/IDENTIFICATION/SEVERITY/TEXT"
$  NOMESS*AGE    == "SET MESSAGE/NODELETE/NOFACILITY/NOIDENTIFICATION/NOSEVERITY/NOTEXT"
$  TOP           == "MONITOR PROCESS/TOPCPU/INTERVAL=1"
$  ED*IT         == "EDIT/TPU"
$!
$  PHONE       == "PHONE"
$  PA*NSWER    == "PHONE ANSWER"
$  PR*JECT     == "PHONE REJECT"
$  PD*IRECTORY == "PHONE DIRECTORY"
$!
$ M*AIL :== MAIL/EDIT=(SEND,REPLY=EXTRACT,FORWARD)
$ DEFINE/NOLOG EVE$INIT USER:[WJH3957.ETC]EVE$INIT.EVE
$!
$ DEFINE/NOLOG NEWSRC USER:[WJH3957.ETC]newsrc.
$ DEFINE/NOLOG NEWS_EDIT CALLABLE_TPU
$!
$ GAG*GER   == "SET BROADCAST=NOUSER1"
$ UNGAG*GER == "SET BROADCAST=ALL"
$!
$ IF username .eqs. "WJH3957"
$ THEN
$    SET PROMPT="''e'[1mBi?''e'[0m"
$!
$    BBS_ALIAS == "Dancing in the Rain at Night"
$    BBS_EDIT == "edit/tpu"
$!
$    DEFINE/NOLOG IRCNAME SAGE
$ ENDIF
$!
$  ATLAS       == "telnet atlas.tntech.edu"
$  C*ONNECT    == "telnet GEMINI.TNTECH.EDU"
$!
$ IF f$edit(p1, "UPCASE, TRIM") .nes. "SKIP"
$ THEN
$   BDAY
$   WRITE SYS$OUTPUT ""
$   GETNAMES nonotification
$   PROCESS RANDOM
$ ENDIF
$!
$!***************************************************
$!*** Programs Accessed in Other Peoples Accounts ***
$!***************************************************
$!  gnwh*o ==    "@user:[sac9240.public]who.com"
$  gnwh*o ==    "@user:[rwv2961.public]who.com"
$  lol*ong ==   "@user:[rwv2961.public]logout.com"
$  loc*k ==     "@user:[rwv2961.public]lock.com"
$!  find ==      "@user:[rwv2961.public]find.com"
$  beep ==      "@user:[rwv2961.public]beep.com"
$  marq*uee ==  "@user:[rwv2961.public]marquee.com"
$  que*stion == "@user:[rwv2961.public]question.com"
$  quote ==     "@user:[rwv2961.public]quote.com"
$  text ==      "run user:[rwv2961.public]text.exe"
$  watch ==     "@user:[rwv2961.public]watch.com"
$  thes*hit ==  "@user:[cec9501]dreaming.com"
$  chef ==	"run user:[mwr3116.public]vaxchef.exe"
$  zmo*dem ==	"@user:[mwr3116.public]sz.com"
$  SET MESSAGE /NOFACILITY/NOSEVERITY/NOIDENTIFICATION/NOTEXT
$  @user:[honors.public]honors.com
$  SET MESSAGE 'saved_message_status
$  cdsys == "set def SYS$COMMON:[000000]"
$!
$!**************************************
$!***** DEN-OS v1.0  edit commands *****
$!**************************************
$ elog*in      == "edit/tpu sys$login:login.com"
$ emailc*heck  == "edit/tpu ''program_location'mailcheck.com"
$ ewho         == "edit/tpu ''program_location'who.com"
$ enames       == "edit/tpu " + f$search("[...]names.dat")
$ enames       ==  f$extract(0, f$locate(";", enames), enames)
$ eproc        == "edit/tpu " + f$search("[...]pnames.dat")
$ eproc        ==  f$extract(0, f$locate(";", eproc), eproc)
$ epage        == "edit/tpu user:[''username'.www]index.html"
$ eplan        == "edit/tpu sys$login:finger.pln"
$ esig*nature  == "edit/tpu sys$login:signature.txt"
$ epoint       == "edit/tpu sys$login:appoint.ments"
$ rb*oot       == "@sys$login:login.com"
$!
$!**************************************
$!***** DEN-OS v1.0  lynx commands *****
$!**************************************
$ page          == "$ttu_system:lynx http://gemini.tntech.edu/~''username'"
$!
$!*****************************************************************
$!***** DEN-OS v1.0  appointment book activation and commands *****
$!*****************************************************************
$ t_point = f$search("[...]appoint.ments")
$ IF t_point .nes. ""
$ THEN
$   t_point = f$extract(0, f$locate(";", t_point), t_point)
$   epoint == "edit/tpu " + t_point
$   appoint*ment  == "TYPE " + t_point
$   ERASE
$   appointment
$   WAIT 00:00:05
$ ENDIF
$!
$!********************
$!*** Helpdesk.com ***
$!********************
$!
$ pclab   == "@user:[acs.pclab]pclab"
$ hdlib   == "help/libr=user:[acs.help]hdhelp.hlb/noinstr/page"
$ trp     == "@user:[acs.termrpt]terminalreport
$ tcodes == "type/p SYS$SYSTEM:SMGTERMS.TXT"
$!
$ @USER:[labmgr.com]helpdesk.com
$ EXIT
