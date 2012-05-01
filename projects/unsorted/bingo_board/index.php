<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/* I want for the numbers to be concentrated in ranges on the
 * columns. For example, B would be the lowest numbers and O
 * the highest. This will help speed checking the board. I'd like
 * a little overlap though just to make it a little hard.
 *
 * If there was no overlap I'd just do a range of 20 for each
 * (count(0 - 99) / 5). With some overlap though I need to get
 * a little more complicated because the ranges are:
 *
 * ((max + 1) / divisions) / 2 + k * (max + 1) / divisions + k ± range / 2
 * 0 ≤ k ≤ dividions - 1
 *
 * When range = (max + 1) / divisions this is fine. When range
 * grows you get the overlap, but it starts to push the maximum
 * number produced above max. To deal with this you have to start
 * to shift the midpoints down.
 */
$title = isset($_GET['title']) ? $_GET['title'] : 'BINGO';
$columns = isset($_GET['columns']) ? (int)$_GET['columns'] : strlen($title);
$max = isset($_GET['max']) ? (int)$_GET['max'] : 99;
$range = isset($_GET['range']) ? (int)$_GET['range'] : $max / $columns;
$delta = ($max - $range) / ($columns - 1);
$boardcount = isset($_GET['boardcount']) ? (int)$_GET['boardcount'] : 6;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Bingo Boingo Boards</title>
    <link rel="stylesheet" href="bingo_board.css" type="text/css" />
    <style type="text/css">
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <div id="links">
      <?php printf("<form action='%s'>\n", $_SERVER['SCRIPT_NAME']); ?>
        <ul>
<?php
$params = array('title', 'columns', 'max', 'board count');
for($i = 0; $i < count($params); $i++) {
  $varname = str_replace(' ', '', $params[$i]);
  printf("<li><label>%s</label> <input type='text' name='%s' value='%s' /></li>\n",
         ucwords($params[$i]), $varname, $GLOBALS[$varname]);
}
?>
          <li><input type='submit' value='New Boards' /></li>
        </ul>
      <?php print "</form>\n"; ?>
      <?php printf("<a href='%ss'>Source</a>", $_SERVER['SCRIPT_NAME']); ?>
    </div>
          
        
<?php
for($i = 0; $i < $boardcount; $i++) {
  $parity = $i % 2 == 0 ? 'odd' : 'even';
  printf("<table class='bingoboard %s'>\n", $parity);
  print '<thead><tr>';
  for($row = 0; $row < $columns; $row++) {
    printf("<th>%s</th>", $title[$row % strlen($title)]);
  }
  print '</tr></thead>';
?>


<?php
  if(!isset($used)) $used = array();
  for($j = 0; $j <= $max; $j++) {
    $used[$j] = false;
  }
  print '<tbody>' . "\n";
  for($row = 0; $row < $columns; $row++) {
    print '<tr>';
    for($col = 0; $col < $columns; $col++) {
      if($col == $row && $row == ($columns - $columns % 2) / 2) {
        print '<td class="freesquare">Free</td>';
      } else {
        do {
          $val = round($range / 2 + $col * $delta + mt_rand(-$range / 2, $range / 2));
        } while($used[$val]);
        $used[$val] = true;
        print '<td>' . $val . '</td>';
      }
    }
    print '</tr>' . "\n";
  }
  print '</tbody>' . "\n";
  print '</table>' . "\n";
}
?>
</ul>
  </body>
</html>
