<?php 
header("Content-type: text/plain");
$prefix = "wp_";
global $wpdb;
if(isset($wpdb->prefix)) $prefix = $wpdb->prefix;

$metrics = array("Letter to the Editor" => array("letter", "letters"),
                 "Handwritten Letter" => array("letter", "letters"),
                 "Phone Call" => array("call", "calls"));
$users = array('jactivist' => 'Jane Activist', 'jdogood' => 'Johnny Dogooder',
               'smiley' => 'Smiley Happenstance', 'bigpoppa' => 'Poppa Smurf');
?>

INSERT INTO <?php print "${prefix}activism_metrics" ?>(name, singular_key, plural_key) VALUES
<?php
$metricnames = array();
foreach($metrics as $metric => $keys) {
  array_push($metricnames, $metric);
  printf("    ('%s', '%s', '%s')%s\n", $metric, $keys[0], $keys[1],  count($usernames) < count($users) ? "," : ";");
}
?>

INSERT INTO <?php print "${prefix}users" ?>(user_login, display_name) VALUES
<?php
$usernames = array();
foreach($users as $username => $name) {
  array_push($usernames, $username);
  printf("    ('%s', '%s')%s\n", $username, $name, count($usernames) < count($users) ? "," : ";");
}
?>

<?php for($i = 15 + rand(0, 25); $i > 0; $i--) { ?>
INSERT INTO <?php print "${prefix}activism_points" ?>(user_id, metric_id, points)
<?php
  printf(" SELECT %susers.id, %sactivism_metrics.id, %d FROM %susers, %sactivism_metrics WHERE user_login = '%s' and name = '%s';\n",
         $prefix, $prefix, rand(1, 10), $prefix, $prefix,
         $usernames[rand(0, count($usernames) - 1)], $metricnames[rand(0, count($metricnames) - 1)]);
}
?>
