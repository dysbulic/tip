<?php
$xhtml = preg_match("'application/xhtml\+xml'", $_SERVER['HTTP_ACCEPT']);
if($xhtml) {
  header("Content-type: application/xhtml+xml");
 }
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>
<?php if($xhtml) { ?>
<!-- "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" -->
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
 "http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd"
 [ <!ENTITY mathml  "http://www.w3.org/1998/Math/MathML">
   <!ENTITY hellip  "&#x2026;">
   <!ENTITY mdash   "&#8212;">
   <!ENTITY bull    "&#8226;">
   <!ENTITY cross   "&#x2A2F;">
   <!ENTITY plusminus "&#xB1;">
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
    <title>Targeting a Moving Projectile</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
      .note { margin-left: 2em; border: 3px double; background-color: #EEE; padding: 1em 2em; }
      body { max-width: 800px; }
      q { white-space: normal; display: block; margin-left: 4em; text-indent -2em; font-family: sans-serif; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Targeting a Moving Projectile</h1>

    <p class="note">This is <a href="http://www.himinbi.org">my</a> attempt to understand <a href="http://wayne.madstones.com">Wayne</a>'s solution to this problem&hellip;</p>

    <?php require("projectile_definitions.xhtml"); ?>
    
    <p>
      <q>Distance bullet travels in unknown time (t)</q>
      <q>Sdist = Sspeed * t  or  t = Sdist/Sspeed</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mfenced open="|" close="|"><mrow>
        <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mo>=</mo><msub><mi>f</mi><mi>p</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
      <mo>&Therefore;</mo>
      <mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>=</mo>
      <mfrac>
        <mfenced open="|" close="|"><mrow>
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mrow></mfenced>
        <msub><mi>f</mi><mi>p</mi></msub>
      </mfrac>
    </math>
    
    <p>This works out from my original equations as well since each of those vectors is specified as a group of constant terms times a unit vector. The constant terms factor out and the magnitude of the unit vector is, by definition, one.</p>
    
    <p>
      <q>Distance target travels in unknown time (t)</q>
      <q>Tdist = Tspeed * t = Tspeed * Sdist/Sspeed</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mfenced open="|" close="|"><mrow>
        <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mo>=</mo><msub><mi>f</mi><mi>t</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
      <mo>=</mo>
      <msub><mi>f</mi><mi>t</mi></msub>
      <mfenced><mfrac>
        <mfenced open="|" close="|"><mrow>
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mrow></mfenced>
        <msub><mi>f</mi><mi>p</mi></msub>
      </mfrac></mfenced>
    </math>
    
    <p>This is just a substitution from the previous step.</p>
    
    <p>
      <q>New position of target after time (t) or Hit position</q>
      <q>Hpos(x,y,z) = Tpos(x,y,z) + Tdist * Tdir(dx, dy, dz)</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
      <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover><mo>+</mo>
      <mfenced open="|" close="|"><mrow>
        <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mfenced><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub></mfenced>
    </math>
    
    <p>This is another reordering of my equations from above. I specified the vectors in terms of an impulse and a time, whereas he is using the magnitude that is derived from those things.</p>
    
    <p>
      <q>Hpos(x,y,z) = Tpos(x,y,z) + Tspeed/Sspeed * Sdist * Tdir(dx,dy,dz)</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
      <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover><mo>+</mo>
      <mfrac>
        <msub><mi>f</mi><mi>t</mi></msub>
        <msub><mi>f</mi><mi>p</mi></msub>
      </mfrac>
      <mfenced open="|" close="|"><mrow>
        <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mfenced><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub></mfenced>
    </math>
    
    <p>This is a substitution from a previous step, and this is where I lose it. As best I can tell he treats this as a solution for <math xmlns="&mathml;"><mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover></math>. I don't really get that since both <math xmlns="&mathml;"><mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover></math> and <math xmlns="&mathml;"><mfenced open="|" close="|"><mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover></mfenced></math> are both unknown. (<math xmlns="&mathml;"><mfenced open="|" close="|"><mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover></mfenced></math> is a function of <math xmlns="&mathml;"><mo>&Delta;</mo><mi>t</mi></math> which is unknown.)</p>
    
    <p>
      <q>Distance from shot position to hit position, or distance bullet travels</q>
      <q>SHdist = ((Spos.x - Hpos.x)^2 + (Spos.y - Hpos.y)^2 + (Spos.z - Hpos.z)2)^(1/2)</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mfenced open="|" close="|"><mrow>
        <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mo>=</mo>
      <mfenced open="|" close="|"><mrow>
        <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover><mo>-</mo>
        <mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover>
      </mrow></mfenced>
      <mo>=</mo>
      <msqrt><mrow>
        <mo>&sum;</mo>
        <msup>
          <mfenced><mrow>
            <msub><mi>p</mi><mi>i</mi></msub><mo>-</mo>
            <msub><mi>H</mi><mi>i</mi></msub>
          </mrow></mfenced>
          <mn>2</mn>
        </msup>
      </mrow></msqrt>
    </math>
    
    <p>This is just the general equation for the magnitude of a vector.</p>
    
    <p>
      <q>Direction (unit vector) to shoot in, or vector from point S to point H</q>
      <q>SHdir(dx,dy,dz) = Sdir(dx,dy,dz) = ((Hpos.x - Spos.x ), (Hpos.y - Spos.y), (Hpos.z - Spos.z))/SHdist</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>p</mi></msub><mo>=</mo>
      <mfrac>
        <mrow>
          <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover><mo>-</mo>
          <mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover>
        </mrow>
        <mfenced open="|" close="|"><mrow>
          <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover><mo>-</mo>
          <mover><mi>H</mi><mo stretchy="true">&rarr;</mo></mover>
        </mrow></mfenced>
      </mfrac>
    </math>
    
    <p>He is computing the unit vector that I defined previously.</p>
    
    <p>
      <q>t = Sdist/Sspeed = Tdist/Tspeed</q>
    </p>
    
    <math xmlns="&mathml;" mode="display">
      <mo>&Delta;</mo><mi>t</mi><mo>=</mo>
      <mfrac>
        <mfenced open="|" close="|"><mrow>
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mrow></mfenced>
        <msub><mi>f</mi><mi>p</mi></msub>
      </mfrac><mo>=</mo>
      <mfrac>
        <mfenced open="|" close="|"><mrow>
          <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mrow></mfenced>
        <msub><mi>f</mi><mi>t</mi></msub>
      </mfrac>
    </math>
    
    <hr />
    
    <p>I really don't think this can be a solution. His basic equations are:</p>
    
    <ol>
      <li>
        <p>The movement vector for the target (unknown) is a force (known) times a direction (known) times a time (unknown)</p>
        <math xmlns="&mathml;" mode="display">
          <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
          <msub><mi>f</mi><mi>t</mi></msub>
          <mfenced><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub></mfenced>
          <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
        </math>
      </li>
      <li>
        <p>The movement vector for the projectile (unknown) is a force (known) times a direction (unknown) times a time (unknown)</p>
        <math xmlns="&mathml;" mode="display">
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
          <msub><mi>f</mi><mi>p</mi></msub>
          <mfenced><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>p</mi></msub></mfenced>
          <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
        </math>
      </li>
      <li>
        <p>The position of the projectile (known) plus the movement vector for the projectile (unknown) equals the position of the target (known) plus the movement vector for the target (unknown)</p>
        <math xmlns="&mathml;" mode="display">
          <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover><mo>+</mo>
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover><mo>=</mo>
          <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover><mo>+</mo>
          <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </math>
      </li>
    </ol>
    
    <p>That is three independant equations in four unknowns i.e. unsolvable. It could easily be though that I simply don't understand what he is doing. He is a mechanical engineer and this vector addition bit is his bread and butter.</p>

    <p>Since he also deals with the unknowns, there are really a couple more:</p>

    <math xmlns="&mathml;" mode="display">
      <mtable>
        <mtr>
          <mtd>
            <mfenced open="|" close="|"><mrow>
              <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </mrow></mfenced>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd><mo>&Delta;</mo><mi>t</mi><mfenced><msub><mi>f</mi><mi>p</mi></msub></mfenced></mtd>
        </mtr>
        <mtr>
          <mtd>
            <mfenced open="|" close="|"><mrow>
              <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </mrow></mfenced>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd><mo>&Delta;</mo><mi>t</mi><mfenced><msub><mi>f</mi><mi>t</mi></msub></mfenced></mtd>
        </mtr>
      </mtable>
    </math>

    <p>That is two new equations, but two new unknowns.</p>

  </body>
</html>
