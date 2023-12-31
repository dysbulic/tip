<html>
  <head>
    <title>Welcome to Expostnihilo</title>
    <link rel="stylesheet" type="text/css" href="/styles/paper.css" />
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <p>Good 
      <?php
        $hour = date("G");
        if($hour >= 5 && $hour < 12) {
	  print("morning,");
 	} elseif($hour >= 12 && $hour < 16) {
	  print("afternoon,");
	} else {
	  print("evening,");
	}
      ?>
      Welcome to <?php print($_SERVER["SERVER_NAME"]); ?>
    </p>
  </body>
</html>
