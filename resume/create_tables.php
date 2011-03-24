<?php
require_once('db.php');

$DROP = true; // Drop tables before creation to clear data
$DEBUG = false; // Print messages

$tables = array(
        "users" => array(
                "table" => "
                        `username` varchar(30) NOT NULL,
                        `name` text NOT NULL,
                        `email` text NOT NULL,
                        `pass_sha1` text NOT NULL,
                ",
                "keys" => "
                       PRIMARY KEY (`username`)
                ",
        ),
        "timelapses" => array(
                "table" => "
                       `id` int NOT NULL AUTO_INCREMENT,
                       `start_time` datetime NOT NULL,
                       `end_time` datetime NOT NULL,
                ",
                "keys" => "
                       PRIMARY KEY (`id`)
                ",
        ),
        "events" => array(
                "table" => "
                        `id` int NOT NULL AUTO_INCREMENT,
                        `creator` varchar(30) NOT NULL,
                        `title` text NOT NULL,
                        `description` text NOT NULL,
                ",
                keys => "
                     PRIMARY KEY (`id`),
                     FOREIGN KEY (`creator`) REFERENCES users(username)
                ",
        ),
        "event_spans" => array(
                "table" => "
                        `id` int NOT NULL AUTO_INCREMENT,
                        `timelapse_id` int NOT NULL,
                        `event_id` int NOT NULL,
                ",
                keys => "
                        PRIMARY KEY (`id`),
                        FOREIGN KEY (`timelapse_id`) REFERENCES timelapses(id),
                        FOREIGN KEY (`event_id`) REFERENCES events(id)
                ",
        )
);

function lastchar($str) {
        /* Return the last character in the string */
        return $str[strlen($str)-1];
}

if(DEBUG) header("Content-type: text/plain");

foreach($tables as $name => $vals) {
        if($DEBUG) print "Loop: $name\n";
        $table = trim($vals['table']);
        $keys = trim($vals['keys']);
        $table = ( "create table if not exists `$name` ( "
                   . $table
                   . (lastchar($table) == ',' ? '' : ',')
                   . "\n`creation_date` datetime NOT NULL"
                   . (strlen($keys) > 0 ? ',' : '')
                   . $keys
                   . ")"
                );
        if($DEBUG) print "table:$table\n";
        if($DROP) mysql_magic('drop table ' . $name);
        mysql_magic($table);

        $trigger = "CREATE TRIGGER {$name}_insert BEFORE INSERT ON `$name`
                FOR EACH ROW SET NEW.creation_date = NOW()";
        if($DEBUG) print "trigger:$trigger\n";
        mysql_magic($trigger);
}
