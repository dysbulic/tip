<?php
/* global variable $options */

//require('points_db_info.inc');
//mysql_connect($host, $user, $password) or print("<!-- Unable to connect to: $user@$host -->\n");
//mysql_select_db($database) or print("<!-- Unable to select database: $database -->\n");

global $wpdb;
$table_prefix = $wpdb->prefix;
$query = "SELECT count(*) FROM ${table_prefix}activism_metrics";
$metricCount = $wpdb->get_var($query);
?>

<script type="text/javascript">//<![CDATA[
  var metrics = {
<?php
$query = "SELECT id, name FROM ${table_prefix}activism_metrics ORDER BY name";
//$result = mysql_query($query);
$result = $wpdb->get_results($query, ARRAY_N);

for($i = 0; $i < count($result); $i++) {
  printf("             '%s' : '%s'%s",
         $wpdb->escape($result[$i][0]),
         $wpdb->escape($result[$i][1]),
         ($i + 1 < count($result) ? ",\n" : ""));
}
?>
                };
//]]></script>

<?php
$plugin_path = preg_replace("|wp-admin/.*|", "", $_SERVER['SCRIPT_NAME']) . 'wp-content/plugins/' . preg_replace("|.*/(.*)/.*|", '$1', __FILE__);
printf('<script src="%s/%s" type="text/javascript"></script>', $plugin_path, "points_admin_test_form.js");
?>

<div class="wrap">

  <h2>Add Metrics</h2>
  <form action="" method="post">
    <div>
      <label>Metric Name:</label>
      <input type="text" name="name"></input>
      <input type="submit" name="action" value="<?php _e('Add Metric &raquo;'); ?>" />
    </div>
  </form>

  <hr />
  
<?php if($metricCount > 0) { ?>

 <h1>Edit Metrics</h1>
 <form action="" method="post" id="editmetrics">
   <table>
     <thead>
       <tr><th></th><th>Metric</th><th>Singular Key</th><th>Plural Key</th><th>Weight</th><th></th></tr>
     </thead>
     <tbody>
<?php
$query = ("SELECT id, name, singular_key, plural_key, weight FROM ${table_prefix}activism_metrics");
$result = $wpdb->get_results($query, ARRAY_N);
for($i = 0; $i < count($result); $i++) {
?>
  <tr>
    <form action="" method="post">
      <td>
        <?php printf("<input type='hidden' name='id' value='%s'></input>\n", $result[$i][0]) ?>
        <input type="submit" name="action" value="<?php _e('&laquo; Delete Metric'); ?>"></input>
      </td>
      <td>
        <?php printf("<input type='text' name='name' value='%s'></input>\n", $result[$i][1]) ?>
      </td>
      <td>
        <?php printf("<input type='text' name='singular_key' value='%s'></input>\n", $result[$i][2]) ?>
      </td>
      <td>
        <?php printf("<input type='text' name='plural_key' value='%s'></input>\n", $result[$i][3]) ?>
      </td>
      <td>
        <?php printf("<input type='text' name='weight' value='%s'></input>\n", $result[$i][4]) ?>
      </td>
      <td>
        <input type="submit" name="action" value="<?php _e('Rename Metric &raquo;'); ?>"></input>
      </td>
    </form>
  </tr>
<?php
}
?>
     </tbody>
   </table>
 </form>
    
 <hr />
    
 <h1>Add Points</h1>
 <form action="" onsubmit="addPoints(event); return false;">
   <div>
     <label>Add Points to Activist:</label>
     <select name="userid">
<?php
$query = ("SELECT id, display_name FROM ${table_prefix}users ORDER BY display_name");
//$result = mysql_query($query);
$result = $wpdb->get_results($query, ARRAY_N);
//if(!$result) { print '' . mysql_error(); }
//while($row = mysql_fetch_array($result)) {
//while($row = $wpdb->get_row(null, ARRAY_N)) {
for($i = 0; $i < count($result); $i++) {
  printf("<option value='%s'>%s</option>\n", $result[$i][0], $result[$i][1]);
}
?>
     </select>
     <input type="button" value="<?php _e('New Points &raquo;'); ?>" onclick="addPoints(event)"></input>
   </div>
 </form>
 <form action="" method="post" id="addpoints" class="disabled">
   <table>
     <thead>
       <tr><th></th><th>Activist</th><th>Metric</th><th>Points</th><th>Description</th></tr>
     </thead>
     <tbody id="points">
     </tbody>
   </table>
   <div><input type="submit" name="action" value="<?php _e('Add Points &raquo;'); ?>" /></div>
 </form>
 
 <hr />

<?php
$query = sprintf("SELECT %sactivism_points.id, display_name, %sactivism_metrics.name, points, description" .
                 " FROM %sactivism_points JOIN %susers ON %sactivism_points.user_id = %susers.id" .
                 " JOIN %sactivism_metrics ON metric_id = %sactivism_metrics.id",
                 $table_prefix, $table_prefix, $table_prefix, $table_prefix, $table_prefix, $table_prefix,
                 $table_prefix, $table_prefix);
//$result = mysql_query($query);
$result = $wpdb->get_results($query, ARRAY_N);
//if(!$result) { print '' . mysql_error(); }
//if(@mysql_num_rows($result) > 0){
?>
  <h1>Edit Points</h1>
  <table>
    <thead>
      <tr><th></th><th>Activist</th><th>Metric</th><th>Points</th><th>Description</th><th></th></tr>
    </thead>
    <tbody>
<?php
//while($row = mysql_fetch_array($result)) {
for($i = 0; $i < count($result); $i++) {
?>
      <tr>
        <form action="" method="post">
          <td>
            <?php printf("<input type='hidden' name='id' value='%s'></input>\n", $result[$i][0]) ?>
            <input type="submit" name="action" value="<?php _e('&laquo; Delete Points'); ?>"></input>
          </td>
          <td><?php print $result[$i][1] ?></td>
          <td><?php print $result[$i][2] ?></td>
          <td>
             <?php printf("<input type='text' name='points' value='%s'></input>\n", $result[$i][3]) ?>
          </td>
          <td>
            <?php printf("<input type='text' name='description' value='%s'></input>\n", $result[$i][4]) ?>
          </td>
          <td>
            <input type="submit" name="action" value="<?php _e('Save Points &raquo;'); ?>"></input>
          </td>
        </form>
      </tr>
<?php
}
?>
    </tbody>
  </table>

<?php } ?>
