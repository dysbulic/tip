<?php
  require('themes/squiggle/bbcode.php.inc');
  print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
  <head>
    <title><?php print bb_strip($head_title) ?></title>
    <?php print $head ?>
    <?php print $styles ?>

    <!--[if lt IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php print base_path() . (strpos(base_path(), '/') != 0 ? '/' : '') ?>themes/squiggle/ie.css" />
    <![endif]-->
    <script type="text/javascript">/* Needed to avoid Flash of Unstyle Content in IE */</script>
  </head>
  <body>
    <div id="header">
      <!-- <?php if ($logo) { ?><a href="<?php print $base_path ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>" alt="<?php print t('Home') ?>" /></a><?php } ?> -->
      <?php if ($site_name) { ?><h1 class='site-name'><a href="<?php print $base_path ?>" title="<?php print t('Home') ?>"><?php print $site_name ?></a></h1><?php } ?>
      <?php if ($site_slogan) { ?><h2><?php print $site_slogan ?></h2><?php } ?>
      <hr id="namedivider" />
    </div>
    <div id="body">
      <div id="sidebar">
        <div class="pad"></div>
        <?php if ($sidebar_left) { ?><?php print $sidebar_left ?><?php } ?>
      </div>
      <?php print $search_box ?>
      <div id="content">
        <?php print $header ?>
        <!-- <?php print $breadcrumb ?> -->
        <div id="text">
          <h1 class="title"><?php print bb2html($title) ?></h1>
          <div class="tabs"><?php print $tabs ?></div>
          <?php print $help ?>
          <?php print $messages ?>
          <?php print $content; ?>
        </div>
      </div>
    </div>
    <div id="footer">
      <div class="right">
        <a id="xhtml-logo" class="logo" href="http://validator.w3.org/check/referer">
          <span class="wc">W3C</span>
          <span class="html">XHTML 1.0</span>
        </a>
        <a id="css-logo" class="logo" href="http://jigsaw.w3.org/css-validator/check/referer">
          <span class="wc">W3C</span>
          <span class="css">CSS 2.0</span>
        </a>
      </div>
      <div class="left">
        <?php print $footer_message ?>
      </div>
    </div>
  </body>
</html>
<!--
  <?php if ($mission) { ?><div id="mission"><?php print $mission ?></div><?php } ?>
  <?php if ($sidebar_right) { ?><?php print $sidebar_right ?><?php } ?>
  <?php print $closure ?>
-->
