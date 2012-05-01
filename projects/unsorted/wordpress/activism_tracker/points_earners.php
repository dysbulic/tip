<?php
header('Content-type: image/svg+xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="no"?' . ">\n";

$show_title = (strpos($_SERVER['REQUEST_URI'], "notitle") === False);
print "<!-- " . ($show_title ? "yes" : "no") . ": " . strpos("notitle", $_SERVER['REQUEST_URI']) . " -->\n";
print "<!-- " . __FILE__ . " -->\n";

global $wpdb;
if(!isset($wpdb)) {
  if(file_exists('../../../wp-config.php')) {
    @require_once('../../../wp-config.php');
  } elseif(file_exists('../wordpress/wp-config.php')) {
    @require_once('../wordpress/wp-config.php');
  }
}

// From: http://www.actionscript.org/forums/archive/index.php3/t-50746.html
// HSV Values: Number 0-1
// RGB Results:Number 0-255
function HSV_TO_RGB($H, $S, $V) { 
  if($S == 0) {
    return array('red' => $V * 255, 'green' => $V * 255, 'blue' => $V * 255);
  } else {
    $var_H = $H * 6;
    $var_i = floor($var_H);
    $var_1 = $V * (1 - $S);
    $var_2 = $V * (1 - $S * ($var_H - $var_i));
    $var_3 = $V * (1 - $S * (1 - ($var_H - $var_i)));
    
    switch($var_i) {
    case 0: $var_R = $V; $var_G = $var_3; $var_B = $var_1; break;
    case 1: $var_R = $var_2; $var_G = $V; $var_B = $var_1; break;
    case 2: $var_R = $var_1; $var_G = $V; $var_B = $var_3; break;
    case 3: $var_R = $var_1; $var_G = $var_2; $var_B = $V; break;
    case 4: $var_R = $var_3; $var_G = $var_1; $var_B = $V; break;
    default: $var_R = $V; $var_G = $var_1; $var_B = $var_2;
    }
    
    return array('red' => $var_R * 255,
                 'green' => $var_G * 255,
                 'blue' => $var_B * 255);
  }
}

$graph = array('width' => 150, 'height' => 100);
$key = array('width' => 25, 'height' => 30);
$label = array('height' => 15);

$limit = 5;
$title = "Letters to the Editor";
//$elements = array('Test #1' => 30, 'Test #2' => 21, 'Test #3' => 12, 'Test #4' => 3);
$elements = array();

if(isset($_GET['metric'])) {
  $id = $wpdb->escape($_GET['metric']);
} else {
  $query = "SELECT id FROM ${table_prefix}activism_metrics ORDER BY name LIMIT 1";
  $id = $wpdb->get_var($query);
}
$query = sprintf("SELECT name, singular_key, plural_key FROM ${table_prefix}activism_metrics WHERE id = %d", $id);
$title = $wpdb->get_var($query);
$singular_key = $wpdb->get_var($query, 1);
$plural_key = $wpdb->get_var($query, 2);

$query = sprintf("SELECT display_name, sum(points) AS points" .
                 " FROM %sactivism_points JOIN %susers ON user_id = %susers.id" .
                 " WHERE metric_id = '%d'" .
                 " GROUP BY user_id ORDER BY points DESC LIMIT %d",
                 $table_prefix, $table_prefix, $table_prefix, $id, $limit);
$result = $wpdb->get_results($query, ARRAY_N);
for($i = 0; $i < count($result); $i++) {
  $elements[$result[$i][0]] = $result[$i][1];
}
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg xmlns="http://www.w3.org/2000/svg"
 xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
 xmlns:xlink="http://www.w3.org/1999/xlink"
 width="100%" height="100%"
 a:scriptImplementation="Adobe" onload="checkMargins()"
    <?php printf("viewBox='0 0 %s %s'",
                 $graph['width'] + $key['width'],
                 $graph['height'] + $key['height'] + $label['height']); ?>
    version="1.1">
  <defs>
    <style type="text/css">
      #gridlines { fill: #EEE; }
      rect, line { fill: none; stroke: #555; stroke-width: .25; stroke-opacity: .5 }
      text {  fill: black; font-family: "Helvetica", "Bitstream Vera Sans"; }
      #title {
        font-size: 12px; text-align: center; text-anchor: middle;
      }
      #users text, #labels text {
        font-size: 7px; text-align: right; text-anchor: end;
      }
      #bars rect { stroke-width: .5; fill-opacity: .8; }
<?php if(!$show_title) print "#title { display: none; }\n" ?>
<?php
$saturation = .75 + .25 * rand() / getrandmax();
$hue = rand() / getrandmax();
$value = .5 + .5 * rand() / getrandmax();
for($i = 1; $i <= count($elements); $i++) {
  $color = HSV_TO_RGB($hue, $saturation, $value);
  printf("#bar-%s { fill: rgb(%s, %s, %s); }\n", $i,
         round($color['red']), round($color['green']), round($color['blue']));
  $hue += $i / count($elements);
  while($hue > 1) { $hue -= 1; }
}
?>
    </style>
    <script type="text/ecmascript" a:scriptImplementation="Adobe">//<![CDATA[
    function checkMargins() {
      if(typeof(window.parseXML) == "undefined") { // potentially Firefox which has to render once
        setTimeout(function() { reallyCheckMargins(); }, 10);
      } else {
        reallyCheckMargins();
      }
    }
    function reallyCheckMargins() {
      var minX = document.getElementById("users").getBBox().x;
      var viewBox = document.rootElement.getAttribute("viewBox").split(" ");
      if(Math.abs(minX) < 10000000) { // absurdly large in ASV
        viewBox[2] = parseFloat(viewBox[2]) + Math.abs(minX);
        viewBox[0] = minX - viewBox[2] * .05;
        viewBox[2] += viewBox[2] * .1;
        var labelsBounds = document.getElementById("labels").getBBox();
        viewBox[3] = labelsBounds.y + labelsBounds.height + labelsBounds.height * .1;
      } else {
        // Fix in ASV
        viewBox[0] -= 10;
        viewBox[3] *= 1.25;
      }
      document.rootElement.setAttribute("viewBox", viewBox.join(" "));
    }
    //]]></script>
    <linearGradient>
      <stop offset="0" style="stop-color:#4e29de;stop-opacity:0.9;" />
      <stop offset="1" style="stop-color:white;stop-opacity:1;" />
      <stop offset="1" style="stop-color:black;stop-opacity:0;" />
    </linearGradient>
  </defs>
  <g id="gridlines" transform="translate(<?php printf('%s, %s', $key['width'], $label['height']) ?>)">
    <rect x="0" y="0" width="<?php print $graph['width'] ?>" height="<?php print $graph['height'] ?>"/>
    <?php
      // There is a line through each bar and a line above and below
      $lineCount = count($elements) * 2;
      $heightSpace = $graph['height'] / $lineCount;
      for($i = 1; $i <= $lineCount; $i++) {
        $y = $i * $heightSpace;
    ?>
    <line x1="0" y1="<?php print $y ?>" x2="<?php print $graph['width'] ?>" y2="<?php print $y ?>" />
<?php } ?>
<?php
      // I want the spacing to be about the same, but I want an even number of lines
      // ToDo: Add a check to make sure there's at least one element difference in the labels
      //   I.E. No "3 letters" followed by another "3 letters"
      $lineCount = 2 * round($graph['width'] / $heightSpace / 2) - 2;
      $widthSpace = $graph['width'] / $lineCount;
      for($i = 1; $i < $lineCount; $i++) {
        $x = $i * $widthSpace;
?>
    <line x1="<?php print $x ?>" y1="0" x2="<?php print $x ?>" y2="<?php print $graph['height'] ?>" />
<?php } ?>
  </g>
  <text id="title" x="<?php print $key['width'] + $graph['width'] / 2 ?>" y="<?php print $label['height'] * .9 ?>"><?php print $title ?></text>
  <g id="users" transform="translate(<?php printf('%s, %s', $key['width'] * -.1, $label['height'] - $heightSpace * .8) ?>)">
<?php
$count = 0;
foreach($elements as $element => $value) {
  if(!isset($maxValue) || $value > $maxValue) $maxValue = $value;
    $count++;
?>
    <text x="<?php print $key['width'] ?>" y="<?php print $count * $heightSpace * 2 ?>"><?php print $element ?></text>
<?php
}
?>
  </g>
  <g id="bars" transform="translate(<?php printf('%s, %s', $key['width'], $label['height']) ?>)">
<?php
$count = 0;
$barHeight = $heightSpace * 1.1;
foreach($elements as $element => $value) {
  $count++;
?>
    <rect id="<?php print "bar-$count" ?>" x="0" y="<?php print $count * $heightSpace * 2 - $heightSpace * 1 - $barHeight / 2 ?>"
           width="<?php print $graph['width'] * $value / $maxValue ?>" height="<?php print $barHeight ?>" />
<?php } ?>
  </g>
  <g id="labels" transform="translate(<?php printf('%s, %s', $key['width'] * 1.05, $label['height'] * .1) ?>)">
<?php
$maxValue *= 1.1;
for($i = 0; $i <= $lineCount; $i += 2) {
?>
    <text x="0" y="0"
          transform="translate(<?php printf('%s, %s', $i * $widthSpace, $label['height'] + $graph['height']) ?>) rotate(-70)">
    <?php $count = round($i * $maxValue / $lineCount); print $count . " " . ($count == 1 ? $singular_key : $plural_key); ?>
    </text>
<?php } ?>
  </g>
</svg>
