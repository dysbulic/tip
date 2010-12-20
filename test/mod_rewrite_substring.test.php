<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Test of Forwarding Substring URLs with Mod_Rewrite</title>
    <link type="text/css" rel="stylesheet" href="javascript_test.css" />
    <style type="text/css">
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Forwarding URL's with Mod_Rewrite</h1>

    <p>In the .htaccess for this directory, there are the following rules:</p>

    <pre>RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^mod_rewrite_substring http://google.com [R,L]</pre>

    <p>If a file doesn't exist and it begins with <code>mod_rewrite_substring</code>, then redirect to Google. This works as you would expect with <a href="mod_rewrite_substring.dne">mod_rewrite_substring.dne</a>. It forwards to Google. With <a href="mod_rewrite_substring.test">mod_rewrite_substring.test</a> however, it opens this page, <?php $name = basename($_SERVER['SCRIPT_NAME']); printf('<a href="%s">%s</a>', $name, $name) ?>, even though the file extension is missing.</p>

    <p>I have a <a href="../templ">project</a> where I am trying to forward URLs where a file with a name that is a superstring exists and this behavior is keeping that from working.</p>

    <p>It only does this on my server at home, a Fedora Core box running stock Apache 2.2.3. Under OSX it works as expected, as does it on <a href="http://dreamhost.com">Dreamhost</a>.</p>

    <p>This machine is running: <?php print $_SERVER['SERVER_SOFTWARE'] ?>.</p>

    <p>If you think you know the solution and would like to test a solution, you can download <?php printf('<a href="%s">this script</a>', $name . 's') ?>. I'm pretty sure it is a Fedora bug and I've filed a <a href="https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=230953">report</a> since the behavior doesn't exist on any other distributions I have access to.</p>
  </body>
</html>
