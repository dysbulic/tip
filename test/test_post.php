<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Post Test</title>
    <link rel="stylesheet" type="text/css" href="/styles/main.css" />
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Post Test</h1>
    <p>This is a test of how a form was submitted:</p>
    <pre>
      <?php print_r(array_keys($GLOBALS)) ?>
    </pre>

    <form method="post">
      <table>
        <tr>
          <th>Word</th>
          <th>Part of Speech</th>
          <th>Definition</th>
        </tr>
        <tr>
          <td colspan="3" class="button-row">
            <input type="reset" />
            <input type="submit" name="action" value="Update Dictionary" />
          </td>
        </tr>
      </table>
    </form>
    <form method="post">
      <table>
        <tr>
          <td>Enter new part of speech:</td>
          <td><input type="text" name="part_of_speech" /></td>
        </tr>
	<tr>
          <td colspan="2" class="button-row">
            <input type="reset" />
            <input type="submit" name="action" value="Add" />
          </td>
        </tr>
      </table>
    </form>        
    <form method="post">
      <table>
	<tr>
          <td class="button-row">
            <input type="submit" name="action" value="Restart" />
          </td>
        </tr>
      </table>
    </form>
  </body>
</html>
