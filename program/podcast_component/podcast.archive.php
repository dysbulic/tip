<?php
  function printPager($channel, $pageno, $maxpages) {
    print '<ul class="pager">' . "\n";
    if($pageno < $maxpages) {
      printf('<li><a href="%s?page=%d&channel=%s" title="first">&#x21E5;</a></li>', $_SERVER['PHP_SELF'], $maxpages, $channel);
      printf('<li><a href="%s?page=%d&channel=%s" title="first">&#x2192;</a></li>', $_SERVER['PHP_SELF'], $pageno + 1, $channel);
    }
    if($pageno > 1) {
      printf('<li><a href="%s?page=%d&channel=%s" title="first">&#x2190;</a></li>', $_SERVER['PHP_SELF'], $pageno - 1, $channel);
      printf('<li><a href="%s?page=%d&channel=%s" title="first">&#x21E4;</a></li>', $_SERVER['PHP_SELF'], 1, $channel);
    }
    print '</ul>' . "\n";
  }
?>

<?php
function printArchive($podcasts) {
  global $mosConfig_live_site, $mainframe;
?>
<table>
  <tr>
    <th></th>
    <th class="sortable">Date</th>
    <th class="sortable">Title</th>
    <th class="sortable">Artist</th>
    <th class="sortable">Rating</th>
  </tr>

<?php
    $templatepath = $mosConfig_live_site . '/templates/' . $GLOBALS['cur_template'];
    $mainframe->addCustomHeadTag(sprintf('<link href="%s/css/archive.css" rel="stylesheet" type="text/css" />', $templatepath));
    for($i = 0; $i < count($podcasts); $i++) {
      $podcast = $podcasts[$i];
?>
  <tr><!-- class="gcpick"> -->
    <td class="pathholder"></td>
    <td>
      <?php printf('<a href="%s/podcasts/%s">%s</a>', $mosConfig_live_site, $podcast->id,
                   date('d M Y', strtotime($podcast->air_date))) ?>
    </td>
    <td>
      <?php printf('<a href="%s/podcasts/%s">%s</a>', $mosConfig_live_site, $podcast->id, $podcast->title) ?>
    </td>
    <td><?php printf('<a href="%s">%s</a>', $podcast->artist_url, $podcast->artist) ?></td>
    <td class="rating">&#x2605;&#x2605;&#x2605;&#x2606;&#x2606;</td>
  </tr>
  <tr> 
    <td class="thumbnail">
      <?php printf('<img title="View More Info" src="%s" alt="%s - %s" />', $podcast->graphic, $podcast->artist, $podcast->title) ?>
    </td>
    <td colspan="4" class="player">
      <?php printf('<object data="%s/flash/player.swf" type="application/x-shockwave-flash" height="24" width="600">' . "\n",
        $templatepath) ?>
      <?php printf('<param value="%s/flash/player.swf" name="movie" />' . "\n", $templatepath) ?>
      <?php printf('<param value="playerID=1&amp;soundFile=%s" name="FlashVars" />' . "\n", $podcast->show_url) ?>
      <param value="high" name="quality" />
      <param value="false" name="menu" />
      <param value="transparent" name="wmode" />
      <?php print '</object>' . "\n" ?>
      <ul class="details">
        <li>
          <ul class="info">
            <li><span class="album">Album:</span> <?php printf('<a href="%s">%s</a></li>', $podcast->album_url, $podcast->album) ?></li>
            <li><span class="label">Label:</span> <?php printf('<a href="%s">%s</a></li>', $podcast->label_url, $podcast->label) ?></li>
          </ul>
        </li>
<?php if($podcast->gccomment != '') { ?>
        <li class="gccomment">
          <a href="http://guitarcenter.com" title="Guitar Center Picks"><img class="gclogo" src="gc_guitar.png" alt="Guitar Center" /></a>
        </li>
<?php } ?>
        <li>
          <ul class="commentopts">
            <li>
              <?php printf('<a href="%s/podcasts/%s">Permalink</a>', $mosConfig_live_site, $podcast->id) ?>
            </li>
            <li>
              <?php printf('<a href="%s/podcasts/%s">View Comments</a>', $mosConfig_live_site, $podcast->id) ?>
            </li>
            <li>
              <?php printf('<a href="%s/podcasts/%s">Add Comment</a>', $mosConfig_live_site, $podcast->id) ?>
            </li>
          </ul>
        </li>
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
      </ul>
    </td>
  </tr>
<?php } ?>
</table>
<?php } ?>
