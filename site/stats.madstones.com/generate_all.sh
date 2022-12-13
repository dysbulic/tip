#!/bin/bash

#if [ -z "$PS1" ]; then # not interactive
     # Is there some way to selectively silence this program?
#fi

[ "${0%/*}" != "" ] && pushd "${0%/*}"

DOMAINS=""
#DOMAINS="$DOMAINS invitees.mr.pcvs.org mr.pcvs.org"
DOMAINS="$DOMAINS odin.himinbi.org resume.himinbi.org"
#DOMAINS="$DOMAINS studs.mr.pcvs.org will.mr.pcvs.org"
DOMAINS="$DOMAINS www.himinbi.org www.holcomb.info"
#DOMAINS="$DOMAINS www.pcvs.org"

for domain in $DOMAINS; do
    if [ "${domain:0:4}" == "www." ]; then alias=${domain#www.};
    else alias=www.$domain; fi
    ./generate_stats.sh "$@" $domain $alias
done
#./generate_stats.sh "$@" $(hostname --fqdn) $(hostname --alias)

popd
