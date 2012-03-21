<?php
function printListing($podcasts) {
  global $mosConfig_live_site, $mainframe;
?>
<h1>Discover New Music</h1>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean sit amet urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Aenean fermentum. Mauris sed massa in lorem ultrices tincidunt. Fusce consectetuer fringilla ante. Duis nonummy pellentesque erat. Ut eget mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque malesuada iaculis quam. Quisque a augue. Vestibulum dolor. Phasellus laoreet mollis justo. Maecenas consequat tempus diam. Sed in quam nec pede adipiscing elementum. Suspendisse vitae sapien non neque dignissim accumsan. Aliquam ut lacus ut lorem viverra pellentesque. Integer molestie placerat nulla.</p>
<ul id="archive">
<?php
    $templatepath = $mosConfig_live_site . '/templates/' . $GLOBALS['cur_template'];
    $mainframe->addCustomHeadTag(sprintf('<link href="%s/css/listing.css" rel="stylesheet" type="text/css" />', $templatepath));
    for($i = 0; $i < count($podcasts); $i++) {
      $podcast = $podcasts[$i];
      printf('<li id="%s">' . "\n", $podcast->channelAbbr);
?>
        <h2><?php printf('<a href="%s">%s</a>', sefRelToAbs(sprintf('index.php?option=com_podcast&amp;channel=%s', $podcast->channelAbbr)),
                                                $podcast->channel) ?></h2>
    <ul>
    <li class="artist">
      <?php printf('<a href="%s">%s</a>', sefRelToAbs(sprintf('index.php?option=com_podcast&amp;castid=%d', $podcast->id)),
                                          $podcast->artist) ?>
    </li>
    <li class="title">
      <?php printf('<a href="%s">%s</a>', sefRelToAbs(sprintf('index.php?option=com_podcast&amp;castid=%d', $podcast->id)),
                                          $podcast->title) ?>
    </li>
    <li class="player">
      <?php printf('<object data="%s/flash/player.swf" type="application/x-shockwave-flash" height="20" width="200">' . "\n",
                   $templatepath) ?>
      <?php printf('<param value="%s/flash/player.swf" name="movie" />' . "\n", $templatepath) ?>
      <?php printf('<param value="playerID=1&amp;soundFile=%s" name="FlashVars" />' . "\n", $podcast->show_url) ?>
      <param value="high" name="quality" />
      <param value="false" name="menu" />
      <param value="transparent" name="wmode" />
      <?php print '</object>' . "\n" ?>
    </li>
    </ul>
    </li>
<?php } ?>
</ul>
<?php } ?>
