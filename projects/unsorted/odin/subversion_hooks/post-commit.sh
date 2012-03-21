#!/bin/sh

# POST-COMMIT HOOK
#
# The post-commit hook is invoked after a commit.  Subversion runs
# this hook by invoking a program (script, executable, binary, etc.)
# named 'post-commit' (for which this file is a template) with the 
# following ordered arguments:
#
#   [1] REPOS-PATH   (the path to this repository)
#   [2] REV          (the number of the revision just committed)
#
# The default working directory for the invocation is undefined, so
# the program should set one explicitly if it cares.
#
# Because the commit has already completed and cannot be undone,
# the exit code of the hook program is ignored.  The hook program
# can use the 'svnlook' utility to help it examine the
# newly-committed tree.
#
# On a Unix system, the normal procedure is to have 'post-commit'
# invoke other programs to do the real work, though it may do the
# work itself too.
#
# Note that 'post-commit' must be executable by the user(s) who will
# invoke it (typically the user httpd runs as), and that user must
# have filesystem-level permission to access the repository.
#
# On a Windows system, you should name the hook program
# 'post-commit.bat' or 'post-commit.exe',
# but the basic idea is the same.
# 
# Here is an example hook script, for a Unix /bin/sh interpreter:

REPOS="$1"
REV="$2"

# script runs as apache when updating via webdav

[ -z "$BUILDLOGS" ] && BUILDLOGS="/home/wjholcomb/logs/subversion"
[ -e "$BUILDLOGS" ] || mkdir "$BUILDLOGS"

LOG="$BUILDLOGS/svn.commit.log.$(date "+%Y.%m.%d.%H.%0k.%s")"

echo "Commiting revsion $REV of $REPOS" > "$LOG"
echo "Logging update to: $LOG" >> "$LOG"

if [ ! -z "$REV" ]; then
    ADMINEMAIL=commits@svn.madstones.com
    EMAILPROG=/usr/lib/subversion/hook-scripts/commit-email.pl
    echo "Sending diff e-mail to $ADMINEMAIL" >> "$LOG"
    $EMAILPROG "$REPOS" "$REV" $ADMINEMAIL --from $ADMINEMAIL
fi

# To avoid: svn: Can't convert string from native encoding to 'UTF-8': on some clients
export LC_CTYPE=en_US.UTF-8

if [ ! -z "$REPOS" ]; then
    #DIR="$(echo ~/${REPOS##*/})"
    DIR=/home/wjholcomb/sites/

    if [ -e "$DIR" ]; then
	echo "Updating copy at $DIR" >> "$LOG"

	cd "$DIR"
	svn update
	
       	#for makefile in $(find -name Makefile); do
	#    DIR="${makefile%Makefile}"
	#    echo "Making: $makefile ($DIR)" # >> "$LOG"
	#    make -C "$DIR" # >> "$LOG"
	#done
    fi
fi

# Update DoH
# ssh wholcomb@madstones.com "cd sites && svn update"
