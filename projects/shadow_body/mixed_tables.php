<?php
 if(!isset($_GET['noxmldecl']))
   print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
 if(!isset($_GET['nodoctype'])) {
   print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"' . "\n" . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n");
 }
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Drop Shadows with Table Layout and Cropped Overflows</title>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <style type="text/css">
      /* This is what actually sets up the layout. */

      .body { display: table; }

      <?php if(isset($_GET['maintable'])) { ?>
        #maincrop { display: table; }
      <?php } else { ?>
        #maincontent { display: table; border-bottom: 1px solid; border-top: 1px solid; }
        #maincrop, .header, .footer { display: table-row; }
      <?php } ?>

      .top, #shadowcrop, .bottom { display: table-row; }
      .left, .right, #navigation, #content, #jump { display: table-cell; }

      /* This establishes the shadows on the main body */
      .left { width: 10px; }
      .top div { height: 10px; }
      .right { width: 15px; }
      .bottom div { height: 15px; }

      /* In IE, the order of class elements matters, .left.shadow will override other .shadow
      * elements, but .shadow.left will not.
      */
      .shadow.left { background: transparent url('shadow_left.png') repeat-y left top; }
      .shadow.right { background: transparent url('shadow_right.png') repeat-y right top; }
      .shadow.top .left { background: transparent url('shadow_blur_ball.png') no-repeat left top; }
      .shadow.top .center { background: transparent url('shadow_top.png') repeat-x left top; }
      .shadow.top .right { background: transparent url('shadow_blur_ball.png') no-repeat right top; }
      .shadow.bottom .left { background: transparent url('shadow_blur_ball.png') no-repeat left -35px; } /* bottom is off in IE */
      .shadow.bottom .center { background: transparent url('shadow_bottom.png') repeat-x left top;  }
      .shadow.bottom .right { background: transparent url('shadow_blur_ball.png') no-repeat right -35px; }
    </style>
    <style type="text/css">
      /* Various colors and widths and whatnot */

      body { font-size: 13pt; background-color: white; }
      .body { width: 80%; margin: auto; }
      #maincrop { background-color: #EEF; }
      #navigation { border-right: 2px solid #353; }
      #jump:hover { background-color: #FDD; }
      #navigation:hover, #content:hover { background-color: #FEE; }
      .header { background-color: #DFD; }
      .footer { background-color: rgb(255, 239, 185); }
      .header, .footer { text-align: center; padding: .5em; }
      .header h1 { margin: 0; }

      #navigation a { display: block; font-weight: bold; font-size: 120%; padding: 5px .5em 5px 1.5em;
                      text-decoration: none; text-indent: -1em; }
      #navigation a:hover { border: 2px solid; border-style: solid none; padding: 3px .5em 3px 1.5em;
                            background-color: #CCF; }

      #jump ul { list-style: none; margin: 1em 0 0 1em; padding: 0; }
      #jump ul ul { margin: 0; }
      #jump a { text-decoration: none; font-size: 120%; display: block; padding: 5px 7px; }
      #jump a:hover { padding: 3px 5px 3px 3px; border: 2px solid; border-left-width: 4px; background-color: #CFC; }
      #jump li li { margin: 0; margin-left: 1em; }

      #navigation { width: 200px; }
      #jump { width: 22%; }
      .main, #maincrop { border: 1px solid; }
      #maincrop { border-style: solid none solid none; }
      
      #content, #jump { padding: .75em; }
      #jump { padding-left: 0; 
              background: #DDF url('left_curved_shadow.png') repeat-y;
              border-left: 1px solid #335; }
      
      .footer { padding: .5em 3em; }
      .footer img { border: none; }
      .footer:after { content: ''; display: block; clear: both; }
    </style>

    <!--[if lt IE 8]>
    <style type="text/css">
      #navigation, #content, .left { float: left; }
      #jump, .right { float: right; }
      .top, #shadowcrop, .bottom { clear: both; }

      /* for IE, all of the widths need to be specified since the width + padding for
       * all the columns needs to add up to 100%
       */
      #content { width: 50%; }
      #jump { width: 20%; }
      #content, #navigation, #jump { padding: 10px 2.5%; }
      #navigation { width: 20%; padding-left: 0; padding-right: 0; }

      #content { margin-right: -2px; margin-left: -2px; } /* to compensate for the borders */

      #maincrop, .center, .main { float: left; width: 100%; }
    </style>
    <![endif]-->
    <!--[if lt IE 7]>
    <style type="text/css">
      body { text-align: center; }
      .body { text-align: left; }

      .body { padding-right: 25px; }
      .left, .right { margin: 0; }

      #jump a, .footer { zoom: 1; } /* fixes an IE6 spacing bug */

      .shadow.left, .shadow.right, #navigation, #content, #jump
        { padding-bottom: 3000px; margin-bottom: -2990px; }
      .shadow.left, .shadow.right { margin-bottom: -3000px; }
      #content { width: 55%; }
    </style>
    <![endif]-->
    <!--[if IE 7]>
    <style type="text/css">
      .body { padding-left: 10px; padding-right: 15px; }
      .left { margin-left: -10px; }
      .right { margin-right: -15px; }

      #content, #maincrop, .footer { min-height: 0; } /* clear after floats */

      /* The right column is wrapped to the next line at certain dimensions without it: rounding error? */
      #jump { margin-left: -1px; }

      .main { margin: 0 0 0 -2px; /* prevents shadow covering the border */ }

      /* This is tricky. In standards compliant mode, borders and padding increase the size of the box.
       * It the assigned height is higher than the containing box, they will cause it to expand which
       * will cause a reevaluation of this statement which will loop the process (forever).
       *
       * In this case there's 10px of padding on the top and bottom as well as two 1px borders for a
       * total of 22px.
       */
      #jump,  #navigation, #content, .shadow.left, .shadow.right {
        height: expression(heightOf(this));
      }
    </style>
    <script type="text/javascript" src="ie7_triple_columns.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="top" class="body">
      <div class="top shadow"><div class="left"><!----></div><div class="center"><!----></div><div class="right"><!----></div></div>
      <div id="shadowcrop">
        <div id="lshadow" class="left shadow"><!----></div>
        <div class="main">
          <div class="header"><h1>Uniform Height Columns</h1></div>
          <div id="maincrop">
            <?php if(!isset($_GET['maintable'])) { ?>
            <div id="maincontent">
            <?php } ?>
            <div id="navigation">
              <a href="#top">Top</a>
              <a href="#intro">Introduction</a>
              <a href="#standards">Standards Compliant</a>
              <a href="#ie6">IE 5.5/6</a>
              <a href="#ie7">IE 7</a>
              <a href="#quirksmode">Quirks Mode</a>
            </div>
            <div id="content">
              <p id="intro">This is a hodgepodge to try and get several things working simultaneously:</p>
              <ul>
                <li>Support for the browsers that have at least 1% share in the server logs: IE6, IE7, Firefox</li>
                <li>If possible, support other browsers, in order of precedence: IE5, Safari, Opera</li>
                <li>Three columns of equal height:
                <ul>
                  <li>The ability to change column widths using only CSS</li>
                  <li>Column reordering is unimportant</li>
                </ul>
                </li>
                <li>Drop shadows around the entire layout</li>
                <li>Relative links within the layout should not cause <a href="https://bugzilla.mozilla.org/show_bug.cgi?id=325942">cutoff issues</a></li>
              </ul>

              <hr />

              <h2 id="standards">Standards Compliant</h2>
              <p>The standards compliant version of this page just uses a CSS <code>display</code> of <code>table</code> to make the three columns. The drop shadow is a containing table. This works in:</p>
              <ul>
                <li>Firefox 1.5.0.6</li>
                <li>Opera 9.00</li>
                <li>Safari 2.0.3</li>
              </ul>
              
              <h2 id="ie">Internet Explorer</h2>

              <p>Internet Explorer doesn't support <code>display: table</code>. So, instead everything is floated and trickery is done to make things render correctly.</p>

              <h3 id="ie6">IE5.5 and IE6</h3>
              
              <p>IE5/6 uses the technique from <a href="http://positioniseverything.net/articles/onetruelayout/">One True Layout</a> of setting an absurd amount of padding on the columns and then an opposite negative bottom margin. This causes the columns to extend below the end of their container. Normally you would have to specify <code>overflow: hidden</code> to cause that content not to display, but IE6 does it by default. In fact, I don't know how to avoid it.</p>

              <p>Things are a little tricky with the shadows as well. In the original layout, the left column is a fixed pixel width, the right column is a percentage and the center fills the rest. I do not know how to do this with floats with a document order of 123 (it is easily done with document order 132) without using absolute positioning. (Absolute positioning will break the overflow trick.)</p>
              
              <p>For the reason the sizes had to be altered slightly and everything specified as percentages. (Since the parent is a percentage, it isn't possible to use pixel widths.)</p>

              <p>This layout mostly works. It relies on quirks mode which may make it unsuitable for some uses. There is also about 5px of overflow of the shadows at the bottom of the page:</p>

              <ul>
                <li>Internet Explorer 6sp1b (from <a href="http://browsers.evolt.org/?ie/32bit/standalone">evolt</a>)</li>
                <li>Internet Explorer 5.5sp2 (from <a href="http://browsers.evolt.org/?ie/32bit/standalone">evolt</a>)</li>
              </ul>
              
              <h3 id="ie7">IE7</h3>
              
              <p>IE7 correctly implements <code>overflow</code>, so the long columns can't be used anymore. Setting <code>overflow: hidden</code> isn't an option because it will break relative links. So, this uses another trick from <a href="http://positioniseverything.net/articles/onetruelayout/example/equalheightanchorsiefix">Position Is Everything</a>. The heights are set in CSS with an IE proprietary <code>expression</code> statement.</p>

              <p>This statement was very volatile. If it was a pixel too large it would cause the containing block to expand and reevaluate the statement. This would then be off by another pixel which would set the loop going forever and lock the browser. Likewise, if it was too small the loop would shrink the container to nothing. Even if the page loaded correctly, resizing the window could set off this behavior (I assume from a rounding issue).</p>

              <p>My fix expands the expression statement to <a href="ie7_triple_columns.js">full-blown javascript</a>, but it seems to avoid the resizing issues. It inserts a class attribute, <code>height-processed</code>, to signify that the statement should not be reevaluated. When the page is resized, elements with that class are adjusted. It is more complex than is desirable, but resizing the page can't cause the browser to lock which I see as worth it.</p>

              <p>This works in:</p>

              <ul>
                <li>Internet Explorer 7.0RC1</li>
              </ul>

              <h2 id="quirksmode">Quirks Mode</h2>

              <p>Quirks mode on different browsers cause this to render in different ways. This document has an <code>&lt;?xml ?></code> declaration and a doctype. This should only trigger quirks mode in IE6. To see how this document renders, this document can be shown:</p>

              <form action="" method="get">
                <div>
                  <label>Without Doctype</label>
                  <?php print '<input type="checkbox" name="nodoctype"' . (isset($_GET['nodoctype']) ? ' checked="checked"' : '') . ' value="" />'?>
                </div>
                <div>
                  <label>Without &lt;?XML ?> Declaration</label>
                  <?php print '<input type="checkbox" name="noxmldecl"' . (isset($_GET['noxmldecl']) ? ' checked="checked"' : '') . ' value="" />'?>
                </div>
                <div><input type="submit" value="View" /></div>
              </form>
              
              <hr />

              <div style="width: 50%; border: 1px solid">width: 50%</div>
              <div style="width: 50%; padding: 0 10% 0 10%; border: 1px solid">width: 50%; padding: 0 10% 0 10%</div>
              <p>If the two divs are the same width, IE is in quirks mode.</p>

              <h2 id="firefoxbug">Weird Firefox Bug</h2>

              <p>I wouldn't believe this was actually happening, had I not tested it repeatedly. When I open this page served from my <a href="http://odin.himinbi.org">webserver</a>, the right-hand column is wrapped to the next line in standards compliant mode in OSX and ok in quirks mode. It looks ok in both modes for me in XP. In Linux it is wrapped in both but not when served by <a href="http://httpd.apache.org">Apache</a> on the box. (All three servers: work, home and web are running Apache 2.) <a href="http://www.doomfire.net/brett/">My brother</a> says that it wraps for him in XP until he opens the <a href="http://chrispederick.com/work/webdeveloper/">developers tools</a>, it pops into place.</p>
              <p>Honestly, I've never seen anything like it. It can be fixed though by making the container for the columns <code>display: table-row</code> rather than <code>table</code>. This, however, causes the header to display below the body in Safari.</p>
              <?php if(isset($_GET['maintable'])) { ?>
              <p>The page is currently using <code>display: table</code>, but the page can also use <?php print '<a href="' . $_SERVER['SCRIPT_NAME'] . '">' ?><code>display: table-row</code><?php print '</a>' ?>.</p>
              <?php } else { ?>
              <p>The page is currently using <code>display: table-row</code>, but the page can also use <?php print '<a href="' . $_SERVER['SCRIPT_NAME'] . '?maintable=">' ?><code>display: table</code><?php print '</a>' ?>.</p>
              <?php } ?>
              <hr />

              <h2 id="source">Source</h2>
              <p>This page includes some basic PHP to handle testing conditions. <?php print '<a href="' . $_SERVER['SCRIPT_NAME'] . 's">' ?>The source is available.<?php print '</a>' ?></p>
            </div>
            <div id="jump">
              <ul>
                <li><a href="../">Odin</a>
                <ul>
                  <li><a href="../sidebar_rotate/">Sidebar Rotation</a></li>
                  <li><a href="../covers/">Debian Cover</a></li>
                  <li><a href="../tree_view/">Tree Menu</a></li>
                  <li><a href="../alpha_link_hover/">Alpha Rollover</a></li>
                </ul>
                </li>
                <li><a href="mailto:Will%20Holcomb%20%3Cwholcomb@gmail.com%3E">E-Mail</a></li>
                <li><a href="http://madstones.com">Mad Stones</a></li>
              </ul>
            </div>
            <?php if(!isset($_GET['maintable'])) { ?>
            </div>
            <?php } ?>
          </div>
          <div class="footer">
            <div style="float: left; margin-top: .5em;">&copy; 2006 <a href="http://www.himinbi.org">Will Holcomb</a></div>
            <div style="float: right">
              <a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://www.w3.org/Icons/valid-css" alt="CSS" /></a>
              <a href="http://validator.w3.org/check/referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="XHTML" /></a>
            </div>
          </div>
        </div>
        <div class="right shadow"><!----></div>
      </div>
      <div class="bottom shadow"><div class="left"><!----></div><div class="center"></div><div class="right"><!----></div></div>
    </div>
  </body>
</html>
