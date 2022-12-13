<?php
header("Content-type: text/plain");

echo "uid: " . getmyuid() . "\n";
echo "pid: " . getmypid() . "\n";
echo "inode: " . getmyinode() . "\n";
echo "lastmod: " . getlastmod() . "\n";
?>
