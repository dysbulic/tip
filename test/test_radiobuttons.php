<html>
  <head>
    <title>Radio Button Test</title>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <form>
      <input type="radio" name="radio" value="one">one</input>
      <input type="radio" name="radio" value="two">two</input>
      <input type="radio" name="radio" value="three">three</input>
      <input type="submit" />
    </form>
<?php if(isset($_GET["radio"])) { ?>
    <ol>
      <li>The value of $_GET["radio"] is <?php print $_GET["radio"] ?></li>
      <li><?php print $string="The value of \$_GET['radio'] is '" . $_GET['radio'] . "'"; ?></li>
    </ol>
<?php } ?>
  </body>
</html>
