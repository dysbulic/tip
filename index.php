<!DOCTYPE html PUBLIC
  "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Will's R&eacute;sum&eacute;</title>
    <script type="application/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
    </script>
    <script type="application/javascript" src="index.js"></script>
    <script type="application/javascript"><![CDATA[
    ]]></script>
    <link rel="stylesheet" type="text/css" href="resume.css" />
  </head>
  <body>
    <?php if(isset($params['error'])) { ?>
      <div id="error"><?php print $params['error'] ?></div>
    <?php } ?>
    <form action="register.php" method="post">
      <fieldset>
        <div><legend>Name:</legend><input type="text" name="name"/></div>
        <div><legend>Username:</legend><input type="text" name="username"/></div>
        <div><legend>Password:</legend><input type="password" name="password"></div>
        <div><legend>E-mail:</legend><input type="text" name="email"/></div>
        <div><input type="submit" name="submit" value="Register"/></div>
      </fieldset>
    </form>
  </body>
</html>
