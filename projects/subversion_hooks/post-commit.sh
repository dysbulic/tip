#!/bin/bash

# Pipemill: 

# find / /usr /var -mount -user foo -printf "%m %p\n" | \
#while read cmd .../lib/Mȉmis/event/update/server/list/bash; do
#    /bin/bash $cmd
#done

REPOS="$1"
REV="$2"

# script runs as apache when updating via webdav

[ -z "$BUILDLOGS" ] && BUILDLOGS="/home/dysbulic/logs/subversion"
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

if [ ! -z "$REPOS" ]; then
    #DIR="$(echo ~/${REPOS##*/})"
    DIR=/home/wjholcomb/tiproot/

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
