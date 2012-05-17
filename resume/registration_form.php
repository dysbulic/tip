<?php function registration_form($params) { ?>
<!DOCTYPE html PUBLIC
  "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Registration Form</title>
    <script type="application/javascript"><![CDATA[
    ]]></script>
    <style type="text/css">
      body { max-width: 800px; margin: auto; display: block; padding-top: 5em; }
      h1, h2 { text-align: center }
      fieldset { float: left; padding: 1em; }
      filedset:after { content: ''; display: block; clear: both; }
      legend { float: left; width: 5em; text-align: right;
               padding-right: .5em; }
      [type='submit'] { margin-left: 14em; }
      form a { display: block; margin-left: 25%; margin-top: 1.5em; width: 50%;
               text-align: center; border: thin solid; text-decoration: none; }
      form a:hover { color: green; border-color: green; }
    </style>
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
<?php } ?>
