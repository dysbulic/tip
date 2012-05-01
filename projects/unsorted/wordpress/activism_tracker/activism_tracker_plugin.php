<?php
/*
Plugin Name: Activism Tracker
Plugin URI: http://odin.himinbi.org/wordpress_profile_picture/
Description: Tracks activist activities for a campaign blog
Version: 0.1
Author: Will Holcomb <wholcomb@gmail.com>
Author URI: http://himinbi.org
*/

add_action('admin_menu', 'activism_tracker_config');
add_action('activate_activism_tracker/activism_tracker_plugin.php', activism_tracker_install);

$activism_tracker_defaults = array('users' => 5);

function activism_tracker_install() {
  global $wpdb;

  $table_name = $wpdb->prefix . "activism_points";
  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
    ob_start();
    require("activism_tables.sql.php");
    $sql = ob_get_contents();
    ob_end_clean();

    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);

    $options = get_option("activism_tracker_options");
    if($options['load_default_data']) {
      ob_start();
      require("default_data.sql.php");
      $sql = ob_get_contents();
      ob_end_clean();
      $results = $wpdb->query($sql);
    }
  }
}

/**
 * admin_menu hook to add configuration page to menubar
 */
function activism_tracker_config() {
  global $wpdb;
  if(function_exists('add_options_page')) {
    add_options_page('Activism Tracker Options', 'Activism Tracker', 8, __FILE__, activism_tracker_config_page);
  }
}

function activism_tracker_config_page() {
  global $activism_tracker_defaults;
  global $wpdb;
  $options = get_option("activism_tracker_options");

  // This function is called both for the initial display and to process the submission
  if(isset($_POST['action'])) {
    if(!current_user_can('manage_options')) {
      echo '<div id="message" class="updated fade"><p>Error: Permission denied to manage options</p></div>';
    } else {
      // The buttons have double arrows after them in WordPress, strip those
      $action = preg_replace('/\W+$/', '', $_POST['action']);
      $action = preg_replace('/^\W+/', '', $action);
  
      switch($action) {
      case 'Set Configuration':
        foreach($activism_tracker_defaults as $key => $default) {
          if(isset($_POST[$key])) {
            // it is not possible to set an option to an empty value
            // on an empty value, the element is reset to the default
            $options[$key] = ($_POST[$key] != '' ? $_POST[$key] : $profile_picture_defaults[$key]);
          }
        }
        update_option('profile_picture_options', $options);
        echo '<div id="message" class="updated fade"><p>Activism tracker settings saved</p></div>';
        break;
      case 'Add Metric':
        $query = sprintf("INSERT INTO %sactivism_metrics(name) VALUES ('%s')",
                         $wpdb->prefix, $wpdb->escape($_POST['name']));
        $wpdb->query($query);
        printf('<div id="message" class="updated fade"><p>Added Metric: "%s"</p></div>', $_POST['name']);
        break;
      case 'Delete Metric':
        $query = sprintf("DELETE FROM %sactivism_metrics WHERE id = '%s'",
                         $wpdb->prefix, $wpdb->escape($_POST['id']));
        $wpdb->query($query);
        printf('<div id="message" class="updated fade"><p>Deleted Metric: "%d"</p></div>', $_POST['id']);
        break;
      case 'Rename Metric':
        $query = sprintf("UPDATE %sactivism_metrics SET name = '%s', singular_key = '%s', plural_key = '%s', weight = '%s' WHERE id = '%s'",
                         $wpdb->prefix,
                         $wpdb->escape($_POST['name']),
                         $wpdb->escape($_POST['singular_key']),
                         $wpdb->escape($_POST['plural_key']),
                         $wpdb->escape($_POST['weight']),
                         $wpdb->escape($_POST['id']));
        $wpdb->query($query);
        printf('<div id="message" class="updated fade"><p>Renamed Metric: "%s"</p></div>', $_POST['name']);
        break;
      case 'Add Points':
        for($index = 0; isset($_POST['user_id'][$index]); $index++) {
          $query = sprintf("INSERT INTO %sactivism_points(user_id, metric_id, points, description) VALUES ('%s', '%s', '%s', '%s')",
                           $wpdb->prefix,
                           $wpdb->escape($_POST['user_id'][$index]),
                           $wpdb->escape($_POST['metric_id'][$index]),
                           $wpdb->escape($_POST['points'][$index]),
                           $wpdb->escape($_POST['description'][$index]));
          $wpdb->query($query);
        }
        printf('<div id="message" class="updated fade"><p>Added Points: "%d"</p></div>', $index);
        break;
      case 'Delete Points':
        $query = sprintf("DELETE FROM %sactivism_points WHERE id = '%s'", $wpdb->prefix, $wpdb->escape($_POST['id']));
        $wpdb->query($query);
        printf('<div id="message" class="updated fade"><p>Deleted Points: "%d"</p></div>', $_POST['id']);
        break;
      case 'Save Points':
        $query = sprintf("UPDATE %sactivism_points SET points = '%s', description = '%s' WHERE id = '%s'",
                         $wpdb->prefix,
                         $wpdb->escape($_POST['points']),
                         $wpdb->escape($_POST['description']),
                         $wpdb->escape($_POST['id']));
        $wpdb->query($query);
        printf('<div id="message" class="updated fade"><p>Saved Points: "%s"</p></div>', $_POST['description']);
        break;
      }
    }
  }
  require("configuration_pane.php");
}

function activism_print_graphs() {
  if(!isset($wpdb)) {
    $config = dirname(__FILE__) . "/../../../wp-config.php";
    require_once($config);
  }
  print ABSPATH;
  print (isset($wpdb) ? "yes" : "Testing");
}
?>