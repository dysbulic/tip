#!/bin/bash
#
# Simple program to get the info from a page-a-day calendar
#
# http://page-a-day.com/pad/2003ZENN/01JAN/01/
#
# 2002/02/07 - dys

site="http://page-a-day.com"
auth_url="$site/pub-bin/paduserlogin.pl"
cal_url_base="$site/pad/editorial"
date=$(date "+%Y/%m/%d")
side="front"

if [ $# -eq 0 ]; then
  cat <<EOF
  Gets a page from a page-a-day calendar. To use it you have to
  already have an account on the page-a-day system at:
  http://page-a-day.com/

  get_calendar.sh {[-d YYYY/MM/DD] [-e e-mail] [-p password]}
    [calendar] [-f] [-b]}

  Requests a page from a page-a-day calendar online.
   -e sets the e-mail to be used in connecting
   -p sets the password to be used in connecting
   -d sets the date to get (default: today)
   -f sets the front to be requested
   -b sets the back to be requested
   
   Known calendars are:
      ZENN: Little Zen Calendar
      MENS: 365 Mensa Brain Puzzlers
      CATS: 365 Cats Calendar
      DARW: Darwin Awards Calendar
      JEEV: Ask Jeeves Calendar
      TRIV: 365 Amazing Trivia Facts

  Arguments are processed in order and may be used multiple times...
  
   ./get_calendar.sh MENS -e dys@thfa.org -p pass -f -b ZENN -f

  Will retrieve the front and back of the Mensa calendar and the
  front of the zen one.

EOF
fi

while [ $# -gt 0 ]; do
    if [ "$1" == "-e" ]; then
        shift
        email=$1
    elif [ "$1" == "-d" ]; then
        shift
        date=$1
    elif [ "$1" == "-p" ]; then
        shift
        password=$1
    elif [[ "$1" != "-f" && "$1" != "-b" ]]; then
        # All other agruments are taken to be calendar names
        cal=$1;
    else
        if [ "$1" == "-f" ]; then
            side="front"
        elif [ "$1" == "-b" ]; then
            side="back"
        fi
        
        if [ -z "$cal" ]; then
            echo 1>&2 "Error: Tried getting a side before calendar name set"
        elif [[ -z $email || -z $password ]]; then
            echo 1>&2 "Error: Username and password not set"
        else
            dir="/tmp/calendar_${cal}_$(date --date=$date +%Y_%m_%d)"
            [ ! -e "$dir" ] && mkdir "$dir"
            echo "Writing $cal to: $dir"

            file="$dir/$side.html"
            if [ -e "$file" ]; then
                echo 1>&2 "Skipped: $file already exists"
            else
                # If not logged in yet, do so
                if [ -z $SESSIONID ]; then
                    ID_NAME="PADSID"
                    args="--data rm=setuser --data target_uri= --data email=$email --data password=$password"
                    command="curl --cookie-jar - --output /dev/null --silent $args $auth_url"
                    SESSIONID=$($command | grep $ID_NAME | sed -e "s/^.*$ID_NAME[[:space:]]*/$ID_NAME=/")
                    echo "Got session id: $SESSIONID"
                fi

                # Build the appropriate url
                url_date=$(date --date=$date "+%m%b/%d" | tr [:lower:] [:upper:])
                year=$(date --date=$date "+%Y")
                url="$cal_url_base/$side/$year$cal/$url_date/"
                echo "Getting url: $url"
                
                # Get the page
                curl --cookie "$SESSIONID" --silent $url --output $file
                echo "Wrote $cal $side on $date to: $file"
                
                # Get any images embedded in the page
                for image in $(cat "$file" \
                    | sed -e "s/<img[[:space:]]\+src=\"\([^\"]*\)\"/\nIMAGE: \1\n/gi" \
                    | grep IMAGE: | sed -e "s/IMAGE: //"); do
                  imgfile="$dir/${image##*/}"
                  if [ -e "$imgfile" ]; then
                      echo 1>&2 "Skipped: $imgfile already exists"
                  else
                      imgurl="$url$image"
                      echo "Getting image: $imgurl"
                      curl --cookie "$SESSIONID" --location --referer $url --silent "$imgurl" --output $imgfile
                      echo "Wrote image to: $imgfile"
                  fi
                done
            fi
        fi
    fi
    shift
done

# logout now that it's done
if [ ! -z "$SESSIONID" ]; then
    curl --cookie "$SESSIONID" --output /dev/null --silent $auth_url?rm=logout
fi
