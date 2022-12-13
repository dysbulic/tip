<?php
$xhtml = preg_match("'application/xhtml\+xml'", $_SERVER['HTTP_ACCEPT']);
if($xhtml) {
  header("Content-type: application/xhtml+xml");
 }
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>

<?php if($xhtml) { ?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
 "http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd"
 [ <!ENTITY mathml  "http://www.w3.org/1998/Math/MathML">
   <!ENTITY hellip  "&#x2026;">
   <!ENTITY mdash   "&#8212;">
   <!ENTITY bull    "&#8226;">
   <!ENTITY deg     "&#xB0;">
   <!ENTITY copy    "&#x9A;">
   <!ENTITY gamma   "&#x3B3;">
   <!ENTITY epsilon "&#x3B5;">
   <!ENTITY sum     "&#8721;">
   <!ENTITY rarr    "&#8594;">
   <!ENTITY cap     "&#8745;">
   <!ENTITY Delta   "&#x394;">
   <!ENTITY delta   "&#x3B4;">
   <!ENTITY phi     "&#x3C6;">
   <!ENTITY SuchThat "&#x220B;">
   <!ENTITY ForAll  "&#x2200;">
   <!ENTITY Element "&#x2208;">
   <!ENTITY Exists  "&#x2203;">
   <!ENTITY Therefore     "&#x2234;">
   <!ENTITY ApplyFunction "&#x2061;">
   <!ENTITY geq     "&#x2265;">
   <!ENTITY leq     "&#x2264;">
   <!ENTITY lfloor  "&#x230A;">
   <!ENTITY rfloor  "&#x230B;">
   <!ENTITY prime   "&#x2032;">
   <!ENTITY isin    "&#x2208;">
   <!ENTITY notin   "&#x2209;">
   <!ENTITY real    "&#x211D;"> ]>
<?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:math="http://www.w3.org/1998/Math/MathML">
  <head>
    <title>Camera Position Transformation Matrix</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <blockquote><p>Given a camera at point p1 compute a view matrix so that the camera looks at an arbitrary point p2. Assume vector 'up' as the up vector (hint: use {0,1,0} to estimate the true up vector). Compiled code not required.</p></blockquote>

    <p>The answer to this one uses an interpretation of the transformation matrix I got from <a href="http://matt.madstones.com">Matt</a> <a href="http://odin.himinbi.org/old_web/java3d/">years ago</a>:</p>
    
    <math xmlns="&mathml;" mode="display">
      <mfenced open="[" close="]"><mtable>
        <mtr>
          <mtd><msub><mi>X</mi><mi>x</mi></msub></mtd>
          <mtd><msub><mi>Y</mi><mi>x</mi></msub></mtd>
          <mtd><msub><mi>Z</mi><mi>x</mi></msub></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd><msub><mi>X</mi><mi>y</mi></msub></mtd>
          <mtd><msub><mi>Y</mi><mi>y</mi></msub></mtd>
          <mtd><msub><mi>Z</mi><mi>y</mi></msub></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd><msub><mi>X</mi><mi>z</mi></msub></mtd>
          <mtd><msub><mi>Y</mi><mi>z</mi></msub></mtd>
          <mtd><msub><mi>Z</mi><mi>z</mi></msub></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd><msub><mi>W</mi><mi>x</mi></msub></mtd>
          <mtd><msub><mi>W</mi><mi>y</mi></msub></mtd>
          <mtd><msub><mi>W</mi><mi>z</mi></msub></mtd>
          <mtd><mn>1</mn></mtd>
        </mtr>
      </mtable></mfenced>
    </math>
    
    <p>Where <math xmlns="&mathml;"><mover><mi>X</mi><mo stretchy="true">&rarr;</mo></mover></math>, <math xmlns="&mathml;"><mover><mi>Y</mi><mo stretchy="true">&rarr;</mo></mover></math> and <math xmlns="&mathml;"><mover><mi>Z</mi><mo stretchy="true">&rarr;</mo></mover></math> are the unit vectors for each of the axes and <math xmlns="&mathml;"><mover><mi>W</mi><mo stretchy="true">&rarr;</mo></mover></math> is the origin.</p>
    
    <p><math xmlns="&mathml;"><mover><mi>W</mi><mo stretchy="true">&rarr;</mo></mover></math> is easy:</p>
    
    <math xmlns="&mathml;" mode="display">
      <mover><mi>W</mi><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
      <msub><mi>p</mi><mn>1</mn></msub>
    </math>
    
    <p><a href="http://www.toymaker.info/Games/html/camera.html">The internet</a> says that the camera will be pointed down the Z-axis, so that is just a unit vector pointed from <math xmlns="&mathml;"><mover><mi>W</mi><mo stretchy="true">&rarr;</mo></mover></math> to <math xmlns="&mathml;"><msub><mi>p</mi><mn>2</mn></msub></math>:</p>
    
    <math xmlns="&mathml;" mode="display">
      <mover><mi>Z</mi><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
      <mfrac>
        <mrow><msub><mi>p</mi><mn>2</mn></msub><mo>-</mo><msub><mi>p</mi><mn>1</mn></msub></mrow>
        <mfenced open="|" close="|">
          <mrow><msub><mi>p</mi><mn>2</mn></msub><mo>-</mo><msub><mi>p</mi><mn>1</mn></msub></mrow>
        </mfenced>
      </mfrac>
    </math>
    
    <p>The next axis that is needed is <math xmlns="&mathml;"><mover><mi>Y</mi><mo stretchy="true">&rarr;</mo></mover></math>. To find it</p>
  </body>
</html>
