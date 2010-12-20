<?php if(isset($_GET['xmldecl'])) print '<?xml version="1.0" encoding="UTF-8" ?' . ">\n" ?>
<?php if(!isset($_GET['nodoctype'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Testing Internet Explorer Reflowing</title>
    <style type="text/css">
      div { border: 2px solid; padding: 1em; }
      
      .body, .centercol { height: 0; }
      .leftcol, .rightcol, .heightmatch, .content { height: 100%; }

      div.body { width: 800px; position: relative; }
      div.leftcol { width: 100px; float: left; }
      div.rightcol { width: 200px; position: absolute; top: 0; right: 0; }
      div.centercol { margin-right: 200px; }
      div.heightmatch { float: right; width: 150px; }
      
      .sample { border: 1px solid; padding: 0 .25em 0 .25em; }

      .body { border-color: #557; background-color: DDF; }
      .leftcol { border-color: #575; background-color: DFD; }
      .rightcol { border-color: #755; background-color: FDD; }
      .centercol { border-color: #678; background-color: CDE; }
      .heightmatch { border-color: #876; background-color: EDC; }
      .content { border-color: #768; background-color: DCE; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Internet Explorer Reflowing</h1>
    <p>Theoretically <acronym title="Internet Explorer">IE</acronym> should reflow boxes that have a height specified, but are too short. I'm finding though that this doesn't seem to work sometimes, so for testing this page can be loaded <?php print '<a href="' . $_SERVER['SCRIPT_NAME'] . '">'?>with a doctype<?php print '</a>' ?> and also <a href="?nodoctype&amp;xmldecl">without</a>. <em>(Thereby changing the standards compliance.)</em></p>
    <?php if(isset($_GET['nodoctype'])) { ?>
      <p><code>DOCTYPE</code> is currently ommited.</p>
    <?php } ?>
    <div style="width: 25%;"><code>&lt;div style="width: 25%" /></code></div>
    <div style="width: 25%; padding: 1em 10% 1em 10%;"><code>&lt;div style="width: 25%; padding: 1em 10% 1em 10%" /></code></div>
    <div class="body">
      <div class="leftcol">This column is <code>float</code>ed to the left. Its height is relatively small&hellip;</div>
      <div class="centercol">This is the center column. The content though is in another <code>div</code>:
      <div class="heightmatch"></div>

        <div class="content">
          <p>This is the actual content. The containing <code>div</code>s on this page,</p>
          <ul>
            <li><code class="body sample">.body</code></li>
            <li><code class="centercol sample">.centercol</code></li>
          </ul>
          <p>have a <code>height</code> of 0. In <acronym title="Internet Explorer">IE</acronym>, this should cause them to reflow around the elements that they contain. The other elements,</p>
          <ul>
            <li><code class="leftcol sample">.leftcol</code></li>
            <li><code class="content sample">.content</code></li>
            <li><code class="heightmatch sample">.heightmatch</code></li>
            <li><code class="rightcol sample">.rightcol</code></li>
          </ul>
          <p>have a <code>height</code> of <code>100%</code>. This should cause them to fill to the height of their containing block,</p>
        </div>
      </div>
      <div class="rightcol">This column is absolutely positioned and the <code>margin-right</code> of its preceding sibling, <code>centercol</code>, is such so as to allow it to be visible.</div>
    </div>
  </body>
</html>
