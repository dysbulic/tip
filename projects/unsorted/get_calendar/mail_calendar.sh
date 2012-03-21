#!/bin/bash
#
# Mails a calendar entry to an email address

cd ${0%/*}

#command="java -classpath $CLASSPATH:java-getopt-1.0.9.jar GetCalendar"
command="./get_calendar.sh"

name="Calendar"

if [ $# -eq 0 ]; then
  cat <<EOF
  mail_calendar.sh [-e e-mail] [-p password]
                   [-d YYYY/MM/DD]
                   {[-f] [-b] [-c calendar]}
                   [-n name] {address}

  Requests a page from a page-a-day calendar online.
   -f sets the front to be requested
   -b sets the back to be requested
   -e sets the email username to be used in connecting
   -p sets the password to be used in connecting
   -n sets the name of the calendar
   -c sets the calendar to request
   -d sets the date to retrieve

  Arguments may be used multiple times...

   ./mail_calendar.sh -e will@thfa.org -p pass -f -b -c MENS \\
      amqad@honors.tntech.edu

EOF
exit
fi

date=$(date +%Y/%m/%d)

while [ $# -gt 0 ]; do
    case $1 in
        "-f" | "-b")
            command="$command $1"
            ;;
        "-e" | "-p")
            command="$command $1"
            shift
            command="$command $1"
            ;;
        "-d")
            command="$command $1"
            shift
            command="$command $1"
            date=$1
            ;;
        "-c")
            shift
            command="$command $1"
            ;;
        "-n")
            shift
            name=$1
            ;;
        *)
            if [ -z "$recipients" ]; then
                recipients="$1"
            else
                recipients="$recipients, $1"
            fi
            ;;
    esac
    shift;
done

echo "Executing: $command"
dirs=$($command | grep "Writing" | sed -e "s/.*to:[[:space:]]*//" | sort | uniq)

# Args:
#  $1: HTML file to be added to message
#  $2: MIME composition draft
function add_file() {
    unset imgfiles;
    for image in $(cat "$1" \
        | sed -e "s/<img[[:space:]]\+src=\"\([^\"]*\)\"/\nIMAGE: \1\n/gi" \
        | grep IMAGE: | sed -e "s/IMAGE: //"); do
      imgfiles[${#imgfiles[*]}]="$dir/${image##*/}"
    done

    echo "#begin alternative" >> "$2"
    
    if [ ${#imgfiles[*]} -gt 0 ]; then
        echo "#<text/plain" >> "$2"
        echo "There is an image in this page. To view it properly, it is necessary to" >> "$2"
        echo "view it in a graphical email reader (such as mozilla or outlook)." >> "$2"
        echo  >> "$2"
        echo  "The text portion is included below, but it may well not make sense." >> "$2"
        echo  >> "$2"
        echo "*-*" >> "$2"
        echo  >> "$2"
        links -dump "$1" >> "$2"
        echo  >> "$2"
    else
        echo "#text/plain | links -dump \"$1\"" >> "$2"
    fi

    if [ ${#imgfiles[*]} -gt 0 ]; then
        # This should be "begin related", but mhbuild is too smart for me and
        #  won't do that, so I have to do the parallel and then replace it at
        #  the end.
        echo "#begin parallel" >> "$2"
    fi

    echo "#text/html $1" >> "$2"

    i=0
    while [ $i -lt ${#imgfiles[*]} ]; do
        imgfile=${imgfiles[$i]};
        i=$(( $i + 1 ))
        ext=${imgfile##*\.}
        if [ "$ext" == "jpg" ]; then
            type="image/jpeg"
        else
            type="image/$ext"
        fi
        echo "#$type <${imgfile##*/}> $imgfile" >> "$2"
    done

    if [ ${#imgfiles[*]} -gt 0 ]; then
        echo "#end" >> "$2" # End multipart/parallel
    fi
    echo "#end" >> "$2" # End multipart/alternative
    
    # Strip any leading directories off of image urls in file and reference
    # them to the email
    cat "$1" | sed -e "s/<img[[:space:]]\+src=\"\(.*\/\|\)\([^\"]*\)\"/<img src=\"cid:\2\"/gi" > "$1.stripped"
    mv -f "$1.stripped" "$1"
    echo "Made image urls relative for: $1"
}

unset files
for dir in $dirs; do
    echo "Collecting files from: $dir"

    # The order matters on these, so process them separately
    [ -e "$dir/front.html" ] && files[${#files[*]}]="$dir/front.html"
    [ -e "$dir/back.html" ] && files[${#files[*]}]="$dir/back.html"
    for file in "$dir/"*.html; do
        if [[ -e "$file" && "$file" != "$dir/front.html" && "$file" != "$dir/back.html" ]]; then
            files[${#files[*]}]="$file"
        fi
    done
done

if [ ${#files[*]} -eq 1 ]; then
    echo "Found ${#files[*]} HTML file to output"
else
    echo "Found ${#files[*]} HTML files to output"
fi

if [ ${#files[*]} -eq 0 ]; then
    echo 1>&2 "Error: No files found processing $@"
else
    draft="/tmp/mime_composition.$$"
    
    echo "To: $recipients" > "$draft"
    echo "Subject: $name for $(date --date=$date '+%Y/%m/%d (%A, %B %-d, %Y)')" >> "$draft"
    echo >> "$draft"

    if [[ ${#files[*]} -eq 1 && ! $(grep -i \<img "$files") ]]; then
        links -dump $files >> "$draft"
    else
        fileindex=0;
        while [ $fileindex -lt ${#files[*]} ]; do
            echo "Adding file: $fileindex: ${files[$fileindex]}"
            add_file "${files[$fileindex]}" "$draft"
            fileindex=$(( $fileindex + 1 ))
        done
    fi

    mhbuild "$draft"
    
    # To bypass mhmail not liking multipart/related
    sed -e "s/multipart\/parallel/multipart\/related/" "$draft" > "$draft.relative"
    mv -f "$draft.relative" "$draft"

    # send is misconfigured on honors server and missing on stderr
    #send "$draft"

    cat "$draft" | /usr/sbin/sendmail -bm -t && \
        rm -rfv "$dir" "$draft" "${draft%/*}/,${draft##*/}.orig"
fi
