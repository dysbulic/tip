<?php
  session_start();
  if(!isset($_SESSION['uid'])) header('Location: form_list.php');
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php require_once('radio_scale.php.inc'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Testing Scenario &mdash; Final Questionnaire</title>
    <link rel="stylesheet" type="text/css" href="../../styles/assignment.css" />
    <link rel="stylesheet" type="text/css" href="questionnaire.css" />
    <!--[if IE]><![if !IE]><![endif]-->
    <style type="text/css">
      ol, ol ol, ol ol ol { list-style: none; padding-left: 1.5em; }
      ol > li > label:before {
       display: marker;
       content: counter(topcounter, upper-roman) ': ';
      }
      ol ol > li > label:before, ol ol ol > li > label:before {
       display: marker;
       content: counter(topcounter, decimal) '.' counter(innercounter, decimal) ': ';
      }
      ol > li { counter-increment: topcounter; }
      ol ol > li { counter-increment: innercounter; }
      form > ol > li { margin-top: 1.5em; }
      form > ol > li > label { font-weight: bold; }
    </style>
    <!--[if IE]><![endif]><![endif]-->
    <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
    <script src="http://jqueryjs.googlecode.com/svn/trunk/plugins/form/jquery.form.js" type="text/javascript"></script>
    <script src="http://dev.jquery.com/export/3602/trunk/plugins/color/jquery.color.js" type="text/javascript"></script>
<!--    <script src="verify_questionnaire.js" type="text/javascript"></script> -->
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <div class="header">
      <h1>Exit Survey for the HCI Application User Evaluation</h1>
    </div>
    <form name="background" method="post" action="form_process.php">
      <div>
        <input type="hidden" name="form_name" value="final questionnaire" />
        <?php printf('<input type="hidden" name="uid" value="%d" />', $_SESSION['uid']) ?>
        <h2>Identification Number: <?php print $_SESSION['uid'] ?></h2>
      </div>
      <ol>
        <li>
          <label>Please list 3 things you like the most about the application:</label>
          <textarea name="LikeMost" rows="10" cols="40"></textarea>
        </li>
        <li>
          <label>Please list 3 things you like the least about the application:</label>
          <textarea name="LikeLeast" rows="10" cols="40"></textarea>
        </li>
        <li>
          <label>Additional Comments:</label><br/>
          <textarea name="Comments" rows="10" cols="40"></textarea>
        </li>
      </ol>
      <div>
        <input type="submit" value="Submit"/>
        <input type="reset" value="Reset"/>
      </div>
    </form>
  </body>
</html>
