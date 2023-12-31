<?php
header("Content-type: text/plain");

echo "does bogus exist? " . fileAtime("bogus") . "\n";
$myself = $_SERVER["SCRIPT_FILENAME"];
echo "how about myself? ($myself): " . fileAtime($myself) . "\n";
echo "atime: " . fileAtime($myself) . "\n";
echo "ctime: " . fileCtime($myself) . "\n";
echo "group: " . fileGroup($myself) . "\n";
echo "inode: " . fileInode($myself) . "\n";
echo "mtime: " . fileMtime($myself) . "\n";
echo "owner: " . fileOwner($myself) . "\n";
echo "perms: " . filePerms($myself) . "\n";
echo " size: " .  fileSize($myself) . "\n";
echo " type: " .  fileType($myself) . "\n";

$statdata = stat($myself);
echo "  dev: " . $statdata[0] . "\n";
echo "inode: " . $statdata[1] . "\n";
echo " mode: " . $statdata[2] . "\n";
echo "nlink: " . $statdata[3] . "\n";
echo "  uid: " . $statdata[4] . "\n";
echo "  gid: " . $statdata[5] . "\n";
echo " rdev: " . $statdata[6] . "\n";
echo " size: " . $statdata[7] . "\n";
echo "atime: " . $statdata[8] . "\n";
echo "mtime: " . $statdata[9] . "\n";
echo "ctime: " . $statdata[10] . "\n";
echo "blksize: " . $statdata[11] . "\n";
echo "blocks: " . $statdata[12] . "\n";

echo "type of functions: " . fileType("functions") . "\n";
?>
