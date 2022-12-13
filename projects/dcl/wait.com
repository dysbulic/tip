$ set symbol/scope=noglobal
$ set on
$ on warning then goto error
$ on error then goto error
$ on severe_error then goto error
$ on control_y then goto end
$!
$ username = f$edit(f$getjpi("","USERNAME"), "TRIM,LOWERCASE")
$!
$ if interactive .eqs. "FALSE"
$ then
$   procname = f$extract(0, 15, username + " waiting")
$   ctx = ""
$   pid = f$context("PROCESS", ctx, "PRCNAM", procname, "EQL")
$   pid = f$pid(ctx)
$   if pid .nes. ""
$   then
$     write sys$output " Helpdesk reminder already running (pid = " + -
       pid + "); program exiting"
$     goto end
$   endif
$!
$   set process/name="''procname'"
$ endif
$!
$ begin:
$ checkin_flag := false
$ roomcount_flag := false
$ current_time = f$extract(14, 2, f$cvtime())
$ current_time = f$integer(current_time)
$!
$ set_delta_time:
$ if current_time .lt. 15
$ then
$   delta_time = 15 - current_time
$   checkin_flag := true
$ else
$   if current_time .lt. 30
$   then
$     delta_time = 30 - current_time
$     roomcount_flag := true
$   else
$     if current_time .lt. 45
$     then
$       delta_time = 45 - current_time
$       checkin_flag := true
$     else
$       delta_time = 60 - current_time
$       delta_time = delta_time + 15
$       checkin_flag := true
$     endif
$   endif
$ endif
$ delta_time = f$string(delta_time)
$!
$ set_message:
$ if checkin_flag .eqs. "TRUE"
$ then
$   message = "It's time to run checkin"
$ else
$   if roomcount_flag .eqs. "TRUE"
$   then
$     message = "It's time to run roomcount"
$   else
$     message = "Program error; do something; I don't know what"
$   endif
$ endif
$!
$ if delta_time .ne. 1
$ then
$   sses = "s"
$ else
$   sses = ""
$ endif
$!
$ if interactive .eqs. "TRUE" then write sys$output -
   "From " + f$extract(11, 8, f$cvtime()) + -
   " waiting " + delta_time + " minute''sses' to send " + username + -
   " ""''message'"""
$!
$ gosub set_length
$ wait 00:'delta_time'
$ send 'username "''message'"
$!
$ run_program:
$ if interactive .eqs. "FALSE" then goto begin
$ if checkin_flag .eqs. "TRUE" then spawn checkin
$ if roomcount_flag .eqs. "TRUE" then spawn roomcount
$ goto begin
$!
$ error:
$ write sys$output "Exiting on error:"
$ end:
$ set symbol/scope=global
$ exit
$!
$ set_length:
$ if f$length(delta_time) .lt. 2
$ then
$   delta_time = "0" + f$string(delta_time)
$   goto set_length
$ else
$   return
$ endif
