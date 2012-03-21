<?php
 if(isset($_GET['xmldecl']))
   print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
 if(isset($_GET['doctype'])) {
   print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"' . "\n" . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n");
 }
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Drop Shadows with Cropped Overflows</title>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <style type="text/css">/*<![CDATA[*/
      /* html, body { height: 100%; margin: 0; overflow: hidden; } */
      body { text-align: center; font-size: 13pt; background-color: white; }
      .body { text-align: left; width: 80%; margin: auto; }
      
      .left { width: 10px; }
      .top div { height: 10px; }
      .right { width: 15px; }
      .bottom div { height: 15px; }

      .left { float: left }
      .right { float: right }
      .main { margin: 0 15px 0 10px; }

      .navigation, .jump, .content { padding: 10px; }
      .maincrop { background-color: #EEF; }
      .navigation { border-right: 2px solid #353; margin-right: 10px; }
      .jump { margin-left: 10px; padding-left: 25px; 
              background: #DDF url('left_curved_shadow.png') repeat-y;
              border-left: 1px solid #335; }

       .jump:hover { background-color: #FDD; }
       .navigation:hover, .content:hover { background-color: #FEE; }

      .navigation { width: 200px; float: left; }
      .content { margin-left: 220px; margin-right: 25%; padding-right: 35px; }
      .jump { width: 25%; float: right; }

       .main, .maincrop { border: 1px solid; }
       .maincrop { border-style: solid none solid none; }

       .header, .footer { text-align: center; padding: .5em; }
       .header { background-color: #DFD; }
       .footer { background-color: rgb(255, 239, 185); }

      .overflowcrop:after, .maincrop:after { content: ""; display: block; height: 0; clear: both; }
      .shadow.left, .shadow.right, .navigation, .jump
        { padding-bottom: 3000px; margin-bottom: -2990px; }

      /* In IE, the order of class elements matters, .left.shadow will override other .shadow
      * elements, but .shadow.left will not.
      */
      .shadow.left { background: transparent url('shadow_left.png') repeat-y left top; }
      .shadow.right { background: transparent url('shadow_right.png') repeat-y right top; }
      .top.shadow .left { background: transparent url('shadow_blur_ball.png') no-repeat left top; }
      .shadow.top .center { background: transparent url('shadow_top.png') repeat-x left top; }
      .shadow.top .right { background: transparent url('shadow_blur_ball.png') no-repeat right top; }
      .shadow.bottom .left { background: transparent url('shadow_blur_ball.png') no-repeat left -35px; } /* bottom is off in IE */
      .shadow.bottom .center { background: transparent url('shadow_bottom.png') repeat-x left top;  }
      .shadow.bottom .right { background: transparent url('shadow_blur_ball.png') no-repeat right -35px; }
      .left, .right, .center { overflow: hidden; }
    /*]]>*/</style>
    <!--[if lt IE 7]><![if ! IE]><![endif]-->
    <style type="text/css">
      .overflowcrop, .maincrop { overflow: hidden; }
    </style>
    <!--[if lt IE 7]><![endif]><![endif]-->
    <!--[if lt IE 7]>
    <style type="text/css">
      .jump { background-color: #E2E2E2 }
      .center { margin: 0 15px 0 10px; }
<?php if(!isset($_GET['noxmldecl'])) { ?>
      /* This is only included in the version with an XML declaration */
      .maincrop { height: 0; }
<?php } ?>
    </style>
    <![endif]-->
  </head>
  <body>
    <div class="body">
      <div class="top shadow"><div class="left"></div><div class="right"></div><div class="center"></div></div>
      <div class="overflowcrop">
        <div class="left shadow"></div>
        <div class="right shadow"></div>
        <div class="main">
          <div class="header">Header</div>
          <div class="maincrop">
            <div class="navigation">This is the navigation section.</div>
            <div class="jump">These are the jump points.</div>
            <div class="content">
              <h1>Uniform Height Columns</h1>
              <p>This is a test of uniform height columns.</p>
              <hr />

              <p>This is the actual content of the layout. Normally this will be the longest column, but not necessarily so&hellip;</p>
              <p>This has been tested in several browsers and generally seems to work with the text wrapping and column heights and whatnot:</p>
              <ul>
                <li class="ok">Firefox 1.5</li>
                <li class="ok">Internet Explorer 6.0</li>
                <li>Opera 8.54: the <code>overflow: hidden</code> is ignored</li>
                <li class="ok">Safari 2.0.3</li>
              </ul>
              <p>I can never tell with <acronym title="Internet Explorer">IE</acronym> if it is in quirks mode or not.</p>
              <div style="width: 50%; border: 1px solid">width: 50%</div>
              <div style="width: 50%; padding: 0 10% 0 10%; border: 1px solid">width: 50%; padding: 0 10% 0 10%</div>
              <p>If the two divs are the same width, it&apos;s in quirks mode. Two notes:</p>
              <ul>
                <li>IE6 goes into quirks mode with no doctype or an initial <code>&lt;?xml ?></code> declaration. Even if that declaration is commented out.</li>
                <li>IE7 goes into quirks mode with only with no doctype.</li>
                <li>
                  This document can be seen with:
                  <form action="" method="get">
                    <div>
                      <label>Doctype</label>
                      <?php print '<input type="checkbox" name="doctype"' . (isset($_GET['doctype']) ? ' checked="checked"' : '') . '" />'?>
                    </div>
                    <div>
                      <label>XML Declaration</label>
                      <?php print '<input type="checkbox" name="xmldecl"' . (isset($_GET['xmldecl']) ? ' checked="checked"' : '') . '" />'?>
                    </div>
                    <div><input type="submit" value="View" /></div>
                  </form>
                </li>
              </ul>
              <hr />
              <a name="rellink" id="rellink"></a>
              <h2>Issues with Relative Links</h2>
              <p>One problem with this layout is relative links. When a <a href="#rellink">relative link</a> is used, it should cause the page to scroll to that point. Instead, because of <code>overflow: hover</code>, the top of the page is cropped.</p>
            </div>
          </div>
          <div class="footer">Footer</div>
        </div>
      </div>
      <div class="bottom shadow"><div class="left"></div><div class="right"></div><div class="center"></div></div>
    </div>
  </body>
</html>
