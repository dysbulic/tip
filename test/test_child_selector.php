<?php if($_GET['quirks'] != "on"): $quirks = true; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtmel1-strict.dtd">
<?php endif; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Child Selector Test</title>
    <link rel="stylesheet" type="text/css" href="javascript_test.css" />
    <style type="text/css">
      body > p:first-child { font-size: 150%; color: red; }
      ul { list-style: none; padding: 0; }
      table { border-collapse: collapse; margin-top: 1em; }
      th, td { padding: .25em .5em; border: 1px solid black; text-align: center; }
      table:last-child th, table:last-child td { border: 2px solid red; }
      table:last-of-type th { border-color: green; }
      table + table + table th { border-style: dashed; }
      p ~ p { font-weight: bold; }
      #redtable { background-color: #3E3; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <p><code>body > p:first-child { font-size: 150%; color: red; }</code></p>
    <ul>
      <li><code>table:last-child td { border: 2px solid red; }</code></li>
      <li><code>table:last-of-type th { border-color: green; }</code></li>
      <li><code>table + table + table th { border-style: dashed; }</code></li>
    </ul>
    <table>
      <tr><th>Test #1</th><th>Testing</th></tr>
      <tr><td>Test</td><td>Test</td></tr>
    </table>
    <table>
      <tr><th>Test #2</th><th>Testing</th></tr>
      <tr><td>Test</td><td>Test</td></tr>
    </table>
    <table>
      <tr><th>Test #3</th><th>Testing</th></tr>
      <tr><td>Test</td><td>Test</td></tr>
    </table>
    <p><code>p ~ p { font-weight: bold; }</code></p>
    
    <table id="redtable" bgcolor="red">
      <tr><td>&lt;table bgcolor="red"></td></tr>
      <tr><td>#redtable { background-color: #3E3; }</td></tr>
    </table>

    <p>This document can also be viewed in <?php printf("<a href='%s'>%s</a>", $_SERVER['SCRIPT_NAME'] . (!$quirks ? '' : '?quirks=on'), (!$quirks ? 'standards compliant' : 'quirks') . " mode") ?>. Quirks mode keeps IE7 from processing any of the advanced selectors. This means I can't manage to address a specific table in MySpace's ungodly mess of HTML. How do so many crappy products get so popular? I hate them all so much</p>
  </body>
</html>
