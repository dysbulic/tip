<?php
session_start();
if(isset($_GET['reset'])) {
  session_destroy();
  header('Location: ' . $_SERVER['PHP_SELF']);
  return;
}
if(!isset($_SESSION['uid']) && isset($_POST['uid'])) $_SESSION['uid'] = $_POST['uid'];

function printItem($name) {
  $id = strtolower(preg_replace("/ - [0-9]+/", "", $name));
  $trial = ($id != $name) ? $trial = substr($name, strlen($id) + 3) : '';
  printf('<li class="%s">', $_SESSION[$name . ' done'] ? 'done' : '');
  printf('<a href="%s.php?trial=%s">%s</a>', preg_replace("/ /", "_", $id), $trial, ucwords($name));
  print '</li>';
}
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>User Evaluation Forms</title>
    <link rel="stylesheet" type="text/css" href="../../styles/assignment.css" />
    <style type="text/css">
      .done:before { display: marker; content: 'x '; margin-left: -1em; }
      .done { list-style: none; opacity: .5; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body >
    <div id="header">
      <h1>CS-352 &mdash; Test Questionnaires</h1>
    </div>
    <?php if(!isset($_SESSION['uid'])) { ?>
      <?php printf('<form action="%s" method="post">', $_SERVER['PHP_SELF']) ?>
      <label>User ID:</label><input type="text" name="uid" />
      <?php print('</form>') ?>
    <?php } else { ?>
      <h2>Identification Number: <?php print $_SESSION['uid'] ?></h2>
      <ul>
        <?php printItem('background questionnaire') ?>
        <li><a href="consent_form.pdf">Consent form</a></li>
        <li>Training Scenario</li>
        <li>
          <p>Test Scenario - 1</p>
          <ul>
            <li><a href="nasa-tlx_survey.html">NASA-TLX questionnaire</a></li>
            <?php printItem('SART questionnaire - 1') ?>
            <?php printItem('use questionnaire - 1') ?>
          </ul>
        </li>
        <li>
          <p>Test Scenario - 2</p>
          <ul>
            <li><a href="nasa-tlx_survey.html">NASA-TLX questionnaire</a></li>
            <?php printItem('SART questionnaire - 2') ?>
            <?php printItem('use questionnaire - 2') ?>
          </ul>
        </li>
        <?php printItem('final questionnaire') ?>
      </ul>
      <p><?php printf('<a href="%s?reset">Reset</a>', $_SERVER['PHP_SELF']) ?></p>
    <?php } ?>
  </body>
</html>
