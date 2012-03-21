;;; This is an attempt at an elisp file because I need to put
;;; some commands together to convert a mysql database definition
;;; to postgres to run phpnuke. Tested against definition for 6.0
;;;   2003/04/13 - wjh

;;; To run just do:
;;;   cp sql/nuke.sql sql/pg_nuke.sql
;;;   emacs sql/pg_nuke.sql
;;;     M-x load-file  [filename]
;;;   createuser --no-createdb --no-adduser nuke
;;;   createdb nuke
;;;   psql nuke
;;;     update pg_database set datdba = (select usesysid from pg_shadow where usename = 'nuke') where datname = 'nuke';
;;;     alter user nuke with password 'pass';
;;;   psql -U nuke nuke < sql/pg_nuke.sql

;;; Fix comment style
(replace-string "#" "--")
(goto-line 1)

;;; Remove proprietary table type from table end
(replace-string " TYPE=MyISAM" "")
(goto-line 1)

;;; Put quotes around table names
(replace-regexp "CREATE TABLE \\(.*\\) (" "CREATE TABLE \"\\1\" (")
(goto-line 1)

;;; Convert primary keys to serials
(replace-regexp "\\<\\w*int([[:digit:]]+).*NOT NULL auto_increment" "serial primary key")
(goto-line 1)

;;; Remove all keys
;;; This is a possible issue that I do not know the source well enough
;;;  to check. This removes all keys. The primary keys will still get
;;;  indexed, but foreign keys will not and also postgres' foreign key
;;;  constraints will not be used.
(replace-regexp ".*KEY.*\n" "")
(goto-line 1)

;;; Remove commas left at the end of table definitions
(replace-string ",\n);" "\n);")
(goto-line 1)

;;; Convert small ints
(replace-regexp "tinyint([[:digit:]]+)" "smallint")
(goto-line 1)

;;; Convert large ints
(replace-regexp "bigint([[:digit:]]+)" "bigint")
(goto-line 1)

;;; Convert any remaining ints
(replace-regexp "\\<\\w*int([[:digit:]]+)\\( unsigned\\)?" "integer")
(goto-line 1)

;;; Convert doubles
(replace-regexp "double(.*)" "double precision")
(goto-line 1)

;;; Convert timestamps
(replace-regexp "datetime" "timestamp")
(goto-line 1)

;;; Convert date and time defaults
(replace-regexp "0000-00-00\\( 00:00:00\\)?" "now()")
(goto-line 1)

;;; Convert short text type
(replace-string "tinytext" "text")
(goto-line 1)
