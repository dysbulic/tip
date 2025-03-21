<?php
header("Content-type: text/plain");

echo "Testing link functions...\n";

SymLink("Makefile", "test.sym");
echo "linkinfo: " . LinkInfo("test.sym") . "\n";
echo "readlink: " . ReadLink("test.sym") . "\n";
Unlink("test.sym");

Link("Makefile", "test.hard");
$statdata = stat("test.hard");
echo "test.hard stat[nlinks]: " . $statdata[3] . "\n";
echo "test.hard stat[   ino]: " . $statdata[1] . "\n";
$statdata = stat("Makefile");
echo " Makefile stat[nlinks]: " . $statdata[3] . "\n";
echo " Makefile stat[   ino]: " . $statdata[1] . "\n";
?>
