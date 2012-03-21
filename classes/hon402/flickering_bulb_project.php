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
   <!ENTITY bull    "&#x7E6;">
   <!ENTITY deg     "&#xB0;">
   <!ENTITY copy    "&#x9A;">
   <!ENTITY gamma   "&#x3B3;">
   <!ENTITY epsilon "&#x3B5;">
   <!ENTITY Delta   "&#x394;">
   <!ENTITY delta   "&#x3B4;">
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
    <title>Flickering Bulb Spacetime Paradox</title>
    <link rel="stylesheet" type="text/css" href="homework.css"/>
    <link rel="shortcut icon" href="http://odin.himinbi.org/gallery/main.php?g2_view=core.DownloadItem&amp;g2_itemId=8950" />
    <style type="text/css">
      .figure {
        display: block;
        width: 100%;
        height: 150px;
      }
      body { font-size: 13pt; }
      p, ul, math[mode = 'display'] { margin-left: 10%; max-width: 40em; }
      #intro { font-style: italic; background-color: #DDD; padding: .25em .5em; border: 2px solid; }
      #footer a { text-decoration: none; }
      #footer img { border: none; }
      #copy { float: left; }
      #license { float: right; }
    </style>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <!--[if IE]>
    <p id="intro">Warning: Internet Explorer does not properly handle the math markup used for this document (as of 2009). You should upgrade to <a href="http://firefox.com">Firefox</a> or <a href="http://opera.com">Opera</a> (which will give you a better browsing experience overall.</p>
    <![endif]-->
    <h1>The Flickering Bulb Paradox</h1>
    <h2>Proposed by G.P. Sanstry</h2>
    <p>Two long conducting rails are open at one end but connected at the other end through a lamp and battery in series. One of the rails has a square vertical offset 2m long. Between the rails a H-shaped slider moves frictionlessly. The vertical bars of the sliders are conducting and the cross piece is insulating. If either of bars of the slider is touching both of the rails simultaneously then the circuit is completed and the bulb lights.</p>
    <object class="figure" type="image/svg+xml" data="slider-and-rails-at-rest.svg"></object>
    <p>If you take the slider and move it along the rails then the bulb will stay constantly lit because there is a constant connection until bar A hits the gap and immediately when that happens bar B makes connection completing the circuit, so there is no break.</p>
    <p>Spacetime physics however has a phenomena known as Lorentz contraction such that an object shrinks along the axis of motion. This so called "stretch factor" is represented by <math:math><math:mi>&gamma;</math:mi></math:math> where:</p>
    <math mode="display" xmlns="&mathml;">
      <mi>&gamma;</mi>
      <mo>=</mo>
      <mfrac>
        <mn>1</mn>
        <msqrt><mrow>
            <mn>1</mn><mo>-</mo><msup><msub><mi>v</mi><mi>rel</mi></msub><mn>2</mn></msup>
        </mrow></msqrt>
      </mfrac>
    </math>
    <p>Where <math:math><math:msub><math:mi>v</math:mi><math:mi>rel</math:mi></math:msub></math:math> is the relative velocity between the observer and the observed as a fraction of the speed of light.</p>
    <p>
      This concept of contraction of length introduces an apparent paradox. Say for instance that the slider is moving at 259627884 m/s, that's approximately <math:math><math:mfrac><math:msqrt><math:mn>3</math:mn></math:msqrt><math:mn>2</math:mn></math:mfrac><math:mi>c</math:mi></math:math>. At that speed <math:math><math:mi>&gamma;</math:mi><math:mo>=</math:mo><math:mn>2</math:mn></math:math>, so the length of the slider would be reduced by a factor of two, making it 1m.
    </p>
    <object class="figure" type="image/svg+xml" data="slider-moving.svg"></object>
    <p>If the slider is contracted then there will be a length of time during which the circuit is completely broken and the light goes off.</p>
    <p>The theory of relativity rests heavily on the concept that there is no absolute frame of observation, so it is equally valid to see the experiment as the slider holding still and the rails moving in the opposite direction at <math:math><math:msub><math:mi>v</math:mi><math:mi>rel</math:mi></math:msub></math:math>. From this frame though the rails will be Lorentz contracted and so it will appear as:</p>
    <object class="figure" type="image/svg+xml" data="rails-moving.svg"></object>
    <p>The gap is now only 1m, so the slider maintains contact the entire time and the bulb will never go off. Relativity does not allow for different events to occur for different observers. The order of the events can be different, but the bulb either goes off or it doesn't and that fact is the same for all frames of reference. So, there must be some way to resolve this paradox, or the contraction of length does not exist.</p>
    <p>The resolution comes with the recognition that the electromotive force, &epsilon;, like everything else, cannot move faster than the speed of light. This means that when a rail makes (or breaks) contact there is a time lapse of at least 4 light-meters before that change in force will reach the bulb. (It is 4m to the side of the gap closest to the bulb, 6m to the far side.) To better examine this, it is convenient to define some events. These events will take place in all frames (though not necessarily at the same time or even in the same order.) They are shown here in the frame with the rails at rest, though again this is arbitrary and could have been shown just as easily in the frame with the slider at rest.</p>
    <p><strong>Event #1</strong>: Bar A enters the gap (breaking contact with the rails):</p>
    <object class="figure" type="image/svg+xml" data="event-one.svg"></object>
    <p><strong>Event #2</strong>: Bar B exits the gap (reestablishing contact with the rails):</p>
    <object class="figure" type="image/svg+xml" data="event-two.svg"></object>
    <p>You will notice that the bulb is not out in either event even though during the interval between then there was no circuit. This seems incorrect at first, but again the changes in &epsilon; are not simultaneous; they require time for the change is force to propagate through the wire. These changes in &epsilon; are represented with *s. To better analyze this we will define two more events.</p>
    <p><strong>Event #3</strong>: The drop in &epsilon; from event #1 reaches the bulb:</p>
    <object class="figure" type="image/svg+xml" data="event-three.svg"></object>
    <p><strong>Event #4</strong>: The restoration of &epsilon; from event #2 reaches the bulb:</p>
    <object class="figure" type="image/svg+xml" data="event-four.svg"></object>
    <p>If event #4 occurs at or before the time event three occurs then &epsilon; is never diminished and the bulb will not flicker.</p>

    <h2 id="railrest">How long is the bulb off in the frame where the rail is at rest?</h2>
    <p>The bulb will be off for the period of time between events 3 and 4 (<math xmlns="&mathml;"><msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub></math>). Using the following notations:</p>
    <object class="figure" type="image/svg+xml" data="rails-rest-notations.svg"></object>
    <p>Obviously there are relationships between events 1 and 3, 2 and 4 since 1 causes 3 and 2 causes 4. The time between 1 and 3 is just:</p>
    <math mode="display" xmlns="&mathml;">
      <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>1,3</mn></msub>
      <mo>=</mo>
      <msub><mi>t</mi><mn>3</mn></msub><mo>-</mo><msub><mi>t</mi><mn>1</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mo>-</mo><mi>x</mi></mrow>
        <msub><mi>v</mi><mn>pulse</mn></msub>
      </mfrac>
    </math>
    <p>Since those events are separated by the time that it takes the pulse to cover the distance from <math:math><math:mi>x</math:mi></math:math> to <math:math><math:mn>0</math:mn></math:math>. The distance is negative because the pulse is traveling toward 0. (The value of <math:math><math:msub><math:mi>v</math:mi><math:mi>pulse</math:mi></math:msub></math:math> is negative as well.) Similarly:</p>
    <math mode="display" xmlns="&mathml;">
      <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>2,4</mn></msub>
      <mo>=</mo>
      <msub><mi>t</mi><mn>4</mn></msub><mo>-</mo><msub><mi>t</mi><mn>2</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mo>-</mo><mfenced><mrow><mi>x</mi><mo>+</mo><mi>J</mi></mrow></mfenced></mrow>
        <msub><mi>v</mi><mn>pulse</mn></msub>
      </mfrac>
    </math>
    <p>The time between 1 and 2 is a little more sophisticated. The length if the gap is <math:math><math:mi>J</math:mi></math:math>. At event #1 the position of bar B is <math xmlns="&mathml;"><mi>x</mi><mo>+</mo><msup><mi>S</mi><mo>&prime;</mo></msup></math>, that means the distance from bar B and the other side of the gap is <math xmlns="&mathml;"><mi>J</mi><mo>-</mo><msup><mi>S</mi><mo>&prime;</mo></msup></math>. The time that it will take the slider to move that distance is:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <msub><mrow><mrow><mo>&Delta;</mo><mi>t</mi></mrow></mrow><mn>1,2</mn></msub>
      <mo>=</mo>
      <msub><mi>t</mi><mn>2</mn></msub><mo>-</mo><msub><mi>t</mi><mn>1</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mi>J</mi><mo>-</mo><msup><mi>S</mi><mo>&prime;</mo></msup></mrow>
        <msub><mi>v</mi><mn>rel</mn></msub>
      </mfrac>
    </math>
    <p>Since <math xmlns="&mathml;"><msub><mi>t</mi><mn>1</mn></msub></math> is the first event, it is convenient to assign:</p>
    <math mode="display" xmlns="&mathml;">
      <msub><mi>t</mi><mn>1</mn></msub>
      <mo>=</mo>
      <mn>0</mn>
    </math>

    <p>So:</p>

    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
          <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub>
            <msub><mo minsize="225%">|</mo><mrow><msub><mi>t</mi><mn>1</mn></msub><mo>=</mo><mn>0</mn></mrow></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msub><mi>t</mi><mn>4</mn></msub>
            <mo>-</mo>
            <msub><mi>t</mi><mn>3</mn></msub>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced><mrow>
              <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mrow><mn>2,4</mn></mrow></msub><mo>+</mo><msub><mi>t</mi><mn>2</mn></msub>
            </mrow></mfenced>
            <mo>-</mo>
            <mfenced><mrow>
              <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mrow><mn>1,3</mn></mrow></msub><mo>+</mo><msub><mi>t</mi><mn>1</mn></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced><mrow>
              <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mrow><mn>2,4</mn></mrow></msub>
              <mo>+</mo>
              <mfenced><mrow>
                <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mrow><mn>1,2</mn></mrow></msub><mo>+</mo><msub><mi>t</mi><mn>1</mn></msub>
              </mrow></mfenced>
            </mrow></mfenced>
            <mo>-</mo>
            <mfenced><mrow>
              <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mrow><mn>1,3</mn></mrow></msub><mo>+</mo><msub><mi>t</mi><mn>1</mn></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow><mo>-</mo><mi>x</mi><mo>-</mo><mi>J</mi></mrow>
              <msub><mi>v</mi><mn>pulse</mn></msub>
            </mfrac>
            <mo>+</mo>
            <mfrac>
              <mrow><mi>J</mi><mo>-</mo><msup><mi>S</mi><mo>&prime;</mo></msup></mrow>
              <msub><mi>v</mi><mn>rel</mn></msub>
            </mfrac>
            <mo>+</mo><mn>0</mn><mo>-</mo>
            <mfrac>
              <mrow><mo>-</mo><mi>x</mi></mrow>
              <msub><mi>v</mi><mn>pulse</mn></msub>
            </mfrac>
            <mo>-</mo><mn>0</mn>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow><mo>-</mo><mi>J</mi></mrow>
              <msub><mi>v</mi><mn>pulse</mn></msub>
            </mfrac>
            <mo>+</mo>
            <mfrac>
              <mrow><mi>J</mi><mo>-</mo><msup><mi>S</mi><mo>&prime;</mo></msup></mrow>
              <msub><mi>v</mi><mn>rel</mn></msub>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>J</mi><msub><mi>v</mi><mn>pulse</mn></msub>
                <mo>-</mo>
                <mi>J</mi><msub><mi>v</mi><mn>rel</mn></msub>
                <mo>-</mo>
                <msup><mi>S</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>pulse</mn></msub>
              </mrow>
              <mrow>
                <msub><mi>v</mi><mn>pulse</mn></msub>
                <msub><mi>v</mi><mn>rel</mn></msub>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
      </mtable>
    </math>

    <p>Substituting in the values that were given:</p>
    <ul>
      <li><math xmlns="&mathml;"><mi>J</mi><mo>=</mo><mi>S</mi><mo>=</mo><mn>2m</mn></math></li>
      <li>
        <math xmlns="&mathml;">
          <msub><mi>v</mi><mi>rel</mi></msub><mo>=</mo>
          <mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <msub><mi>v</mi><mi>pulse</mi></msub>
          <mo>=</mo><mo>-</mo><mn>1</mn>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <mi>&gamma;</mi><mo>=</mo>
          <mfrac>
            <mn>1</mn>
            <msqrt><mrow>
              <mn>1</mn><mo>-</mo><msup><msub><mi>v</mi><mi>rel</mi></msub><mn>2</mn></msup>
            </mrow></msqrt>
          </mfrac>
          <mo>=</mo>
          <mfrac>
            <mn>1</mn>
            <msqrt><mrow>
              <mn>1</mn><mo>-</mo><msup><mfenced><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mfenced><mn>2</mn></msup>
            </mrow></msqrt>
          </mfrac>
          <mo>=</mo><mn>2</mn>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <msup><mi>S</mi><mo>&prime;</mo></msup>
          <mo>=</mo>
          <mfrac><mi>S</mi><mi>&gamma;</mi></mfrac>
          <mo>=</mo>
          <mfrac><mi>S</mi><mn>2</mn></mfrac>
          <mo>=</mo>
          <mn>1m</mn>
        </math>
      </li>
    </ul>
    <math xmlns="&mathml;" mode="display">
      <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub><mo>=</mo>
      <mfrac>
        <mrow><mn>2</mn><msqrt>3</msqrt><mo>+</mo><mn>2</mn></mrow>
        <msqrt>3</msqrt>
      </mfrac>
      <mo>&#x0224A;</mo><mn>3.1547m</mn>
    </math>
    
    <p><em>In spacetime it is not uncommon to express lengths of time in units commonly associated with distance. The two converge with the speed of light which is constant in all frames of reference at <math xmlns="&mathml;"><mn>c</mn><mo>&#x2261;</mo><mn>299792458</mn><mfrac><mn>m</mn><mn>s</mn></mfrac></math>.</em></p>

    <hr />

    <h2 id="sliderrest">How long is the bulb off in the frame where the slider is at rest?</h2>
    <p>This scenario is very similar to the one where the rails are at rest: the time that the bulb will be off is still the time between events 3 and 4 (<math xmlns="&mathml;"><msub><mrow><mo>&Delta;</mo><msup><mi>t</mi><mo>&prime;</mo></msup></mrow><mn>3,4</mn></msub></math>). Using the following notations:</p>
    <object class="figure" type="image/svg+xml" data="slider-rest-notations.svg"></object>
    <p>One important difference in this scenario is that relative velocity between the change in &epsilon; and the bulb (<math:math><math:msub><math:mi>v</math:mi><math:mi>pulse</math:mi></math:msub></math:math>) is effectively reduced. The pulse is essentially "chasing" the bulb and so:</p>
    <math xmlns="&mathml;" mode="display">
      <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mi>pulse</mi></msub><mo>=</mo>
      <msub><mi>v</mi><mi>pulse</mi></msub><mo>-</mo>
      <msub><mi>v</mi><mi>rel</mi></msub>
    </math>
    <p>Based on this change, the previous equations are altered slightly:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>1,3</mn></msub>
      <mo>=</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>3</mn></msub>
      <mo>-</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>1</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mo>-</mo><msup><mi>x</mi><mo>&prime;</mo></msup></mrow>
        <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
      </mfrac>
    </math>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>2,4</mn></msub>
      <mo>=</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>4</mn></msub>
      <mo>-</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mo>-</mo><mfenced><mrow><msup><mi>x</mi><mo>&prime;</mo></msup><mo>+</mo><msup><mi>J</mi><mo>&prime;</mo></msup></mrow></mfenced></mrow>
        <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
      </mfrac>
    </math>
    <p>The other major difference in this frame of reference is that event #2 happens before event #1. This means that the time difference between 1 and 2 is the time it takes bar A to traverse the distance <math:math><math:mi>S</math:mi><math:mo>-</math:mo><math:msup><math:mi>J</math:mi><math:mo>&prime;</math:mo></math:msup></math:math>. (As opposed to the time for bar B to pass <math:math><math:mi>J</math:mi><math:mo>-</math:mo><math:msup><math:mi>S</math:mi><math:mo>&prime;</math:mo></math:msup></math:math> in the frame with the rails at rest.)</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>2,1</mn></msub>
      <mo>=</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>1</mn></msub>
      <mo>-</mo>
      <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub>
      <mo>=</mo>
      <mfrac>
            <mrow><mo>-</mo><mfenced><mrow><mi>S</mi><mo>-</mo><msup><mi>J</mi><mo>&prime;</mo></msup></mrow></mfenced></mrow>
        <msub><mi>v</mi><mn>rel</mn></msub>
      </mfrac>
    </math>
    <p>Since event #2 is now the first, it is convenient to combine the equations with <math xmlns="&mathml;"><msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub><mo>=</mo><mn>0</mn></math></p>

    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub>
            <msub><mo minsize="225%">|</mo><mrow><msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub><mo>=</mo><mn>0</mn></mrow></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>4</mn></msub>
            <mo>-</mo>
            <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>3</mn></msub>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced><mrow>
              <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>2,4</mn></msub>
              <mo>+</mo>
              <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub>
            </mrow></mfenced>
            <mo>-</mo>
            <mfenced><mrow>
              <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>1,3</mn></msub>
              <mo>+</mo>
              <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>1</mn></msub>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfenced><mrow>
              <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>2,4</mn></msub>
              <mo>+</mo>
              <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub>
            </mrow></mfenced>
            <mo>-</mo>
            <mfenced><mrow>
              <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>1,3</mn></msub>
              <mo>+</mo>
              <mfenced><mrow>
                <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>2,1</mn></msub>
                <mo>+</mo>
                <msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub>
              </mrow></mfenced>
            </mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mfrac>
            <mrow><mo>-</mo><mfenced><mrow><msup><mi>x</mi><mo>&prime;</mo></msup><mo>+</mo><msup><mi>J</mi><mo>&prime;</mo></msup></mrow></mfenced></mrow>
            <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
          </mfrac>
          <mo>+</mo><mn>0</mn><mo>-</mo>
          <mfrac>
            <mrow><mo>-</mo><msup><mi>x</mi><mo>&prime;</mo></msup></mrow>
            <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
          </mfrac>
          <mo>-</mo>
          <mfrac>
            <mrow><mo>-</mo><mfenced><mrow><mi>S</mi><mo>-</mo><msup><mi>J</mi><mo>&prime;</mo></msup></mrow></mfenced></mrow>
            <msub><mi>v</mi><mn>rel</mn></msub>
          </mfrac>
          <mo>-</mo><mn>0</mn>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mfrac>
            <msup><mrow><mo>-</mo><mi>J</mi></mrow><mo>&prime;</mo></msup>
            <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
          </mfrac>
          <mo>+</mo>
          <mfrac>
            <mrow><mi>S</mi><mo>-</mo><msup><mi>J</mi><mo>&prime;</mo></msup></mrow>
            <msub><mi>v</mi><mn>rel</mn></msub>
          </mfrac>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>S</mi><msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
                <mo>-</mo>
                <msup><mi>J</mi><mo>&prime;</mo></msup><msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
                <mo>-</mo>
                <msup><mi>J</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>rel</mn></msub>
              </mrow>
              <mrow>
                <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse</mn></msub>
                <msub><mi>v</mi><mn>rel</mn></msub>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
      </mtable>
    </math>

    <p>Substituting in the values that were given:</p>
    <ul>
      <li><math:math><math:mi>J</math:mi><math:mo>=</math:mo><math:mi>S</math:mi><math:mo>=</math:mo><math:mn>2m</math:mn></math:math></li>
      <li>
        <math xmlns="&mathml;">
          <msub><mi>v</mi><mi>rel</mi></msub><mo>=</mo>
          <mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <msub><mi>v</mi><mi>pulse</mi></msub>
          <mo>=</mo><mo>-</mo><mn>1</mn>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <mi>&gamma;</mi><mo>=</mo>
          <mfrac>
            <mn>1</mn>
            <msqrt><mrow>
              <mn>1</mn><mo>-</mo><msup><msub><mi>v</mi><mi>rel</mi></msub><mn>2</mn></msup>
            </mrow></msqrt>
          </mfrac>
          <mo>=</mo>
          <mfrac>
            <mn>1</mn>
            <msqrt><mrow>
              <mn>1</mn><mo>-</mo><msup><mfenced><mrow><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mrow></mfenced><mn>2</mn></msup>
            </mrow></msqrt>
          </mfrac>
          <mo>=</mo><mn>2</mn>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <msup><mi>J</mi><mo>&prime;</mo></msup>
          <mo>=</mo>
          <mfrac><mi>J</mi><mi>&gamma;</mi></mfrac>
          <mo>=</mo>
          <mfrac><mi>J</mi><mn>2</mn></mfrac>
          <mo>=</mo>
          <mn>1m</mn>
        </math>
      </li>
      <li>
        <math xmlns="&mathml;">
          <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mi>pulse</mi></msub>
          <mo>=</mo>
          <msub><mi>v</mi><mi>pulse</mi></msub>
          <mo>-</mo>
          <msub><mi>v</mi><mi>rel</mi></msub>
          <mo>=</mo>
          <mo>-</mo><mn>1</mn><mo>-</mo>
          <mfenced><mrow><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mrow></mfenced>
          <mo>=</mo>
          <mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac><mo>-</mo><mn>1</mn>
        </math>
      </li>
    </ul>

    <math mode="display" xmlns="&mathml;">
      <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow><mn>4</mn><msqrt>3</msqrt><mo>-</mo><mn>4</mn></mrow>
        <mrow><mn>2</mn><msqrt>3</msqrt><mo>-</mo><mn>3</mn></mrow>
      </mfrac>
      <mo>&#x224A;</mo><mn>6.3094m</mn>
    </math>

    <h2 id="proper-time">In which frame is the proper time found?</h2>
    <p>The proper time is the time separation in the frame where the space separation is 0m. Event #3 and #4 occur in the same place in the frame where the rails are at rest. The rails frame is therefore the frame with the proper time.</p>

    <h2 id="frames-ratio">What is the ratio of the times between these frames?</h2>
    <p>According to my figures:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mfrac>
        <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub>
        <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub>
      </mfrac>
      <mo>=</mo>
      <mfrac>
        <mfrac>
          <mrow><mn>4</mn><msqrt>3</msqrt><mo>-</mo><mn>4</mn></mrow>
          <mrow><mn>2</mn><msqrt>3</msqrt><mo>-</mo><mn>3</mn></mrow>
        </mfrac>
        <mfrac>
          <mrow><mn>2</mn><msqrt>3</msqrt><mo>+</mo><mn>2</mn></mrow>
          <msqrt>3</msqrt>
        </mfrac>
      </mfrac>
      <mo>=</mo>
      <mfrac>
        <mrow><mn>2</mn><mfenced><mrow><mn>3</mn><mo>-</mo><msqrt>3</msqrt></mrow></mfenced></mrow>
        <mrow><mn>3</mn><mo>-</mo><msqrt>3</msqrt></mrow>
      </mfrac>
      <mo>=</mo>
      <mn>2</mn>
    </math>

    <h2 id="correct-ratio">What should this ratio be according to the time dilation effect?</h2>
    <p>The relationship between two frames of reference is represented by <math:math><math:mi>&gamma;</math:mi></math:math>:</p>
    
    <math mode="display" xmlns="&mathml;">
      <mtable>
        <mtr>
          <mtd><mo>&Delta;</mo><msup><mi>t</mi><mo>&prime;</mo></msup></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd><mi>&gamma;</mi><mo>&Delta;</mo><mi>t</mi></mtd>
        </mtr>
        <mtr>
          <mtd><mfrac>
            <mrow><mo>&Delta;</mo><msup><mi>t</mi><mo>&prime;</mo></msup></mrow>
            <mrow><mo>&Delta;</mo><mi>t</mi></mrow>
          </mfrac></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd><mi>&gamma;</mi></mtd>
        </mtr>
      </mtable>
    </math>

    <p>This is agrees with the computed value of <math:math><math:mn>2</math:mn></math:math> for <math xmlns="&mathml;"><mfrac><msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub><msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub></mfrac></math>.</p>

    <hr style="width: 75%" />

    <h1 id="further-questions">Further Questions</h1>

    <p>The transmission of electrical impulses through different mediums is generally less than the speed of light. For the next questions, assume that the pulses travel at a speed of .75c in the frame where the rail is at rest.</p>

    <h2 id="slider-speed">At what speed do they travel in the slider frame?</h2>
    <p>This is found using the law of combination of velocities. The velocity of C relative to B:</p>

    <math mode="display" xmlns="&mathml;">
      <msub><mi>v</mi><mn>C,B</mn></msub>
      <mo>=</mo>
      <mfrac>
        <mrow>
          <msub><mi>v</mi><mn>C,A</mn></msub><mo>-</mo><msub><mi>v</mi><mn>B,A</mn></msub>
        </mrow>
        <mrow>
          <mn>1</mn><mo>-</mo><msub><mi>v</mi><mn>B,A</mn></msub><msub><mi>v</mi><mn>C,A</mn></msub>
        </mrow>
      </mfrac>
    </math>
    
    <ul>
      <li><math xmlns="&mathml;">
        <msub><mi>v</mi><mn>pulse,rail</mn></msub><mo>=</mo><mo>-</mo>
        <mfrac><mn>3</mn><mn>4</mn></mfrac>
      </math></li>
      <li><math xmlns="&mathml;">
        <msub><mi>v</mi><mn>slider,rail</mn></msub><mo>=</mo>
        <mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac>
      </math></li>
    </ul>

    <math mode="display" xmlns="&mathml;">
      <mtable columnalign="right center left">
        <mtr>
          <mtd><msub><mi>v</mi><mn>pulse,slider</mn></msub></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <msub><mi>v</mi><mn>pulse,rail</mn></msub><mo>-</mo><msub><mi>v</mi><mn>slider,rail</mn></msub>
              </mrow>
              <mrow>
                <mn>1</mn><mo>-</mo><msub><mi>v</mi><mn>slider,rail</mn></msub><msub><mi>v</mi><mn>pulse,rail</mn></msub>
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
                <mo>-</mo><mfrac><mn>3</mn><mn>4</mn></mfrac><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac>
              </mrow>
              <mrow>
                <mn>1</mn><mo>+</mo>
                <mfrac><mn>3</mn><mn>4</mn></mfrac>
                <mfenced><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo>
            <mfrac>
              <mrow><mn>6</mn><mo>+</mo><mn>4</mn><msqrt><mn>3</mn></msqrt></mrow>
              <mrow><mn>8</mn><mo>+</mo><mn>3</mn><msqrt><mn>3</mn></msqrt></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>&#x0224A;</mo></mtd>
          <mtd><mo>-</mo><mn>.97969</mn></mtd>
        </mtr>
      </mtable>
    </math>

    <h2 id="rail-off">How long is the lamp off as observed in the rail frame?</h2>

    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
          <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub>
            <msub><mo minsize="225%">|</mo><mrow><msub><mi>t</mi><mn>1</mn></msub><mo>=</mo><mn>0</mn></mrow></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>J</mi><msub><mi>v</mi><mn>pulse,rail</mn></msub>
                <mo>-</mo>
                <mi>J</mi><msub><mi>v</mi><mn>rel</mn></msub>
                <mo>-</mo>
                <msup><mi>S</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>pulse,rail</mn></msub>
              </mrow>
              <mrow>
                <msub><mi>v</mi><mn>pulse,rail</mn></msub>
                <msub><mi>v</mi><mn>rel</mn></msub>
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
                <mn>2</mn><mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mn>4</mn></mfrac></mrow></mfenced><mo>-</mo>
                <mn>2</mn><mfenced><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mfenced><mo>-</mo>
                <mn>1</mn><mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mn>4</mn></mfrac></mrow></mfenced>
              </mrow>
              <mrow>
                <mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mn>4</mn></mfrac></mrow></mfenced>
                <mfenced><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow><mn>6</mn><mo>+</mo><mn>8</mn><msqrt><mn>3</mn></msqrt></mrow>
              <mrow><mn>3</mn><msqrt><mn>3</mn></msqrt></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>&#x0224A;</mo></mtd>
          <mtd><mn>3.8124m</mn></mtd>
        </mtr>
      </mtable>
    </math>

    <h2 id="slider-off">How long is the lamp off as observed in the slider frame?</h2>

    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd><msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse,slider</mn></msub></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd><msub><mi>v</mi><mn>pulse,slider</mn></msub><mo>-</mo><msub><mi>v</mi><mn>rel</mn></msub></mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo>
            <mfrac>
              <mrow><mn>6</mn><mo>+</mo><mn>4</mn><msqrt><mn>3</mn></msqrt></mrow>
              <mrow><mn>8</mn><mo>+</mo><mn>3</mn><msqrt><mn>3</mn></msqrt></mrow>
            </mfrac>
            <mo>-</mo><mfenced><mrow><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac>
            <mo>-</mo>
            <mfrac>
              <mrow><mn>6</mn><mo>+</mo><mn>4</mn><msqrt><mn>3</mn></msqrt></mrow>
              <mrow><mn>8</mn><mo>+</mo><mn>3</mn><msqrt><mn>3</mn></msqrt></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mo>-</mo>
            <mfrac>
              <mn>3</mn>
              <mrow><mn>16</mn><mo>+</mo><mn>6</mn><msqrt>3</msqrt></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>&#x0224A;</mo></mtd>
          <mtd><mn>0.1137m</mn></mtd>
        </mtr>
      </mtable>
    </math>

    <math mode="display" xmlns="&mathml;">
      <mtable columnalign="right center left">
        <mtr>
          <mtd>
            <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub>
            <msub><mo minsize="225%">|</mo><mrow><msub><msup><mi>t</mi><mo>&prime;</mo></msup><mn>2</mn></msub><mo>=</mo><mn>0</mn></mrow></msub>
          </mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>S</mi><msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse,slider</mn></msub>
                <mo>-</mo>
                <msup><mi>J</mi><mo>&prime;</mo></msup><msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse,slider</mn></msub>
                <mo>-</mo>
                <msup><mi>J</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>rel</mn></msub>
              </mrow>
              <mrow>
                <msub><msup><mi>v</mi><mo>&prime;</mo></msup><mn>pulse,slider</mn></msub>
                <msub><mi>v</mi><mn>rel</mn></msub>
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
                <mfenced><mn>2</mn></mfenced>
                <mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mrow><mn>16</mn><mo>+</mo><mn>6</mn><msqrt>3</msqrt></mrow></mfrac></mrow></mfenced>
                <mo>-</mo>
                <mfenced><mn>1</mn></mfenced>
                <mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mrow><mn>16</mn><mo>+</mo><mn>6</mn><msqrt>3</msqrt></mrow></mfrac></mrow></mfenced>
                <mo>-</mo>
                <mfenced><mn>1</mn></mfenced>
                <mfenced><mrow><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mrow></mfenced>
              </mrow>
              <mrow>
                <mfenced><mrow><mo>-</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></mrow></mfenced>
                <mfenced><mrow><mo>-</mo><mfrac><mn>3</mn><mrow><mn>16</mn><mo>+</mo><mn>6</mn><msqrt>3</msqrt></mrow></mfrac></mrow></mfenced>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>=</mo></mtd>
          <mtd>
            <mfrac>
              <mrow><mn>12</mn><mo>+</mo><mn>16</mn><msqrt>3</msqrt></mrow>
              <mrow><mn>3</mn><msqrt>3</msqrt></mrow>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd></mtd>
          <mtd><mo>&#x0224A;</mo></mtd>
          <mtd><mn>7.6427</mn></mtd>
        </mtr>
      </mtable>
    </math>
    
    <h2 id="slowed-ratio">What is the ratio of these off times?</h2>

    <math mode="display" xmlns="&mathml;">
      <mfrac>
        <msub><msup><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mo>&prime;</mo></msup><mn>3,4</mn></msub>
        <msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub>
      </mfrac>
      <mo>=</mo>
      <mfrac>
        <mfrac>
          <mrow><mn>12</mn><mo>+</mo><mn>16</mn><msqrt>3</msqrt></mrow>
          <mrow><mn>3</mn><msqrt>3</msqrt></mrow>
        </mfrac>
        <mfrac>
          <mrow><mn>6</mn><mo>+</mo><mn>8</mn><msqrt>3</msqrt></mrow>
          <mrow><mn>3</mn><msqrt>3</msqrt></mrow>
        </mfrac>
      </mfrac>
      <mo>=</mo><mn>2</mn>
      <mo>=</mo><mn>&gamma;</mn>
    </math>

    <hr style="width: 75%" />

    <h2 id="flickerless">How long does the slider need to be to eliminate any flicker?</h2>

    <p>When there is no flicker, the resumption of &epsilon; reaches the bulb before the drop, i.e. <math xmlns="&mathml;"><msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub><mo>&leq;</mo><mn>0</mn></math>.</p>

    <math xmlns="&mathml;" mode="display">
      <mtable columnalign="right center left">
        <mtr>
          <mtd><msub><mrow><mo>&Delta;</mo><mi>t</mi></mrow><mn>3,4</mn></msub></mtd>
          <mtd><mo>&leq;</mo></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd>
            <mfrac>
              <mrow>
                <mi>J</mi><msub><mi>v</mi><mn>pulse</mn></msub>
                <mo>-</mo>
                <mi>J</mi><msub><mi>v</mi><mn>rel</mn></msub>
                <mo>-</mo>
                <msup><mi>S</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>pulse</mn></msub>
              </mrow>
              <mrow>
                <msub><mi>v</mi><mn>pulse</mn></msub>
                <msub><mi>v</mi><mn>rel</mn></msub>
              </mrow>
            </mfrac>
          </mtd>
          <mtd><mo>&leq;</mo></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd>
            <mrow>
              <mi>J</mi><msub><mi>v</mi><mn>pulse</mn></msub>
              <mo>-</mo>
              <mi>J</mi><msub><mi>v</mi><mn>rel</mn></msub>
              <mo>-</mo>
              <msup><mi>S</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>pulse</mn></msub>
            </mrow>
          </mtd>
          <mtd><mo>&leq;</mo></mtd>
          <mtd><mn>0</mn></mtd>
        </mtr>
        <mtr>
          <mtd>
            <mrow>
              <msup><mi>S</mi><mo>&prime;</mo></msup><msub><mi>v</mi><mn>pulse</mn></msub>
            </mrow>
          </mtd>
          <mtd><mo>&geq;</mo></mtd>
          <mtd>
            <mi>J</mi><msub><mi>v</mi><mn>pulse</mn></msub>
            <mo>-</mo>
            <mi>J</mi><msub><mi>v</mi><mn>rel</mn></msub>
          </mtd>
        </mtr>
        <mtr>
          <mtd>
            <mfrac>
              <mrow><mi>S</mi><msub><mi>v</mi><mn>pulse</mn></msub></mrow>
              <mn>&gamma;</mn>
            </mfrac>
          </mtd>
          <mtd><mo>&geq;</mo></mtd>
          <mtd>
            <mi>J</mi>
            <mfenced><mrow><msub><mi>v</mi><mn>pulse</mn></msub><mo>-</mo><msub><mi>v</mi><mn>rel</mn></msub></mrow></mfenced>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mi>S</mi></mtd>
          <mtd><mo>&geq;</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>J</mi><mn>&gamma;</mn>
                <mfenced><mrow><msub><mi>v</mi><mn>pulse</mn></msub><mo>-</mo><msub><mi>v</mi><mn>rel</mn></msub></mrow></mfenced>
              </mrow>
              <msub><mi>v</mi><mn>pulse</mn></msub>
            </mfrac>
          </mtd>
        </mtr>
        <mtr>
          <mtd><mi>S</mi></mtd>
          <mtd><mo>&geq;</mo></mtd>
          <mtd>
            <mfrac>
              <mrow>
                <mi>J</mi>
                <mfenced><mrow><msub><mi>v</mi><mn>pulse</mn></msub><mo>-</mo><msub><mi>v</mi><mn>rel</mn></msub></mrow></mfenced>
              </mrow>
              <mrow>
                <msub><mi>v</mi><mn>pulse</mn></msub>
                <msqrt><mrow><mn>1</mn><mo>-</mo><msup><msub><mi>v</mi><mn>rel</mn></msub><mn>2</mn></msup></mrow></msqrt>
              </mrow>
            </mfrac>
          </mtd>
        </mtr>
      </mtable>
    </math>

    <hr style="width: 75%" />
    <p id="footer">
      <span id="copy">&copy; <a href="mailto:wholcomb@gmail.com">Will Holcomb</a></span>
      <span id="license">
        <a rel="license" href="http://creativecommons.org/licenses/by/2.5/">
          <img alt="Creative Commons License" src="http://creativecommons.org/images/public/somerights20.png" />
        </a>
      </span>
    </p>
  </body>
</html>
