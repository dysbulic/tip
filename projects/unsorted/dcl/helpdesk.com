$!******HELPDESK*****
$ on severe_error then continue
$ set noon
$!
$ setup scratch
$!
$ checkin     == "$user:[labmgr.checkin]checkin"
$ cpu         == "mon proc/topcpu/interval=1"
$ hdd         == "@user:[labmgr.com]define_helpdesk"
$ hdwho       == "@user:[labmgr.helpdesk]hdwho.com"
$ hold        == "@user:[labmgr.helpdesk]idler.com"
$ hds         == "$ttu_system:most user:[labmgr.helpdesk]hdsched.current"
$ las 	      == "$ttu_system:most user:[labmgr.helpdesk]lasched.current"
$ query       == "$user:[acs.roomres]qr"
$ rmct	      == "$user:[acs.roomcount]nrmct.exe"
$ oldrmct     == "$user:[acs.roomcount]roomcount"
$ hd          == "@user:[labmgr.helpdesk]hd.com"
$ nag         == "spawn/nowait/nolog @user:[labmgr.helpdesk]nag.com"
$ oldhdwho    == "@user:[labmgr.helpdesk]oldhdwho.com"
$ oldtrp      == "@user:[acs.termrpt]terminalreport
$ openshifts  == "@user:[labmgr.helpdesk]openshifts.com"
$ trp         == "@user:[labmgr.com]trp_message.com
$ scan	      == "@user:[labmgr.com]scan.com"
$ logphone    == "@user:[labmgr.phone]hlog.com"
$ define/nolog hdmail "@user:[labmgr.helpdesk]helpdesk.dis"
$ define/nolog lamail "@user:[labmgr.helpdesk]labassist.dis"
$!
$! This little bit of code will replace the long series of if
$! statements in the helpdesk.com. If assumes the machine name to be
$! of the format abxyz followed by -hd or -ts where ab is the building
$! abbreviation and xyz is the room number. It checks to see if the
$! machine name contains a -hd or a -ts (the f$length function returns
$! the same not found value for both searches then neither was found)
$! and if either is present the it sets the process name to
$! -=Helpdeskxyz=- and defines helpdesk_lab_set (for the checkin and
$! roomcount programs) as ABXYZ. This will work for all regularly
$! named helpdesk stations and an extra check is added for CH313's odd
$! station. -- WJH
$!
$ serverport = f$getdvi("sys$output","tt_accpornam")
$ if f$locate("-ts", serverport) .ne. f$locate("-hd", serverport)
$ then
$   helpdesk_symbol = f$edit(f$extract(0, 5, serverport), "UPCASE")
$ else
$   delim = f$locate("/", serverport)
$   port_name = f$extract(delim + 1, f$length(serverport) - delim - 1, serverport)
$   if port_name .eqs. "HELPDESK_313"
$   then
$     helpdesk_symbol = "CH313"
$   endif
$ endif
$!
$! If the symbol helpdesk_symbol was assigned in the previous section
$! then it is defined in the logical names table here.
$!
$ if f$type(helpdesk_symbol) .nes. ""
$ then
$!
$!  One of the machines with an ip that reverse maps to CH215 is now
$!  actually in CH313, so that needs to be caught
$!
$   if helpdesk_symbol .eqs. "CH215" then helpdesk_symbol := CH313
$!
$   process_name = "-=Helpdesk" + f$extract(2, 3, helpdesk_symbol) + "=-"
$   define/nolog HELPDESK_LAB_SET "''helpdesk_symbol'"
$ endif
$!
$! If the symbol process_name was defined in the previous section then
$! it a check is done to see if the process name is already in use and if
$! it isn't then that process name is set as the current process name.
$!
$ if f$type(process_name) .nes. ""
$ then
$   ctx = ""
$   pid = f$context("PROCESS", ctx, "PRCNAM", process_name, "EQL")
$   pid = f$pid(ctx)
$   if pid .eqs. "" then set process/name="''process_name'"
$ endif
$!
$! This code looks complicated, but it's simpler than keeping track of
$! all those if's (which are still not balanced) and additionally so long
$! as the new labs that are added have the same naming format as the ones
$! being used now and they don't have a room number that is already in
$! use then this will automatically detect the -ts or -hd in the name and
$! fix the process name appropriately.
$ exit
