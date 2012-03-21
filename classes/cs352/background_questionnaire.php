<?php
  session_start();
  if(!isset($_SESSION['uid'])) header('Location: form_list.php');
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php require_once('radio_scale.php.inc'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Testing Scenario &mdash; Background Questionnaire</title>
    <link rel="stylesheet" type="text/css" href="../../styles/assignment.css" />
    <link rel="stylesheet" type="text/css" href="questionnaire.css" />
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
<!--    <script src="verify_questionnaire.js" type="text/javascript"></script> -->
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <div class="header">
      <h1>Background Information for the HCI Application User Evaluation</h1>
    </div>
    <form name="background" method="post" action="form_process.php">
      <div>
        <input type="hidden" name="form_name" value="background questionnaire" />
        <?php printf('<input type="hidden" name="uid" value="%d" />', $_SESSION['uid']) ?>
        <h2>Identification Number: <?php print $_SESSION['uid'] ?></h2>
      </div>
      <ol>
        <li>
          <label>Biographical Information</label>
          <ol>
            <li class="required">
              <label>Age:</label>
              <?php printRadioScale('Age', array('&lt; 25', '25 - 34', '35 - 44', '45 - 54', '55 - 64', '&gt; 65')); ?>
            </li>
            <li>
              <label>Gender:</label>
              <?php printRadioScale('Gender', array('Male', 'Female')); ?>
            </li>
            <li>
              <label>Highest Level of Education:</label>
              <?php printRadioScale('Education', array('Some College', 'Bachelors', 'Some Graduate', 'Masters', 'Ph.D.')); ?>
            </li>
            <li>
              <label>Handedness:</label>
              <?php printRadioScale('Handedness', array('Left-Handed', 'Right-Handed')); ?>
            </li>
            <li>
              <label>Color-Blindness:</label>
              <?php printRadioScale('ColorBlindness', array('None', 'Red-Green', 'Blue-Yellow', 'Total', 'Other')); ?>
            </li>
            <li>
              <label>What is your primary area of expertise (e.g. Computer Science, Bio-Medical Engineering)?</label>
              <div><textarea name="expertise" cols="40" rows="4"></textarea></div>
            </li>
          </ol>
        </li>
        <li>
          <label>Robotic Experience</label>
          <ol>
            <li class="optional">How do you normally use computers? (Please mark all that apply)
              <ul>
                <li><input type="checkbox" name="Use_Email"/> <label>E-Mail</label></li>
                <li><input type="checkbox" name="Use_Internet"/> <label>Internet Browsing</label></li>
                <li><input type="checkbox" name="Use_Office"/> <label>Microsoft Office (Word, Excel, Access, PowerPoint, etc.)</label></li>
                <li><input type="checkbox" name="Use_TraditionalProgramming"/> <label>Traditional Programming (i.e. C/C++, LISP, Ada)</label></li>
                <li><input type="checkbox" name="Use_GraphicalProgramming"/> <label>Graphical Programming (i.e. Simulink, Agilent Vee, LabView, Visual Basic)</label></li>
                <li><input type="checkbox" name="Use_WebPageDevelopment"/> <label>Web Page Development</label></li>
                <li>
                  <label>Specialized or Other Professional Software:</label><br />
                  <textarea name="Use_ProfessionalSoftware" rows="4" cols="40"></textarea>
                </li>
              </ul>
            </li>
          </ol>
          <p>Please select that answer that most closely matches your response. Please note that the text on the end of the 7 point scales represents the end-point's value.</p>
          <ol start="2" style="counter-reset: innercounter 1">
            <li>
              <label>Have you previously programmed a robot?</label>
              <?php printRadioScale('RobotProgramming', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('RobotExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
            <li>
              <label>Have you previously programmed a computer interface?</label>
              <?php printRadioScale('InterfaceProgramming', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('InterfaceExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
            <li>
              <label>Have you ever simultaniously worked with multiple robots?</label>
              <?php printRadioScale('RobotControl', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('ControlExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
          </ol>
        </li>
        <li>
          <label>Previous Computer Game Experience</label>
          <ol>
            <li>
              <label>Have you ever played a Real-Time Strategy Computer Game? (Starcraft, Warcraft, Age of Empires, etc.)</label>
              <?php printRadioScale('RTSExposure', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('RTSExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
            <li>
              <label>Have you ever played a First Person Shooter type computer game? (Quake, James Bond, Counter Strike, etc.)</label>
              <?php printRadioScale('FPSExposure', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('FPSExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
            <li>
              <label>Have you ever played Unreal Tournament 2004?</label>
              <?php printRadioScale('UTExposure', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('UTExperience', array('Novice', 'Expert'), 7); ?>
                </li>
                <li class="optional required-yes">
                  <label>If yes, have you ever played the map "Unrealville?"</label>
                  <?php printRadioScale('UnrealvilleExperience', array('Yes', 'No')); ?>
                </li>
              </ol>
            </li>
            <li>
              <label>Have you ever played a Hack-n-Slash style computer game? (Dungeon Siege, Baldur's Gate, etc.)</label>
              <?php printRadioScale('HandSExposure', array('Yes', 'No')); ?>
              <ol>
                <li class="optional required-yes">
                  <label>If yes, please rate your expertise level.</label>
                  <?php printRadioScale('HandSExperience', array('Novice', 'Expert'), 7); ?>
                </li>
              </ol>
            </li>
          </ol>
        </li>
        <li>
          <input type="submit" value="Submit" />
          <input type="reset" value="Reset" />
        </li>
      </ol>
    </form>
  </body>
</html>
