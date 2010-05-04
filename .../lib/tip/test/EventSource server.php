<?php
header("Content-Type:text/event-stream");

echo "event: server-time\n";
$time = date( 'Y/m/d @ H:i:s.u' );
echo "data: $time\n\n";

echo "event: count\n";
sprintf( "data: %s \n\n", rand() );
