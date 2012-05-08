<?php
header("Content-Type:text/event-stream");

$time = date( 'Y/m/d @ H:i:s.u' );
echo "event: server-time\n";
echo "data: $time\n\n";
