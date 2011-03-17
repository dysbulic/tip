<?php
$location = file_get_contents( 'tip:letter.png' );

Header( 'HTTP/1.1 302 Moved Temporarily' );
Header( "Location: $location" );
