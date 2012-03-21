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

    <p class="note">This is a question off of <a href="http://www.viciouscycleinc.com">Vicious Cycle</a>'s <a href=".">pre-interview exam</a>. My trying to figure it out got very long and complicated and since I spent about a solid day doing this, I just had to share it. (<em>If you aren't reading this in <a href="http://ie7.com">Firefox</a>, it is likely the <acronym title="Scalable Vector Graphics">SVG</acronym>s or <acronym title="Mathematics Markup Language">MathML</acronym> won't render correctly.</em>)</p>

    <?php require("projectile_definitions.xhtml"); ?>
    
    <p>One other factor to consider is an identity about the magnitude of unit vectors which are, by definition, one.</p>

    <math xmlns="&mathml;" mode="display">
      <mfenced open="|" close="|">
        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
      </mfenced>
      <mo>=</mo>
      <mfenced open="|" close="|">
        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>p</mi></msub>
      </mfenced>
      <mo>=</mo><mn>1</mn>
    </math>
    
    <p>Which, when combined with the existing equations has the ability to remove an unknown:</p>
    
    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <mfenced open="|" close="|">
              <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced open="|" close="|" separators="">
              <msub><mi>f</mi><mi>p</mi></msub>
              <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
              <mfenced><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>p</mi></msub></mfenced>
            </mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msub><mi>f</mi><mi>p</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
            <mfenced open="|" close="|">
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>p</mi></msub>
            </mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msub><mi>f</mi><mi>p</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
          </mtd>
        </mtr>
      </mtable>
    </math>
    
    <p>This isn't terribly useful in and of itself because it introduces the magnitude of movement vector as another unknown. Nothing is known about that magnitude directly. It does add another known relationship however:</p>
    
    <math xmlns="&mathml;" mode="display">
      <mfrac>
        <mfenced open="|" close="|">
          <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mfenced>
        <mfenced open="|" close="|">
          <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </mfenced>
      </mfrac>
      <mo>=</mo>
      <mfrac>
        <mrow>
          <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
          <msub><mi>f</mi><mi>p</mi></msub>
        </mrow>
        <mrow>
          <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
          <msub><mi>f</mi><mi>t</mi></msub>
        </mrow>
      </mfrac>
      <mo>=</mo>
      <mfrac><msub><mi>f</mi><mi>p</mi></msub><msub><mi>f</mi><mi>t</mi></msub></mfrac>
    </math>
    
    <p>How to use this relationship, I am uncertain&hellip; I'm trying to figure out if there is a general solution or if a casewise test has to be done depending on if it the target and projectile are moving toward each other, or if one is chasing the other. Actually, I'm pretty sure there is always a chasing and a following solution, but for most cases, one is in the past (<math xmlns="&mathml;"><mo>&Delta;</mo><mi>t</mi><mo>&lt;</mo><mn>0</mn></math>).</p>
    
    <p>
      Another situation where the unit vector being
      <math xmlns="&mathml;"><mn>1</mn></math>
      is useful is in computing the dot product. Consider the following diagram. The plane that everything is defined is the plane defined by the normal
      <math xmlns="&mathml;">
        <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        <mo>x</mo>
        <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </math>.
      This plane should contain the initial target position, initial projectile position, and the hit position. Because this plane might cut through the original coordinate system at any angle, the projections of
      <math xmlns="&mathml;"><mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover></math>
      and
      <math xmlns="&mathml;"><mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover></math>
      might not have the same length as the originals.
    </p>

    <object data="target_plane.svg" type="image/svg+xml" style="width: 100%; height: 450px;"></object>

    <p>
      I want to define
      <math xmlns="&mathml;"><mi>&phi;</mi></math>
      as an angle.
      <math xmlns="&mathml;"><mi>&phi;</mi></math>
      is going to be used to desribe two angles which have the same magnitude, but which need to be considered separately. They are
      <math xmlns="&mathml;">
        <msubsup>
          <mi>&phi;</mi>
          <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
          <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        </msubsup>
      </math>
      which is the angle between
      <math xmlns="&mathml;"><mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover></math>
      and
      <math xmlns="&mathml;"><mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover></math>,
      and
      <math xmlns="&mathml;">
        <msubsup>
          <mi>&phi;</mi>
          <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
          <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
        </msubsup>
      </math>
      which is the angle between
      <math xmlns="&mathml;"><mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover></math>
      and
      <math xmlns="&mathml;"><msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub></math>.
    </p>

    <p>
      I also want to define a new vector:
      <math xmlns="&mathml;"><mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover></math>.
      This vector lies alone the intersection of the planes defined by the normals
      <math xmlns="&mathml;">
        <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
        <mo>x</mo>
        <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </math>
      and
      <math xmlns="&mathml;">
        <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
        <mo>x</mo>
        <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
      </math>.
      That is to say, there are two main plains in this problem. One contains the two vectors for the initial positions, the other contains the trajectories for the objects and their intersection. The vector
      <math xmlns="&mathml;"><mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover></math>
      lies along the intersection of those two and in the positions plane, it is the distance between the target and position,
      <math xmlns="&mathml;">
        <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover><mo>-</mo>
        <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
      </math>
      In the intersection plane, its position might change due to projection, but it should maintain certain characteristics:
    </p>

    <math xmlns="&mathml;" mode="display">
      <mfenced open="|" close="|">
        <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
      </mfenced>
      <mo>=</mo>
      <mfenced open="|" close="|" separators="-">
        <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
        <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
      </mfenced>
    </math>

    <math xmlns="&mathml;" mode="display">
      <msubsup>
        <mi>&phi;</mi>
        <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
        <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
      </msubsup>
      <mo>=</mo>
      <msubsup>
        <mi>&phi;</mi>
        <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
      </msubsup>
      <mo>=</mo>
      <msubsup>
        <mi>&phi;</mi>
        <mfenced separators="-">
          <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
          <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
        </mfenced>
        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
      </msubsup>
    </math>

    <p>If the magnitude and angle are the same for the two vectors, then their dot product should be the same as well.</p>

    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover><mo>&bull;</mo>
            <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced open="|" close="|">
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mfenced open="|" close="|">
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mfenced>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </msubsup>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced open="|" close="|" separators="-">
              <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
              <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mfenced open="|" close="|">
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mfenced>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mfenced separators="-">
                <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
                <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </msubsup>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced separators="-">
              <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
              <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mo>&bull;</mo>
            <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
          </mtd>
        </mtr>
      </mtable>
    </math>

    <p>
      This should be true since
      <math xmlns="&mathml;"><mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover></math>
      lies in the plane and there is no projection involved that would change the direction or magnitude of the vector.
    </p>

    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover><mo>&bull;</mo>
            <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced open="|" close="|">
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mfenced open="|" close="|">
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mfenced>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </msubsup>
          </mtd>
        </mtr>
        <mtr>
          <mtd>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </msubsup>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover><mo>&bull;</mo>
                <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
              </mrow>
              <mrow>
                <mfenced open="|" close="|">
                  <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                </mfenced>
                <mfenced open="|" close="|">
                  <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                </mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </msubsup>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover><mo>&bull;</mo>
                <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
              </mrow>
              <mrow>
                <mfenced open="|" close="|">
                  <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                </mfenced>
                <mfenced open="|" close="|">
                  <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                </mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
      </mtable>
    </math>
    
    <p>So this gives me <math xmlns="&mathml;"><mi>&phi;</mi></math>, or more spcifically, <math xmlns="&mathml;"><mo>cos</mo><mi>&phi;</mi></math>. What can I do with that? Apply the <a href="http://mathworld.wolfram.com/LawofCosines.html">law of cosines</a>.</p>
    
    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <msup>
              <mfenced open="|" close="|">
                <mover><msub><mi>m</mi><mi>p</mi></msub><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced open="|" close="|">
                <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
            <mo>+</mo>
            <msup>
              <mfenced open="|" close="|"><mrow>
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
            <mo>-</mo><mn>2</mn>
            <mfenced open="|" close="|">
              <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mfenced open="|" close="|">
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mo>cos</mo>
            <msubsup>
              <mi>&phi;</mi>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <mover><msub><mi>m</mi><mi>t</mi></msub><mo stretchy="true">&rarr;</mo></mover>
            </msubsup>
          </mtd>
        </mtr>
        <mtr>
          <mtd>
            <msup>
              <mfenced><mrow>
                <msub><mi>f</mi><mi>p</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced><mrow>
                <msub><mi>f</mi><mi>t</mi></msub>
                <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
            <mo>+</mo>
            <msup>
              <mfenced open="|" close="|">
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
            <mo>-</mo><mn>2</mn>
            <mfenced><mrow>
              <msub><mi>f</mi><mi>t</mi></msub>
              <mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
            </mrow></mfenced>
            <mfenced open="|" close="|">
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
            </mfenced>
            <mfenced>
              <mfrac>
                <mrow>
                  <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                  <mo>&bull;</mo>
                  <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                </mrow>
                <mfenced open="|" close="|">
                  <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                </mfenced>
              </mfrac>
            </mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd>
            <msup>
              <mfenced><mrow>
                <msub><mi>f</mi><mi>p</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced><mrow>
                <msub><mi>f</mi><mi>t</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
            <mo>+</mo>
            <msup>
              <mfenced open="|" close="|">
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
            <mo>-</mo><mn>2</mn>
            <mfenced><mrow>
              <msub><mi>f</mi><mi>t</mi></msub><mfenced><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mfenced>
            </mrow></mfenced>
            <mfenced><mrow>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <mo>&bull;</mo>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mn>0</mn></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>2</mn></msup>
            <mfenced separators="-">
              <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup>
              <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
            </mfenced>
            <mo>+</mo><mo>&Delta;</mo><mi>t</mi>
            <mfenced><mrow>
              <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
              <mfenced><mrow>
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                <mo>&bull;</mo>
                <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
              </mrow></mfenced>
            </mrow></mfenced>
            <mo>+</mo>
            <msup>
              <mfenced open="|" close="|">
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
        </mtr>
      </mtable>
    </math>
    
    <p>Then, apply the quadratic formula:</p>

    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd><mi>a</mi></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup><mo>-</mo>
            <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mi>b</mi></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
            <mfenced><mrow>
              <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              <mo>&bull;</mo>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
            <mfenced><mrow>
              <mfenced><mrow>
                <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover><mo>-</mo>
                <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
              </mrow></mfenced>
              <mo>&bull;</mo>
              <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
            <mo>&sum;</mo>
            <mfenced><mrow>
              <msub><mi>t</mi><mi>i</mi></msub><mo>-</mo>
              <msub><mi>p</mi><mi>i</mi></msub>
            </mrow></mfenced>
            <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mrow><mi>t</mi><mi>i</mi></mrow></msub>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mi>c</mi></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced open="|" close="|">
                <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced open="|" close="|" separators="-">
                <mover><mi>t</mi><mo stretchy="true">&rarr;</mo></mover>
                <mover><mi>p</mi><mo stretchy="true">&rarr;</mo></mover>
              </mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msup>
              <mfenced><mrow>
                <msqrt>
                  <mo>&sum;</mo>
                  <msup>
                    <mfenced separators="-">
                      <msub><mi>t</mi><mi>i</mi></msub>
                      <msub><mi>p</mi><mi>i</mi></msub>
                    </mfenced>
                    <mn>2</mn>
                  </msup>
                </msqrt>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>&plusminus;</mo><mo>&sum;</mo>
            <msup>
              <mfenced><mrow>
                <msub><mi>t</mi><mi>i</mi></msub><mo>-</mo>
                <msub><mi>p</mi><mi>i</mi></msub>
              </mrow></mfenced>
              <mn>2</mn>
            </msup>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mo>&Delta;</mo><mi>t</mi></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mo>-</mo><mi>b</mi><mo>&plusminus;</mo>
                <msqrt>
                  <msup><mi>b</mi><mn>2</mn></msup><mi>-</mi>
                  <mn>4</mn><mi>a</mi><mi>c</mi>
                </msqrt>
              </mrow>
              <mrow><mn>2</mn><mi>a</mi></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mo>-</mo>
                <mfenced separators="">
                  <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
                  <mfenced><mrow>
                    <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                    <mo>&bull;</mo>
                    <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                  </mrow></mfenced>
                </mfenced>
                <mo>&plusminus;</mo>
                <msqrt>
                  <msup>
                    <mfenced separators="">
                      <mo>-</mo><mn>2</mn><msub><mi>f</mi><mi>t</mi></msub>
                      <mfenced><mrow>
                        <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                        <mo>&bull;</mo>
                        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                      </mrow></mfenced>
                    </mfenced>
                    <mn>2</mn>
                  </msup>
                  <mi>-</mi>
                  <mn>4</mn>
                  <mfenced separators="-">
                    <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup>
                    <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
                  </mfenced>
                  <msup>
                    <mfenced open="|" close="|">
                      <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                    </mfenced>
                    <mn>2</mn>
                  </msup>
                </msqrt>
              </mrow>
              <mrow>
              <mn>2</mn>
              <mfenced separators="-">
                <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup>
                <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
              </mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <msub><mi>f</mi><mi>t</mi></msub>
                <mfenced><mrow>
                  <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                  <mo>&bull;</mo>
                  <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                </mrow></mfenced>
                <mo>&plusminus;</mo>
                <msqrt>
                  <msup>
                    <mfenced separators="">
                      <msub><mi>f</mi><mi>t</mi></msub>
                      <mfenced><mrow>
                        <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                        <mo>&bull;</mo>
                        <msub><mover accent="true"><mi>v</mi><mo stretchy="true">^</mo></mover><mi>t</mi></msub>
                      </mrow></mfenced>
                    </mfenced>
                    <mn>2</mn>
                  </msup>
                  <mi>-</mi>
                  <mfenced separators="-">
                    <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup>
                    <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
                  </mfenced>
                  <msup>
                    <mfenced open="|" close="|">
                      <mover><mi>d</mi><mo stretchy="true">&rarr;</mo></mover>
                    </mfenced>
                    <mn>2</mn>
                  </msup>
                </msqrt>
              </mrow>
              <mfenced separators="-">
                <msup><msub><mi>f</mi><mi>t</mi></msub><mn>2</mn></msup>
                <msup><msub><mi>f</mi><mi>p</mi></msub><mn>2</mn></msup>
              </mfenced>
            </mfrac>
          </mtd>
        </mtr>
      </mtable>
    </math>

    <p>This will give two solutions which is what I suspected. I was concerned that defining <math xmlns="&mathml;"><mi>&phi;</mi></math> in a certain plane would make it necessary to project the motion vectors into that plane, scaling their values. The vectors, by the definition of the plane, lie in the plane, so there can't be any scaling.</p>

    <p>The problem with this solution is that it doesn't work. &#x263A; <a href="projectile_targeting.c">A program</a> loaded into a <a href="targeting_test.c">testing driver</a> shows that the values are simply wrong.</p>
  </body>
</html>
