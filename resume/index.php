<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Will's R&eacute;sum&eacute;</title>
    <script type="application/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
    </script>
    <script type="application/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.js">
    </script>
    <script type="application/javascript" src="index.js"></script>
    <script type="application/javascript">//<![CDATA[
    //]]></script>
    <link rel="stylesheet" type="text/css"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="resume.css" />
  </head>
  <body>
    <?php if(isset($params['error'])) { ?>
      <div id="error"><?php print $params['error'] ?></div>
    <?php } ?>
    <?php include('resume.html') ?>  
  </body>
</html>
