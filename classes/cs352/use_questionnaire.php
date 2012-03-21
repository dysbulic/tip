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
    <title>Testing Scenario &mdash; Use Questionnaire</title>
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
    <script src="verify_questionnaire.js" type="text/javascript"></script>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <div class="header">
      <h1>Usage Survey for the HCI Application User Evaluation</h1>
    </div>
    <form class="required" name="background" method="post" action="form_process.php">
      <div>
        <?php printf('<input type="hidden" name="form_name" value="use questionnaire - %s" />', $_GET['trial']) ?>
        <?php printf('<input type="hidden" name="uid" value="%d" />', $_SESSION['uid']) ?>
        <h2>Identification Number: <?php print $_SESSION['uid'] ?></h2>
      </div>
      <p>Please select the answer that most closely matches your response. Please note that text on the ends of the 7 point scales represents the end-point's value.</p>
      <ol>
        <li>
          <label>I preferred to use the interface with:</label>
          <?php printRadioScale('NumBots', array('Fewer Robots', 'More Robots'), 7); ?>
        </li>
        <li>
          <label>Controlling the robots' behaviors was:</label>
          <?php printRadioScale('BehaviorControl', array('Easy', 'Difficult'), 7); ?>
        </li>
        <li>
          <label>The provided built in robot behaviors were:</label>
          <?php printRadioScale('Behaviors', array('Not Helpful', 'Helpful'), 7); ?>
        </li>
        <li>
          <label>Finding out which robots were idle or otherwise needed attention was:</label>
          <?php printRadioScale('NeedAttention', array('Easy', 'Difficult'), 7); ?>
        </li>
        <li>
          <label>Building my own mental map of the world the robots were in was:</label>
          <?php printRadioScale('SA', array('Easy', 'Difficult'), 7); ?>
        </li>
        <li>
          <label>The orchestration view in the interface was:</label>
          <?php printRadioScale('Orchestration', array('Not Helpful', 'Helpful'), 7); ?>
        </li>
        <li>
          <label>The robot manipulation view in the interface was:</label>
          <?php printRadioScale('Manipulation', array('Not Helpful', 'Helpful'), 7); ?>
        </li>
        <li>
          <label>The robot task progress bars were:</label>
          <?php printRadioScale('ProgressBars', array('Not Helpful', 'Helpful'), 7); ?>
        </li>
        <li>
          <label>The video feeds provided by the robots were:</label>
          <?php printRadioScale('Feeds', array('Not Helpful', 'Helpful'), 7); ?>
        </li>
        <li>
          <label>Overall, the system was:</label>
          <?php printRadioScale('Overall', array('Not Intuitive', 'Intuitive'), 7); ?>
        </li>
        <li>
          <label>Overall, I liked using this system:</label>
          <?php printRadioScale('LikedSystem', array('Disagree', 'Agree'), 7); ?>
        </li>
      </ol>
      <div>
        <input type="submit" value="Submit"/>
        <input type="reset" value="Reset"/>
      </div>
    </form>
  </body>
</html>

/*  LocalWords:  LikedSystem
 */
