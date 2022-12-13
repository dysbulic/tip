#!/bin/sh

type=$1
[ -z "$type" ] && read -p "Database type: [mysql|sqlite] (def: mysql) " type
[ -z "$type" ] && type="mysql"

DB_NAME=users
USER=$(whoami)
read -p "Site Password for $USER: " PASS
#PASS=$(echo $PASS | md5sum | sed -e "s/ .*//")

insertsql="insert into users values(null, \"$USER\", \"$PASS\");"

if [ -z "$type" ]; then
    echo "Must specify a type of either sqlite of mysql"
elif [ $type == "sqlite" ]; then
    sqlite3 $DB_NAME.db <<EOF
create table users (id integer primary key, username text, password text);
$insertsql
EOF
elif [ $type == "mysql" ]; then
    read -p "MySQL Admin: " ADMIN
    read -p "User password: " MYPASS
    mysql --user=root -p <<EOF
create database $DB_NAME;
use $DB_NAME;
create table users (id integer primary key auto_increment, username text, password text);
$insertsql
grant all privileges on $DB_NAME.* to "$user"@"localhost" identified by "$MYPASS";
EOF
else
    echo "Unknown database type \"$type\""
fi
