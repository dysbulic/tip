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
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:math="http://www.w3.org/1998/Math/MathML">
  <head>
    <title>Drawing Pretty Lines</title>
    <link rel="stylesheet" href="../styles/main.css" type="text/css" />
    <style type="text/css">
      table {
        margin: auto;
        border-collapse: collapse;
        width: auto;
      }
      td {
        border: thin solid;
        width: 20px;
        height: 25px;
        /* -moz-border-radius: 15px; */
      }
      td:hover {
        background-color: red;
      }
     .filled {
        background-color: blue;
      }
    </style>
    <style type="text/css">
      #curvedisplay {
        position: relative;
        height: 15em;
        border: 2px solid;
      }
      #curvecontrol div {
        display: inline;
      }
      input {
        width: 3em;
      }
      input, select {
        text-align: center;
      }
    </style>
    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript" src="../colortable/colortable.js"></script>
    <script type="text/javascript" src="lines.js"></script>
    <script type="text/javascript" src="draw_bezier.js"></script>
    <script type="text/javascript" src="curve_tester.js"></script>
    <script type="text/javascript">//<![CDATA[
      function loadExamples() {
        var tables = new Array();
        for(var type in {"bounce":0, "touch":1}) {
          tables[type] = makeTable(type, 20, 10);
          eval("if(typeof(" + type + ") == \"function\") new " + type + "(tables[type])");
        }
        control = new CurveControl('curvecontrol', 'curvedisplay');
      }
    //]]></script>
  </head>
  <body onload="loadExamples()">
    <h1>Drawing Pretty Lines</h1>
    <p>I want to redo my homepage and for that project I'd like to be
    able to do a bit of drawing. One simple way to do this is to use
    something like <a
    href="http://www.macromedia.com/software/flash/">Flash</a>, but
    I'd like to keep it in straight HTML if possible. I was thinking
    I'd make some tables and fill the cells. The problem is making
    pretty lines. This page is about that&hellip;</p>
    <hr />
    <p>To start out with, I need some basic functions for making
    tables and filling cells&hellip;</p>
    <div id="bounce"></div>
    <hr />
    <p>The simplest method for drawing lines is to simply plot the
    line through the grid and any square that the line crosses,
    darken.</p>
    <p>The math for this is pretty straightforward. First define a few
    things:</p>
    <ul>
      <li>
        A rectangle <math xmlns="&mathml;"><mi>R</mi></math>
        defined by the upper left corner at
        <math xmlns="&mathml;"><msub><mi>R</mi><mi>t</mi></msub></math>
        and the lower right at
        <math xmlns="&mathml;"><msub><mi>R</mi><mi>b</mi></msub></math>
      </li>
      <li>
        A line <math xmlns="&mathml;"><mi>L</mi></math> defined by
        the two points
        <math xmlns="&mathml;"><msub><mi>P</mi><mi>o</mi></msub></math>
        and
        <math xmlns="&mathml;"><msub><mi>P</mi><mi>f</mi></msub></math>
      </li>
      <li>
        Assuming that <math xmlns="&mathml;"><mi>L</mi></math>
        originates inside <math xmlns="&mathml;"><mi>R</mi></math>
        and exits, there is a single point of intersection,
        <math xmlns="&mathml;"><msub><mi>P</mi><mi>d</mi></msub></math>
      </li>
    </ul>
    <p>If you imagine <math xmlns="&mathml;"><mi>R</mi></math> to
    be embedded in a grid there are eight possible squares that
    <math xmlns="&mathml;"><mi>L</mi></math> could exit into.</p>
    <div style="text-align: center">
      <object style="width: 50%; height: 200px; margin: auto;" type="image/svg+xml" data="rect.svg">
        <img style="height: 200px; margin: auto;" src="rect.png" alt="sectors" />
      </object>
    </div>
    <p>Three of these (6, 7, 8) can be eliminated by guaranteeing that 
      <math xmlns="&mathml;"><msub><mi>P</mi><mi>i</mi></msub></math>
      is to the left of
      <math xmlns="&mathml;"><msub><mi>P</mi><mi>f</mi></msub></math>.
      This is done by simply switching the two if 
      <math xmlns="&mathml;">
        <msub><mi>P</mi><msub><mi>i</mi><mi>x</mi></msub></msub>
        <mo>&gt;</mo>
        <msub><mi>P</mi><msub><mi>f</mi><mi>x</mi></msub></msub>
      </math>
    </p>
    <p>Define
      <math mode="display" xmlns="&mathml;">
        <mi>m</mi>
        <mo>=</mo>
        <mfrac>
          <mi>&Delta;y</mi>
          <mi>&Delta;x</mi>
        </mfrac>
        <mo>=</mo>
        <mfrac>
          <mrow>
            <msub><mi>P</mi><msub><mi>f</mi><mi>y</mi></msub></msub>
            <mo>-</mo>
            <msub><mi>P</mi><msub><mi>i</mi><mi>y</mi></msub></msub>
          </mrow>
          <mrow>
            <msub><mi>P</mi><msub><mi>f</mi><mi>x</mi></msub></msub>
            <mo>-</mo>
            <msub><mi>P</mi><msub><mi>i</mi><mi>x</mi></msub></msub>
          </mrow>
        </mfrac>
      </math>
      and a grid space with lines every
      <math xmlns="&mathml;"><msub><mi>&delta;</mi><mi>x</mi></msub></math>
      and
      <math xmlns="&mathml;"><msub><mi>&delta;</mi><mi>y</mi></msub></math>
      units. A grid square can be identified as
      <math xmlns="&mathml;"><msub><mi>S</mi><mrow><mi>i</mi><mo>,</mo><mi>j</mi></mrow></msub></math>
      where the square is the space bordered by the four lines:
      <math xmlns="&mathml;">
        <mtable>
          <mtr>
            <mtd columnalign="center">
              <mi>x</mi><mo>=</mo><mi>i</mi><msub><mi>&delta;</mi><mi>x</mi></msub>
            </mtd>
          </mtr>
          <mtr>
            <mtd columnalign="center">
              <mi>x</mi><mo>=</mo>
              <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
              <msub><mi>&delta;</mi><mi>x</mi></msub>
            </mtd>
          </mtr>
          <mtr>
            <mtd columnalign="center">
              <mi>y</mi><mo>=</mo><mi>j</mi><msub><mi>&delta;</mi><mi>y</mi></msub>
            </mtd>
          </mtr>
          <mtr>
            <mtd columnalign="center">
              <mi>y</mi><mo>=</mo>
              <mfenced><mrow><mi>j</mi><mo>+</mo><mi>1</mi></mrow></mfenced>
              <msub><mi>&delta;</mi><mi>y</mi></msub>
            </mtd>
          </mtr>
        </mtable>
      </math>
      or, in other words:
      <math xmlns="&mathml;">
        <mfenced><mi>x</mi><mi>y</mi></mfenced>
        <mo>&SuchThat;</mo>
        <mi>i</mi><msub><mi>&delta;</mi><mi>x</mi></msub>
        <mo>&leq;</mo><mi>x</mi><mo>&lt;</mo>
        <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>x</mi></msub>
        <mo>;</mo>
        <mi>j</mi><msub><mi>&delta;</mi><mi>y</mi></msub>
        <mo>&leq;</mo><mi>y</mi><mo>&lt;</mo>
        <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>y</mi></msub>
      </math>
    </p>
    <p>For a given point <math xmlns="&mathml;"><mi>P</mi></math>, 
      <math xmlns="&mathml;">
        <mi>i</mi><mo>=</mo>
        <mfenced open="&lfloor;" close="&rfloor;">
          <mfrac>
            <msub><mi>P</mi><mi>x</mi></msub>
            <msub><mi>&delta;</mi><mi>x</mi></msub>
          </mfrac>
        </mfenced>
      </math>
      and 
      <math xmlns="&mathml;">
        <mi>j</mi><mo>=</mo>
        <mfenced open="&lfloor;" close="&rfloor;">
          <mfrac>
            <msub><mi>P</mi><mi>y</mi></msub>
            <msub><mi>&delta;</mi><mi>y</mi></msub>
          </mfrac>
        </mfenced>
      </math>
    </p>
    <p>To draw my line, I need the set of all
    <math xmlns="&mathml;">
      <msub><mi>S</mi><mrow><mi>i</mi><mo>,</mo><mi>j</mi></mrow></msub>
      <mo>&SuchThat;</mo>
      <mo>&ForAll;</mo>
      <mfenced><mi>x</mi><mi>y</mi></mfenced>
      <mo>&Element;</mo>
      <mover>
        <mrow><msub><mi>P</mi><mi>i</mi></msub><msub><mi>P</mi><mi>f</mi></msub></mrow>
        <mo stretchy="true">¯</mo>
      </mover>
      <mo>&Exists;</mo>
      <msub><mi>S</mi><mrow><mi>i</mi><mo>,</mo><mi>j</mi></mrow></msub>
      <mo>&SuchThat;</mo>
      <mi>i</mi><msub><mi>&delta;</mi><mi>x</mi></msub>
      <mo>&leq;</mo><mi>x</mi><mo>&lt;</mo>
      <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
      <msub><mi>&delta;</mi><mi>x</mi></msub>
      <mo>;</mo>
      <mi>j</mi><msub><mi>&delta;</mi><mi>y</mi></msub>
      <mo>&leq;</mo><mi>y</mi><mo>&lt;</mo>
      <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
      <msub><mi>&delta;</mi><mi>y</mi></msub>
    </math>
    </p>
    <p>I think it would be pretty easy to find iteratively&hellip;
    Let's work first with the case where
    <math xmlns="&mathml;"><mi>m</mi><mo>&lt;</mo><mn>0</mn></math>
    and
    <math xmlns="&mathml;"><mi>m</mi><mo>&notin;</mo><mi>&real;</mi></math>
    since we can already eliminate 6-8 by chosing which point to call
    <math xmlns="&mathml;"><msub><mi>P</mi><mi>i</mi></msub></math>
    this means the ray is going down and right. It will enter 3, 4 or 5 next.
    To figure out which, use <math xmlns="&mathml;"><mi>m</mi></math>.
    </p>
    <ol>
      <li>Start off in
      <math xmlns="&mathml;">
        <msub><mi>S</mi><mrow><mi>i</mi><mo>,</mo><mi>j</mi></mrow></msub>
        <mo>&SuchThat;</mo>
        <mi>i</mi><mi>=</mi>
        <mfenced open="&lfloor;" close="&rfloor;">
          <mfrac>
            <msub><mi>P</mi><msub><mi>i</mi><mi>x</mi></msub></msub>
            <msub><mi>&delta;</mi><mi>x</mi></msub>
          </mfrac>
        </mfenced>
        <mo>;</mo>
        <mi>j</mi><mi>=</mi>
        <mfenced open="&lfloor;" close="&rfloor;">
          <mfrac>
            <msub><mi>P</mi><msub><mi>i</mi><mi>y</mi></msub></msub>
            <msub><mi>&delta;</mi><mi>y</mi></msub>
          </mfrac>
        </mfenced>
      </math>
      </li>
      <li>If
      <math xmlns="&mathml;">
        <msub><mi>P</mi><msub><mi>f</mi><mi>x</mi></msub></msub>
        <mo>&leq;</mo>
        <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>x</mi></msub>
      </math>
      and
      <math xmlns="&mathml;">
        <msub><mi>P</mi><msub><mi>f</mi><mi>y</mi></msub></msub>
        <mo>&leq;</mo>
        <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>y</mi></msub>
      </math>
      then we are at the end of the line and can stop.</li>
      <li>Otherwise, find the intersection of the lines
      <math xmlns="&mathml;">
        <mover>
          <mrow><msub><mi>P</mi><mi>i</mi></msub><msub><mi>P</mi><mi>f</mi></msub></mrow>
          <mo stretchy="true">¯</mo>
        </mover>
      </math>
      and
      <math xmlns="&mathml;">
        <mi>x</mi><mo>=</mo>
        <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>x</mi></msub>
      </math>
      which is
      <math xmlns="&mathml;">
        <mfenced open="&lt;" close="&gt;">
          <mrow>
            <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
            <msub><mi>&delta;</mi><mi>x</mi></msub>
          </mrow>
          <mrow>
            <msub><mi>P</mi><msub><mi>i</mi><mi>y</mi></msub></msub>
            <mo>+</mo>
            <mi>m</mi>
            <mfenced><mrow>
              <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
              <msub><mi>&delta;</mi><mi>x</mi></msub>
              <mo>-</mo>
              <msub><mi>P</mi><msub><mi>i</mi><mi>x</mi></msub></msub>
            </mrow></mfenced>
          </mrow>
        </mfenced>
      </math>
      The important figure is:
      <math xmlns="&mathml;">
        <mrow>
          <msub><mi>P</mi><msub><mi>i</mi><mi>y</mi></msub></msub>
          <mo>+</mo>
          <mi>m</mi>
          <mfenced><mrow>
            <mfenced><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
            <msub><mi>&delta;</mi><mi>x</mi></msub>
            <mo>-</mo>
            <msub><mi>P</mi><msub><mi>i</mi><mi>x</mi></msub></msub>
          </mrow></mfenced>
        </mrow>
        <mo>=</mo>
        <msub><mi>I</mi><mi>y</mi></msub>
      </math>
      and it&apos;s relationship to the bottom border of the rectangle,
      <math xmlns="&mathml;">
        <mi>y</mi><mo>=</mo>
        <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
        <msub><mi>&delta;</mi><mi>y</mi></msub>
      </math>
      The next square is:
      <math xmlns="&mathml;">
        <mrow>
          <mo>{</mo>
          <mtable>
            <mtr>
              <mtd>
                <msub><mi>S</mi><mrow><mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow><mo>,</mo><mi>j</mi></mrow></msub>
              </mtd>
              <mtext>if</mtext>
              <mtd>
                <msub><mi>I</mi><mi>y</mi></msub><mo>&lt;</mo>
                <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
                <msub><mi>&delta;</mi><mi>y</mi></msub>
              </mtd>
            </mtr>
            <mtr>
              <mtd>
                <msub><mi>S</mi><mrow><mi>i</mi><mo>,</mo><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mrow></msub>
              </mtd>
              <mtext>if</mtext>
              <mtd>
                <msub><mi>I</mi><mi>y</mi></msub><mo>&gt;</mo>
                <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
                <msub><mi>&delta;</mi><mi>y</mi></msub>
              </mtd>
            </mtr>
            <mtr>
              <mtd>
                <msub><mi>S</mi>
                <mrow>
                  <mrow><mi>i</mi><mo>+</mo><mn>1</mn></mrow><mo>,</mo>
                  <mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow>
                </mrow>
                </msub>
              </mtd>
              <mtext>if</mtext>
              <mtd>
                <msub><mi>I</mi><mi>y</mi></msub><mo>=</mo>
                <mfenced><mrow><mi>j</mi><mo>+</mo><mn>1</mn></mrow></mfenced>
                <msub><mi>&delta;</mi><mi>y</mi></msub>
              </mtd>
            </mtr>
          </mtable>
        </mrow>
      </math>
      </li>
      <li>Repeat</li>
    </ol>

    <div id="touch"></div>

    <hr />

    <p>The thing is that I was planning on drawing lines by filling in chunks of tables, but I can do it with groups of dots much more easily. What would that look like?</p>

    <div>
      <form action="" id="curvecontrol" onsubmit="return false">
        <div><select name="curve"><option>none</option></select></div>
        <div>
          Rotation:
          <input type="text" name="rotation" value="0" />&deg;
        </div>
        <div>
          Translation: <input type="text" name="transx" value="0" />,
          <input type="text" name="transy" value="0" /></div>
          <div>Scale: <input type="text" name="scalex" value="1" />,
          <input type="text" name="scaley" value="1" /></div>
      </form>
    </div>
    <div id="curvedisplay"></div>
  </body>
</html>
