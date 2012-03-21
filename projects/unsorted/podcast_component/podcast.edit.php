<?php
function printEditScreen($podcast) {
  global $mosConfig_live_site, $mainframe;
  $templatepath = $mosConfig_live_site . '/templates/' . $GLOBALS['cur_template'];
  $mainframe->addCustomHeadTag(sprintf('<link href="%s/css/edit.css" rel="stylesheet" type="text/css" />' . "\n", $templatepath));
  $mainframe->addCustomHeadTag(sprintf('<link href="%s/css/jquery-calendar.css" rel="stylesheet" type="text/css" />' . "\n", $templatepath));
  $mainframe->addCustomHeadTag(sprintf('<script src="%s/js/jquery-calendar.pack.js" type="text/javascript"></script>' . "\n", $templatepath));
  $mainframe->addCustomHeadTag(sprintf('<script type="text/javascript">$($(".calendar").calendar());</script>' . "\n", $templatepath));
  $labels = array('artist_url' => 'Artist URL', 'show_url' => 'Show', 'label_url' => 'Label URL', 'channelAbbr' => undefined,
                  'air_date' => 'Date', 'id' => undefined);
  $classes = array('air_date' => 'calendar');
?>
<?php printf('<form action="%s?task=save" method="post">' . "\n", $_SERVER['PHP_SELF']) ?>
<ul>
  <?php
    for($i = 0; $i < count(Podcast::$props); $i++) {
      $cast = Podcast::$props[$i];
      $label = $labels[$cast];
      if(!array_key_exists($cast, $labels)) $label = ucwords($cast);
      if($label == undefined) {
        printf('<input type="hidden" name="%s" value="%s" />' . "\n", $cast, $podcast->$cast);
      } else {
  ?>
    <li>
      <?php printf('<label for="%s">%s: </label>' . "\n", $cast, $label); ?>
      <?php printf('<input type="text" class="%s" name="%s" value="%s"/>' . "\n",
                   array_key_exists($cast, $classes) ? $classes[$cast] : '', $cast, $podcast->$cast == undefined ? '' : $podcast->$cast); ?>
    </li>
  <?php } ?>
  <?php } ?>
  <li><input type="submit" /></li>
</ul>
</form>
<?php
}
?>
