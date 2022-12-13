<?php
$xhtml = preg_match("'application/xhtml\+xml'", $_SERVER['HTTP_ACCEPT']);
if($xhtml) {
  header("Content-type: application/xhtml+xml");
 }
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n" ?>

<?php if($xhtml) { ?>
<!-- "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" -->
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd"
 [ <!ENTITY mathml  "http://www.w3.org/1998/Math/MathML">
   <!ENTITY hellip  "&#x2026;">
   <!ENTITY bull    "&#x7E6;">
   <!ENTITY sum     "&#x2211;">
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
   <!ENTITY isin    "&#x2208;">
   <!ENTITY notin   "&#x2209;">
   <!ENTITY real    "&#x211D;"> ]>
<?php } ?>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"
 xmlns:math="http://www.w3.org/1998/Math/MathML">
  <head>
    <link rel="stylesheet" href="../styles/main.css" type="text/css" />
    <link rel="stylesheet" href="../styles/table.css" type="text/css" />
    <title>Graduation Hat</title>
    <style type="text/css">
      .break {
        border: thin solid grey;
      }
      .on {
        background-color: rgb(162, 215, 166);
      }
      .off {
        background-color: rgb(238, 162, 162);
      }
      table {
        width: 50%;
        margin: auto;
      }
      td, th {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <h1>My Hat for Graduation</h1>
    <p>I will be graduating with a degree in Computer Science in about
     a month and looking at my degree now with the knowledge I have, I
     think that had I it to do over again I would have gone the
     Computer Engineering route. Software is entertaining and I really
     enjoy my work, but it is all so intangible. I really would like
     to be able to hold the product of one of my efforts in my
     hand.</p>
    <p>To this end I have been playing with circuits a bit and want to
     make my hat for graduation some sort of electronic display. This
     fits very well for me since I haven't really cared about making
     anything especially <em>useful</em>, just something that exists
     physically and being pretty is a plus.</p>
    <p>My original idea was to do a seven segment led (those blocky
     numbers) and have it counting. This is still what I might end up
     going with, but it has the disadvantage of not being visible from
     a wide angle and not being especially pretty. A friend of mine
     suggested instead that I might try some sort of animated pattern
     and I ended up with concentric squares which works well with the
     shape of the hat.</p>
    <p>One of my design constraints is I would like to have as little
     as possible actually on the hat. I would like the batteries and
     chips to be in my pocket since trying to carry them on my head is
     just not realistic given the weight.</p>
    <p>A single seven segment display works well for this since there
     are seven wires for the segments and then a common ground. A
     piece of cat5 has 8 wires in it and I could buy an RJ-45 female
     jack and put it on the back of the hat and use that to easily
     connect to the rest of it in my pocket.</p>
    <p>When looking at switching to the squares I considered having
     seven squares (one for each wire). The problem is that assuming
     the original square is three LEDs on a side and the subsequent
     squares each add one led to the outside then the number of LEDs
     in a given square would be:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mi>L</mi>
      <mfenced>
        <msub>
          <mi>S</mi>
          <mi>n</mi>
        </msub>
      </mfenced>
      <mo>=</mo>
      <mn>4</mn>
      <mo>*</mo>
      <mn>2</mn>
      <mo>*</mo>
      <mi>n</mi>
      <mo>=</mo>
      <mn>8</mn>
      <mo>*</mo>
      <mi>n</mi>
    </math>
    <p>The total number of LEDs to make a given number of squares then
     would be:</p>
    <math mode="display" xmlns="http://www.w3.org/1998/Math/MathML">
      <mi>T</mi>
      <mfenced>
        <mi>n</mi>
      </mfenced>
      <mo>=</mo>
      <munderover>
        <mo>&sum;</mo>
        <mrow>
          <mi>i</mi>
          <mo>=</mo>
          <mn>1</mn>
        </mrow>
        <mi>n</mi>
      </munderover>
      <mi>L</mi>
      <mfenced>
        <msub>
          <mi>S</mi>
          <mi>n</mi>
        </msub>
      </mfenced>
      <mo>=</mo>
      <munderover>
        <mo>&sum;</mo>
        <mrow>
          <mi>i</mi>
          <mo>=</mo>
          <mn>1</mn>
        </mrow>
        <mi>n</mi>
      </munderover>
      <mn>8</mn>
      <mi>i</mi>
      <mo>=</mo>
      <mn>8</mn>
      <mo>*</mo>
      <munderover>
        <mo>&sum;</mo>
        <mrow>
          <mi>i</mi>
          <mo>=</mo>
          <mn>1</mn>
        </mrow>
        <mi>n</mi>
      </munderover>
      <mi>i</mi>
    </math>
    <p>And for 7 squares, <math:mi>T</math:mi><math:mfenced>
     <math:mn>7</math:mn></math:mfenced><math:mo>=</math:mo>
     <math:mn>224</math:mn>. Which, at ~$.20 per LED is around
     $45.</p>
    <p>So, I decided to make the pattern a little more complex and cut
     back on the number of squares. The new pattern will "walk" its
     way out the the edge and then fall back to the center. The truth
     table for this would be:</p>
    <table>
      <thead>
        <tr>
          <th>P<sub>1</sub></th>
          <th>P<sub>2</sub></th>
          <th>P<sub>3</sub></th>
          <th>P<sub>4</sub></th>
          <th class="break"></th>
          <th>S<sub>1</sub></th>
          <th>S<sub>2</sub></th>
          <th>S<sub>3</sub></th>
          <th>S<sub>4</sub></th>
          <th>S<sub>5</sub></th>
          <th>S<sub>6</sub></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="break"></td>
          <td class="off">0</td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
        <tr>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="on">1</td>
          <td class="break"></td>
          <td class="on">1</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
          <td class="off">0</td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
