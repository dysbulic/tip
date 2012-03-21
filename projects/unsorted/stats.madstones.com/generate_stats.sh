#!/bin/bash

DIRS=$(echo "/var/log/httpd /var/log/apache" ~/logs/)

echo $DIRS

#OPTIND=1
while getopts "m:y:" opt; do
    case $opt in
        m)      month=$OPTARG ;;
        y)      year=$OPTARG ;;
        *)      echo "Unknown option: $opt" ;;
    esac
done
shift $(( $OPTIND - 1 ))

[ -z "$month" ] && month=$(date +"%m" | sed -e 's/^0//g')
[ -z "$year" ] && year=$(date +"%Y")
OUTDIR=$(printf 'reports/stats_%d_%02d' $year $month)
PROG="./awstats_buildstaticpages.pl"

if [ -z "$*" ]; then
    NAME=${0##*/}
cat <<EOF
  Generates an awstats log for a host and puts the
   results in $OUTDIR/
  Usage: $NAME [-m <month>] [-y <year>] <url list>
    <url list> is a list of urls to include log files for
     the first url is the authoritative name and subsequent names
     are aliases
    <month> is the month you want a report for (default current month)
    <year> is the year you want a report for (default current year)

  Example: $NAME -m 3 www.himinbi.org himinbi.org
EOF
    exit 1
fi

for name in $@; do
    for log in $name-access_log $name-access.log $name.access.log; do
        for dir in $DIRS; do
            if [ -r $dir/$log ]; then
                LOGS="$dir/$log $LOGS";
	    elif [ -r $dir/$name/http/access.log ]; then
                LOGS="$dir/$name/http/access.log $LOGS";
            fi
        done
    done
done

HOSTNAME=$1

if [ $HOSTNAME == $(hostname --fqdn) ]; then
    for log in access_log access.log; do
        for dir in $DIRS; do
            if [ -r $dir/$log ]; then
                LOGS="$dir/$log $LOGS";
            fi
        done
    done
fi

if [ -z "$LOGS" ]; then
    [ -z "$LOGS" ] && echo "Could not find a log file for $HOSTNAME" >&2
    exit 1
fi

LOCALCONF=awstats.$HOSTNAME.conf
if [ -e $LOCALCONF ]; then
    [ -e $LOCALCONF ] && echo "$LOCALCONF exists: removing" >&2
    rm -f "$LOCALCONF"
fi

for log in $LOGS; do
    line=$(head -n1 "$log")
    # I don't really understand how the backreferences work
    # doing (exp){n} only causes a backreference to the nth
    # appearance of exp. So, the counts for the different
    # lines is based off of that.
    #regex='^( *[^[:space:]]+){3} (\[[^]]+\]) ("[^"]+")( [0-9]+){2}(( "[^"]+"){2})?$';
    regex='^\( *[^[:space:]]\+\)\{3\} \(\[[^]]\+\]\).* \("[^"]\+"\)\( [-0-9]\+\)\{2\}\(\( "[^"]\+"\)\{2\}\)\?$'
    match=$(echo "$line" | sed -e "s/$regex/\\5/")
    type="unknown"
    if [ "$match" != "$line" ]; then
        if [ "$match" == "" ]; then type="common"
        else type="combined"; fi
    else
        echo "Unknown log format for $log; using default" >&2
    fi
    if [[ "$lasttype" != "" && $lasttype != $type ]]; then
        echo "$lastlog ($lasttype) and $log ($type) have different line formats" >2
        exit 1
    else
        lasttype=$type
        lastlog=$log
    fi
done

[ -x "$PROG" ] || { echo "Could not execute: $PROG"; exit 1; }

pushd ${0%/*}

[ -d $OUTDIR ] || mkdir -p $OUTDIR || { echo "Could not create $OUTDIR" && exit 1; }

echo "Generating awstats report for: $@ ($type)"

echo "# Simple config to generate local log ($LOCALCONF)" > $LOCALCONF
echo "Include \"awstats.common.conf\"" >> $LOCALCONF
#echo "LogFile=\"$LOG\"" >> $LOCALCONF
echo "LogFile=\"${0%/*}/logresolvemerge.pl $LOGS |\"" >> $LOCALCONF
[ "$type" == "common" ] && echo "LogFormat=4" >> $LOCALCONF
[ "$type" == "combined" ] && echo "LogFormat=1" >> $LOCALCONF
echo "SiteDomain=\"$HOSTNAME\"" >> $LOCALCONF
echo "HostAliases=\"$@\"" >> $LOCALCONF

$PROG -update -config=$HOSTNAME -awstatsprog=./awstats.pl -dir=$OUTDIR -month=$month -year=$year

rm -f $LOCALCONF

popd
