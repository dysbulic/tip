<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Testing PHP DOM Functions</title>
    <style type="text/css">
      table {
        width: 50%;
      }
      input[type='text'] {
        width: 100%;
      }
      td:first-child {
        text-align: right;
      }
    </style>
    <script type="text/javascript">//<![CDATA[
    //]]></script>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <?php if(!function_exists("domxml_open_mem")) { ?>
      <p>DOM PHP function are not defined</p>
    <?php } else if(!isset($_GET['rss'])) { ?>
    <form action="<?php print $_SERVER['SCRIPT_NAME'] ?>">
      <table>
        <tr>
          <td>RSS Feed:</td>
          <td><input type="text" name="rss" /></td>
        </tr>
        <tr>
          <td>XPath:</td>
          <td><input type="text" name="xpath" /></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="Go!" /></td>
        </tr>
      </table>
    </form>
    <?php } else {
      $xpath = (isset($_GET['xpath']) && $_GET['xpath'] != "") ? $_GET['xpath'] : "count(*)";
      $file = file_get_contents($_GET['rss']);
      if($dom_doc = domxml_open_mem($file)) {
        $xpathCon = $dom_doc->xpath_new_context();
        $namespace = $xpathCon->xpath_eval("namespace-uri(//*)");
        print "<p>Namespace: " . $namespace->value . "</p>\n";
        //xpath_register_ns($xpath, "rss", $namespace);
        $path_val = $xpathCon->xpath_eval($xpath);
        print_r($path_val->nodeset);
      } else {
        print "<p>Failed to open: " . $file . "</p>\n";
      }
    } ?>
  </body>
</html>
