<?php defined('_VALID_MOS') or die('Direct Access to this location is not allowed.') ?>
<?php
  $isos = split('=', _ISO);
  printf('<?xml version="1.0" encoding="%s"?' . ">\n", $isos[1]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <!-- <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script> -->
  <link rel="shortcut icon" href="http://dev.madstones.com/indiefeed/images/ipod.ico" />
<?php
  printf('<meta http-equiv="Content-Type" content="text/html; %s" />', _ISO);
  $templatepath = $mosConfig_live_site . '/templates/' . $GLOBALS['cur_template'];
  $styles = array('site.css', 'header.css', 'corners.css', 'dialog.css');
  for($i = 0; $i < count($styles); $i++) {
    printf('<link rel="stylesheet" href="%s/css/%s" type="text/css" />' . "\n", $templatepath, $styles[$i]);
  }
  $scripts = array('audio-player.js', 'jquery-1.1.3.1.pack.js', 'show_podcast.js',
                   'jquery.dialog.js', 'jquery.corner.js', 'add_rating.js',
                   'make_sortable.js', 'nav_menu.js');
  for($i = 0; $i < count($scripts); $i++) {
    printf('<script src="%s/js/%s" type="text/javascript"></script>' . "\n", $templatepath, $scripts[$i]);
  }
  mosShowHead();
  if($my->id) initEditor();

  if((mosCountModules('user1')) && (mosCountModules('user2'))) {
    //if both modules are loaded, we need a 50%-layout for them
    $usera = 'user1';
    $userb = 'user2';
  } else if((mosCountModules('user1')) || (mosCountModules('user2'))) {
    // if only one, then 100% no matter which one.
    $usera = 'user3';
    $userb = 'user3';
  }
?>
  <!-- <link rel="stylesheet" href="http://dev.jquery.com/view/trunk/themes/flora/flora.all.css" type="text/css" media="screen" title="Flora (Default)" /> -->
    <script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/dimensions/jquery.dimensions.js"></script>
    <script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/ui/ui.mouse.js"></script>
    <script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/ui/ui.slider.js"></script>
    <style type="text/css">
      .player { display: none; }
      .ui-slider-handle { background: black none; border: outset 1px; width: 5px; height: 13px; margin-top: 3px; margin-left: 0px; }
      .ui-slider-1 { background-image: none; width: 200px; }
      .ui-slider-1 img { width: 100%; height: 20px; }
      .newplayer { position: absolute; bottom: 10px; left: 15px; }
    </style>
    <script type="text/javascript">//<![CDATA[
      $().ready(function() {
        $('#login').corner('8px');
        $('#searchform').corner('5px');
        $('#leftmenu > li > span').box();
        $('#archive li ul').box();
        $('.newplayer > div').append('<?php printf('<img src="%s/images/player_bg.png"/>', $templatepath) ?>');
        $('.newplayer > div').slider({
          minValue: 0,
          maxValue: 100
        });
      });
    //]]></script>
    <!--
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
    -->
  </head>
  <body>
    <div id="header">
      <?php printf('<a href="%s"><img alt="indiefeed logo" src="%s/images/indiefeed_logo.white.png" /></a>' . "\n",
                   $mosConfig_live_site, $templatepath); ?>
      <h1 id="indiefeed">
        <?php printf('<a href="%s">indiefeed</a>',  $mosConfig_live_site); ?>
      </h1>
      <ul id="options">
        <li>
          <ul id="navmenu">
            <li id="channels">
              <a href="index.php">Channels</a>
              <ul>
                <li><a href="archive_mockup.html">Alternative / Modern Rock</a></li>
                <li><a href="archive_mockup.html">Blues</a></li>
                <li><a href="archive_mockup.html">Electronica</a></li>
                <li><a href="archive_mockup.html">Hip Hop</a></li>
                <li><a href="archive_mockup.html">Indie-Pop</a></li>
                <li><a href="archive_mockup.html">Performance Poetry</a></li>
                <li><a href="archive_mockup.html">Big Shed Audio-Docs</a></li>
              </ul>
            </li>
            <li><a href="">About</a></li>
            <li><a href="">Contact</a></li>
            <li><a href="">Press Kit</a></li>
            <li><a href="">FAQ</a></li>
          </ul>
        </li>
        <li id="login">
          <ul>
            <li><a href="">Login</a></li>
            <li><a href="">New Account</a></li>
          </ul>
        </li>
        <li id="search">
          <h2>Search the Archive</h2>
          <div id="searchform">
            <form action="index.php?option=com_search" method="get">
              <div>
                <input name="searchword" id="mod_search_searchword"
                       maxlength="20" alt="search" class="inputbox"
                       type="text" size="20" value="search..."
                       onblur="if(this.value=='') this.value='search...';"
                       onfocus="if(this.value=='search...') this.value='';" />
                <input type="hidden" name="option" value="com_search" />
              <input type="hidden" name="Itemid" value="" />
              </div>
            </form>
          </div>
          <?php if(mosCountModules("user4")) { ?>
            <?php mosLoadModules('user4', -1) ?>
          <?php } ?>
        </li>
      </ul>
    </div>
    <ul id="leftmenu">
      <li class="gray">
        <span><a href="">Music Channels</a></span>
        <ul>
          <li><a href="archive_mockup.html">Alternative / Modern Rock</a></li>
          <li><a href="archive_mockup.html">Blues</a></li>
          <li><a href="archive_mockup.html">Electronica</a></li>
          <li><a href="archive_mockup.html">Hip Hop</a></li>
          <li><a href="archive_mockup.html">Indie-Pop</a></li>
          <li><a href="archive_mockup.html">Performance Poetry</a></li>
          <li><a href="archive_mockup.html">Big Shed Audio-Docs</a></li>
        </ul>
      </li>
      <li class="gray">
        <span>Sponsors</span>
        <ul id="sponsors">
          <li><a href=""></a></li>
          <li><a href=""></a></li>
        </ul>
      </li>
    </ul>

    <div id="main">
    <div id="content">
      <?php if(mosCountModules('banner')) { ?>
        <div id="banner">
          <?php mosLoadModules('banner', -1); ?>
        </div>
      <?php } ?>
      <!--
        <div id="pathway">
          <?php mospathway() ?>
        </div>
        <div id="leftcol">
          <?php if(mosCountModules('left')) mosLoadModules('left', -3); ?>
        </div>
      -->
      
        <?php
          if(mosCountModules('user1')) {
            printf('<div id="%s">', $usera);
            mosLoadModules('user1', -2);
            print '</div>';
          }
        ?>
        <?php mosMainBody(); ?>
        </div>
      </div>
      <?php if((mosCountModules('right')) || (mosCountModules('top'))) { ?>
        <div id="rightcol-broad">
          <?php
            mosLoadModules('top', -3);
            mosLoadModules ( 'right',-3);
          ?>
        </div>
      <?php } ?>
    </div>

    <div id="footer" >
      <?php


    printf('&copy; %s %s', mosCurrentDate('%Y'), $GLOBALS['mosConfig_sitename']);
        //include_once($mosConfig_absolute_path . '/includes/footer.php');
        mosLoadModules('debug', -1);
      ?>
    </div>
    </div>
  </div>
</body>
</html>
