#!/usr/bin/env python

"""
Author: Will Holcomb <wholcomb@gmail.com>
Date: June 2008

Loads a LastFM data dump into a mysql database.
Relies on no insertion conflicts, so best run on an empty database.

Usage: load_lastfm_users.py filename password
"""

import _mysql, _mysql_exceptions
import sys

if len(sys.argv) <= 1:
    print __doc__
    sys.exit(-1)

db = _mysql.connect(db = "tfidf")

db.query("CREATE TABLE IF NOT EXISTS user (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(32) UNIQUE)")
db.query("CREATE TABLE IF NOT EXISTS artist (id INT AUTO_INCREMENT PRIMARY KEY, name TEXT, mbid VARCHAR(48) UNIQUE)")
db.query("CREATE TABLE IF NOT EXISTS listen (user_id INT NOT NULL," +
         "artist_id INT NOT NULL," +
         "count INT NOT NULL DEFAULT 1," +
         "FOREIGN KEY (user_id) REFERENCES user(id)," +  
         "FOREIGN KEY (artist_id) REFERENCES artist(id))")  

infile = open(sys.argv[1], "r")
linecount = 0
users = {}
artists = {}
for line in infile:
    line = line.split("<sep>")
    username = _mysql.escape_string(line[0])
    if not users.has_key(username):
        db.query("INSERT INTO user(name) VALUES ('%s')" % username)
        users[username] = db.insert_id()
    userid = users[username]

    artistmbid = _mysql.escape_string(line[2])
    if artistmbid == "(NO MBID)":
        artistmbid = _mysql.escape_string(line[1])
    if not artists.has_key(artistmbid):
        try:
            db.query("INSERT INTO artist(name, mbid) VALUES ('%s', '%s')" %
                     (_mysql.escape_string(line[1]), artistmbid))
        except _mysql_exceptions.IntegrityError:
            print "Error inserting artist %s (%s)" % (line[1], artistmbid)
            continue
        artists[artistmbid] = db.insert_id()
    artistid = artists[artistmbid]
    
    db.query("INSERT INTO listen(user_id, artist_id, count) VALUES ('%d', '%d', '%d')" %
             (userid, artistid, int(line[3])))
    linecount += 1
    if linecount % 10000 == 0: print linecount
    elif linecount % 500 == 0: print ".",
