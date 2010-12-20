#!/usr/bin/python

"""
Something odd is happening and my dates are getting munged when I copy them from one
database to another. This is some testing to try and figure out what is happening.
"""


import MySQLdb, sys, time

dbUser = "root"
dbPass = ""
dbName = "test"

tableName = "date_test_" + time.strftime("%Y_%m_%d_%H_%M_%S")

dbCreated = False

try:
    db = MySQLdb.connect(user=dbUser, db=dbName)
except MySQLdb.Error, details:
    if details[0] == 1049: # unknown database error
        db = MySQLdb.connect(user=dbUser)
        db.query("create database %s" % dbName)
        dbCreated = True
        db.close()
        db = MySQLdb.connect(user=dbUser, db=dbName)
    else:
        print details
        sys.exit(1)

try:
    dbCursor = db.cursor()
    insertCursor = db.cursor()
    dbCursor.execute("create table %s (id int auto_increment primary key, date date not null, timestamp timestamp default current_timestamp)" % tableName)
    insertCursor.execute("insert into %s (date, timestamp) values (%%s, %%s)" % tableName, ("1995-01-25", "1995-01-25"))
    insertCursor.execute("insert into %s (date) values (now())" % tableName)
    dbCursor.execute("select timestamp from %s" % tableName)
    while 1:
        dbRow = dbCursor.fetchone()
        if dbRow is None: break
        insertCursor.execute("insert into %s (date) values (%%s)" % tableName, dbRow[0])

    dbCursor.execute("select id, date, timestamp from %s order by id" % tableName)
    while 1:
        dbRow = dbCursor.fetchone()
        if dbRow is None: break
        print "%s: %s - %s" % (dbRow[0], dbRow[1], dbRow[2])
    
    if dbCreated:
        db.query("drop database %s"% dbName)
    else:
        dbCursor.execute("drop table %s" % tableName)
        
    db.close()
except NameError:
    pass
