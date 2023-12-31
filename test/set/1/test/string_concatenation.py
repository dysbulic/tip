#!/usr/bin/python

import os

sql = ("select title, subtitle, author.name, publication_date, text\n" +
          " from news left join author on (news.article_id = article.id)\n" +
          " where author.id = %s and news.title is not null\n" % os.geteuid() +
          " limit 3 order by author.name")
print sql

types = ["apple", "orange", "pear", "melon"]

#
# Array Indexing
#
sql = "select color, avg_weight, calories_per_gram from fruits\n where "
for i in range(0, len(types)):
    if i > 0: sql += " or "
    sql += "type = '" + types[i] + "'"

print sql

#
# Array Iterator
#
sql = "select color, avg_weight, calories_per_gram from fruits\n where "
for type in types:
    if sql.endswith("'"): sql += " or "
    sql += ("type = '" + type + "'")

print sql

#
# Array Join
#
sql = ("select color, avg_weight, calories_per_gram from fruits\n"
       " where type = '" + "' or type = '".join(types) + "'")

print sql

#
# Array Map with Function
#
def typeString(type):
    return "type = '%s'" % type

sql = ("select color, avg_weight, calories_per_gram from fruits\n"
       " where " + " or ".join(map(typeString, types)))

print sql

#
# Array Map with Lambda
#
sql = ("select color, avg_weight, calories_per_gram from fruits\n"
       " where " + " or ".join(map(lambda type: "type = '%s'" % type, types)))

print sql

#
# List Comprehension
#
sql = ("select color, avg_weight, calories_per_gram from fruits\n"
       " where " + " or ".join(["type = '%s'" % type for type in types]))

print sql
