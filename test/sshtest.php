<?php print("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" ?>\n") ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>PHP SSH Test</title>
    <style type="text/css">
      iframe {
        width: 100%;
        height: 20em;
      }
    </style>
    <script type="text/javascript">
    </script>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <form action=".">
      <div>Username: <input type="text" name="username"></input></div>
      <div>Password: <input type="password" name="password"></input></div>
    </form>
    <?php
      if(isset($_GET["username"]) && isset($_GET["password"])) {
      }
    ?>
  </body>
</html>
