<?php
header("Content-type: text/plain");

OpenLog("php3", $LOG_PID + $LOG_PERROR, $LOG_DAEMON);
SysLog($LOG_INFO, "testing php3 syslog");
CloseLog();
?>
