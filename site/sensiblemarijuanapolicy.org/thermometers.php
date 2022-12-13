<?php
header("Content-Type: text/css");
$image['height'] = 486;
$image['width'] = 130;
$image['pad'] = 114;
$image['active'] = 295;

$min = 0;
$max = 100000;
$val = 0;
?>
.thermometer { position: relative; margin: auto; margin-bottom: 1em; width: 45%; float: left; }
.thermometer h3 { margin-bottom: -.5em; }
.thermometer .therm, .thermometer .inner {
  height: 445px; width: 150px;
  background: transparent url('red_thermometer.png') no-repeat bottom left; }
.thermometer .inner { position: absolute; bottom: 0; background-image: url('fullred_thermometer.png'); }
/*
#money-therm .therm { background-image: url('blue_thermometer.png'); }
#money-therm .inner { background-image: url('fullblue_thermometer.png'); }
*/
.thermometer ul { list-style: none; padding: 0; padding-top: 24px; margin-left: 125px; }
.thermometer .pop ul { padding-top: 62px; }
.thermometer li { height: 50px; min-width: 5em; }

#thermometers:after { content: ''; display: block; clear: both; }

.thermometer .pop { height: 23.5%; }

<?php
require "db_auth_info.php";
mysql_connect($host, $user, $password);
@mysql_select_db($database) or die( "Unable to select database: mysql://$user@$host/$database from " . $_SERVER['REMOTE_ADDR']);

$therms = array("money-therm", "sigs-therm");
foreach($therms as $therm) {
  $query = ('select min, max, value, description from thermometers left join thermometer_values' .
            ' on thermometers.id = thermometer_values.thermometer' .
            " where name = '$therm' order by time desc limit 1");
  $result = mysql_query($query);
  $row = ($result ? mysql_fetch_array($result, MYSQL_NUM) : array("", 0, 1, 0));

  $percent = ($image['pad'] + $row[2] / ($row[1] - $row[0]) * $image['active']) / $image['height'] * 100;
  printf('/* %s: %d / %d */'. "\n", $row[3], $row[2], $row[1] - $row[0]);
  print "#$therm .inner { height: $percent%; }\n";
}

mysql_close();
?>



