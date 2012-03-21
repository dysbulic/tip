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
    <title>Testing Scenario &mdash; Situation Awareness Measurment</title>
    <link rel="stylesheet" type="text/css" href="../../styles/assignment.css" />
    <link rel="stylesheet" type="text/css" href="questionnaire.css" />
    <style type="text/css">
    </style>
    <!--[if IE]><![if !IE]><![endif]-->
    <style type="text/css">
      ol, ol ol, ol ol ol { list-style: none; padding-left: 1.5em; }
      ol > li > label:before {
       display: marker;
       content: 'Part ' counter(topcounter, upper-roman) ': ';
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
      <h1>SART (Situation Awareness Rating Technique)</h1>
    </div>
    <form name="sart" method="post" action="form_process.php" class="required">
      <div>
        <?php printf('<input type="hidden" name="form_name" value="SART questionnaire - %s" />', $_GET['trial']) ?>
        <?php printf('<input type="hidden" name="uid" value="%d" />', $_SESSION['uid']) ?>
        <h2>Identification Number: <?php print $_SESSION['uid'] ?></h2>
      </div>

      <h2>Instructions</h2>
      <p>Situation Awareness (SA) in this study represents your knowledge of all the robots on your team (state and status). For each dimension below, please place a mark under the rating value that matches your experience with what you just completed.</p>
      
      <p>Please select that answer that most closely matches your response. Please note that the text on the end of the 7 point scales represents the end-point's value.</p>

      <ol>
        <li>
          <label>Demands on Attentional Resources</label>
          <p>Demands placed on your attentional resources by completing the tasks. How much of the situational changes, number of variables, and complexity affected your SA.</p>
          <?php printRadioScale('AttentionalDemands', array('Low', 'High'), 7); ?>
        </li>
        <li>
          <label>Supply of Attentional Resources</label>
          <p>Think of your mental state while completing the tasks. Rating should reflect your degree of arousal, your spare mental capacity, your ability to concentrate, and your ability to divide attention across multiple robots and tasks.</p>
          <?php printRadioScale('AttentionalResources', array('Low', 'High'), 7); ?>
        </li>
        <li>
          <label>Understanding of the Situation</label>
          <p>How your understanding and knowledge of the situation affects the task performance and SA. Please rate the quantity of information available to you, the quality of that information, and your familiarity with the tasks.</p>
          <?php printRadioScale('Understanding', array('Low', 'High'), 7); ?>
        </li>
        <li>
          <label>Overall SA</label>
          <p>You should assume a broad perspective that takes into account your entire experience while completing the tasks, and to generate a single rating that you feel best represents your SA while performing the tasks.</p>
          <?php printRadioScale('OverallSA', array('Low', 'High'), 7); ?>
        </li>
      </ol>
      <div>
        <input type="submit" value="Submit"/>
        <input type="reset" value="Reset"/>
      </div>
    </form>
  </body>
</html>
