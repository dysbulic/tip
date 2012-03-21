<?php
$xhtml = preg_match("'application/xhtml\+xml'", $_SERVER['HTTP_ACCEPT']);
if($xhtml) {
  header("Content-type: application/xhtml+xml");
}
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>
<?php if($xhtml): ?>
<!-- "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" -->
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
 "http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd"
 [ <!ENTITY mathml  "http://www.w3.org/1998/Math/MathML">
   <!ENTITY hellip  "&#x2026;">
   <!ENTITY lang    "&#9001;">
   <!ENTITY rang    "&#9002;"> ]>
<?php endif; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"
      xmlns:xml="http://www.w3.org/XML/1998/namespace" lang="en">
  <head>
    <title>Color Change Links</title>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <link rel="stylesheet" href="../styles/main.css" type="text/css" />
    <style type="text/css">
      .cell {
        height: 2em;
        width: 8%;
        border: solid;
        float: left;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      td {
        height: .5em;
      }
      #colortable {
        display: none;
        position: absolute;
        border: medium groove;
        width: 20%;
      }
      #testdiv {
        float: none;
        margin: auto;
        width: 50%;
      }
      #testdiv:hover {
      }
      h1 {
        text-align: center;
        font-size: 30pt;
      }
      #testtext {
        color: rgb(0,0,0);
      }
      #testtext:hover {
      }
    </style>
    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript" src="../colortable/colortable.js"></script>
    <script type="text/javascript" src="fadelink.js"></script>
    <script type="text/javascript" src="fadedemo.js"></script>
  </head>
  <body onload="setup();">
    <h1>Color Change Links</h1>
    <p>The math for a line segment in three dimensions the endpoints P<sub>0</sub> and P<sub>1</sub> is:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mfenced open="&lang;" close="&rang;">
        <mi>x</mi><mi>y</mi><mi>z</mi>
      </mfenced>
      <mo>=</mo>
      <mfenced open="&lang;" close="&rang;">
        <msub><mi>a</mi><mi>x</mi></msub><msub><mi>a</mi><mi>y</mi></msub><msub><mi>a</mi><mi>z</mi></msub>
      </mfenced>
      <mo>+</mo>
      <mi>t</mi>
      <mo>⁢</mo> <!-- <mo>&InvisibleTimes;</mo> -->
      <mfenced open="&lang;" close="&rang;">
        <msub><mi>b</mi><mi>x</mi></msub><msub><mi>b</mi><mi>y</mi></msub><msub><mi>b</mi><mi>z</mi></msub>
      </mfenced>
    </math>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
       <mn>0</mn><mo>&lt;</mo><mi>t</mi><mo>&lt;</mo><mn>1</mn>
    </math>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mfenced open="&lang;" close="&rang;">
        <msub><mi>a</mi><mi>x</mi></msub><msub><mi>a</mi><mi>y</mi></msub><msub><mi>a</mi><mi>z</mi></msub>
      </mfenced>
      <mo>=</mo>
      <mfenced open="&lang;" close="&rang;">
        <msub><mi>P</mi><mi><msub><mn>1</mn><mi>x</mi></msub></mi></msub>
        <msub><mi>P</mi><mi><msub><mn>1</mn><mi>y</mi></msub></mi></msub>
        <msub><mi>P</mi><mi><msub><mn>1</mn><mi>z</mi></msub></mi></msub>
      </mfenced>
    </math>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mfenced open="&lang;" close="&rang;">
        <msub><mi>b</mi><mi>x</mi></msub>
        <msub><mi>b</mi><mi>y</mi></msub>
        <msub><mi>b</mi><mi>z</mi></msub>
      </mfenced>
      <mo>=</mo>
      <mfenced open="&lang;" close="&rang;">
        <mrow>
          <msub><mi>P</mi><mi><msub><mn>1</mn><mi>x</mi></msub></mi></msub> 
          <mo>-</mo>
          <msub><mi>P</mi><mi><msub><mn>2</mn><mi>x</mi></msub></mi></msub>
        </mrow>
        <mrow>
          <msub><mi>P</mi><mi><msub><mn>1</mn><mi>y</mi></msub></mi></msub>
          <mo>-</mo>
          <msub><mi>P</mi><mi><msub><mn>2</mn><mi>y</mi></msub></mi></msub>
        </mrow>
        <mrow>
          <msub><mi>P</mi><mi><msub><mn>1</mn><mi>z</mi></msub></mi></msub>
          <mo>-</mo>
          <msub><mi>P</mi><mi><msub><mn>2</mn><mi>z</mi></msub></mi></msub>
        </mrow>
      </mfenced>
    </math>
    <p>To see what the transition would look like in <acronym title="Red/Green/Blue">RGB</acronym>, click the first and last colors to change them:</p>
    <div id="colortable">
      <div id="colortable-xy"></div>
      <div id="colortable-z"></div>
    </div>
    <div id="rgb-test"></div>
    <p>Another common colorspace for <a href="../colortable/">color charts</a> is <acronym title="Hue/Saturation/Brightness">HSV</acronym> because it looks prettier when laid out. So far as transitions, however, it is too sporadic:</p>
    <div id="hsb-test"></div>
    <p>Examples:</p>
    <div id="testdiv" class="cell"></div>
    <h1><span id="testtext">This is a test</span></h1>
  </body>
</html>
