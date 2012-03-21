<?php
function printPodcastInfo($podcast) {
  global $mosConfig_live_site;
  $templatepath = $mosConfig_live_site . '/templates/' . $GLOBALS['cur_template'];
?>
<?php printf('<ul id="cast-%d">' . "\n", $podcast->id) ?>
  <li><?php printf('<img title="View More Info" src="%s" alt="%s - %s" />',
                   $podcast->graphic, $podcast->artist, $podcast->title) ?></li>
  <li><span class="title">Title:</span> <?php print $podcast->title ?></li>
  <li><span class="artist">Artist:</span> <?php print $podcast->artist ?></li>
  <li><?php print $podcast->air_date ?></li>
   <?php
   $rank = $podcast->rating == undefined ? 0 : $podcast->rating;
   printf('<li class="rating" title="%0.2f/%d">', $rank, Podcast::$maxRating);
   for($i = .5; $i <= $rank; $i++) {
     print '&#x2605;';
   }
   for($i = max($i, 1); $i <= 5; $i++) {
     print '&#x2606;';
   }
   print '</li>';
   ?>
  <li>
    <?php printf('<object data="%s/flash/player.swf" type="application/x-shockwave-flash" height="24" width="600">' . "\n",
                 $templatepath) ?>
    <?php printf('<param value="%s/flash/player.swf" name="movie" />' . "\n", $templatepath) ?>
    <?php printf('<param value="playerID=1&amp;soundFile=%s" name="FlashVars" />' . "\n", $podcast->show_url) ?>
    <param value="high" name="quality" />
    <param value="false" name="menu" />
    <param value="transparent" name="wmode" />
    <?php print '</object>' . "\n" ?>
  </li>
  <li><span class="album">Album:</span> <?php printf('<a href="%s">%s</a></li>', $podcast->album_url, $podcast->album) ?></li>
  <li><span class="label">Label:</span> <?php printf('<a href="%s">%s</a></li>', $podcast->label_url, $podcast->label) ?></li>
  <li>
    <ul class="share">
      <li><a href="http://del.icio.us"><img src="http://www.addthis.com/images/delicious.png" alt="Delicious" title="Delicious" /></a></li>
      <li><a href="http://digg.com"><img src="http://www.addthis.com/images/digg.png" alt="Digg" title="Digg" /></a></li>
      <li><a href="http://reddit.com"><img src="http://www.addthis.com/images/reddit.gif" alt="Reddit" title="Reddit" /></a></li>
      <li><a href="http://stumbleupon.com"><img src="http://www.addthis.com/images/su.png" alt="StumbleUpon" title="StumbleUpon" /></a></li>
      <li><a href="http://technorati.com"><img src="http://www.addthis.com/images/technorati.png" alt="Technorati" title="Technorati" /></a></li>
      <li><a href="http://www.addthis.com"><img src="http://www.addthis.com/images/plus-16x16-light.gif" alt="AddThis" title="AddThis" /></a></li>
    </ul>
  </li>
  <?php foreach($podcast->comments as $comment) { ?>
    <li><span class="label">Comments:</span> <?php printf('<a href="">%s</a></li>', $comment) ?></li>
  <?php } ?>
</ul>
<?php } ?>