Monotone Version 1.2

Thumb.php and .htaccess Installation:

1) Move htaccess.txt and thumb.php to your site's root folder (the one where xmlrpc.php and the wp-admin folder live).
2) Rename htaccess.txt to .htaccess OR copy the lines out of .htaccess and append them to your current .htaccess file.
3) Make a folder with world writable permissions (probably 0777) called "cache" in your site's root directory. This is so thumb.php can cache the images it generates.
3) Everything should work.