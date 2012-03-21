#!/bin/bash

# Author: Will Holcomb <wholcomb@gmail.com>
# Date: July 2008
#
# Runs data analyses from http://odin.himinbi.org/idf_to_item:item/
#
# Username and password for mysql should be specified in ~/.my.cnf of the form:
#
# [client]
# user = will
# host = localhost
# password = Cra%%ieP@55

OUTFILE=lastfm.test.out
TIME="time -o $OUTFILE -a"

$TIME ./load_lastfm_users.py lastfm.users | tee "$OUTFILE"
$TIME mysql tfidf < tfidf.sql | tee -a "$OUTFILE"
$TIME mysql tfidf < raw_counts.sql | tee -a "$OUTFILE"
$TIME mysql tfidf < similarity_comparisons.sql | tee -a "$OUTFILE"
