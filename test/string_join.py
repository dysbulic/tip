#!/usr/bin/python

columns = ["column_one", "column_two", "column_three", "column_four"]
print "select * from table where " + " == 'test' or ".join(columns) + " == 'test';"
print "select * from table where column == '" + "' or column == '".join(columns) + "';"
