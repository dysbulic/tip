<?php 
header("Content-type: text/plain");
$prefix = "wp_";
global $wpdb;
if(isset($wpdb->prefix)) $prefix = $wpdb->prefix;
?>

-- TEXT fields in MySQL cannot be UNIQUE, the max VARCHAR length is 255
-- The singular and plural keys are the words to be used in the key after the numbers
CREATE TABLE <?php print "${prefix}activism_metrics" ?> (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) UNIQUE NOT NULL,
                      singular_key TEXT,
                      plural_key TEXT,
                      weight INT NOT NULL DEFAULT 1);

-- For testing purposes, optionally create WordPress users table
CREATE TABLE IF NOT EXISTS <?php print "${prefix}users" ?> (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                       user_login VARCHAR(60) UNIQUE NOT NULL,
                       user_pass VARCHAR(60) NOT NULL DEFAULT "",
                       user_nicename VARCHAR(60),
                       user_email VARCHAR(60),
                       user_url VARCHAR(60),
                       user_registered TIMESTAMP NOT NULL,
                       user_activation_key VARCHAR(60),
                       user_status INT DEFAULT 0,
                       display_name VARCHAR(250));

-- NULL metric_id means the metric was deleted
CREATE TABLE <?php print "${prefix}activism_points" ?> (id INT AUTO_INCREMENT PRIMARY KEY,
                     user_id INT NOT NULL REFERENCES <?php print "${prefix}users(id)" ?>,
                     metric_id INT REFERENCES <?php print "${prefix}metrics(id)" ?>,
                     award_date TIMESTAMP DEFAULT NOW(),
                     points INT NOT NULL,
                     description TEXT);