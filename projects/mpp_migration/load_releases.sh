#!/bin/sh

# Script to load a set of press releases from an rmlm file into kintera

URI=https://www.kintera.com
LOGIN=wholcombmpp
FOLDER="Press Releases $(date '+%Y_%m_%d-%H:%M')"
SOURCE="$1"
# COOKIES=$(tempfile --prefix cookies.)
COOKIES=/tmp/kintera_cookies.log

[ -e "$COOKIES" ] && rm -f "$COOKIES"
touch "$COOKIES"

while [ -z "$LOGIN" ]; do
    read -p "Login ID: " LOGIN
done

while [ -z "$PASS" ]; do
    read -p "Password: " PASS
done

FILECOUNT=0
FPRE="kintera_debug."

urlEncode="perl -p -e 'chomp;s/([\W])/\"\%\".uc(sprintf(\"%2.2x\",ord(\$1)))/eg;'"

# If the third parameter contains "F", output will go to a file

function getURL() {
    command="curl --cookie-jar $COOKIES.new --cookie $COOKIES --silent --location"
    [ -z "$3" ] && command="$command --output /dev/null"
    command="$command $2 \"$1\""
    if [ "$3" != "${3/F/}" ]; then
        FILE="$0"
        while [ -e "$FILE" ]; do FILE="$FPRE$((++FILECOUNT)).html"; done
        command="$command | tee \"$FILE\""
    fi
    [ "$3" == "F" ] && command="$command > /dev/null"
    [ -z "$SILENT" ] && echo $command >&2
    eval $command
    [ $? == 0 ] || "Error: Command exited with status $?"
    [[ -z "$SILENT" && -e $COOKIES ]] && diff $COOKIES $COOKIES.new >&2
    mv -f $COOKIES.new $COOKIES
}

LOGINURI=$URI/KINTERA_Sphere/login/asp/login.asp?use=yes
getURL "$LOGINURI" "--data LoginName=$LOGIN --data Password=$PASS" ""

CMSURI="$URI/kintera_sphere/comm/cms_website/cmsWebsiteList.aspx"
CMS=$(getURL "$CMSURI" "" "T")

ACID=$(echo "$CMS" | sed -e '/acid=/!d' -e 's/^.*acid=\([^&]*\)&.*$/\1/')
SITEID=$(echo "$CMS" | sed -e '/ShowMenu.*Marijuana Policy Project/!d' \
                           -e "s/^.*ShowMenu([^,]*,[[:space:]]*'\([^']*\)'.*\$/\1/")

SITEURI="$URI/kintera_cms/welcome/start.asp?acid=$ACID&wsid=$SITEID"

CNTURI=$(getURL "$SITEURI" "" "TF" \
         | sed -e '/Content Management/!d' -e 's/^.*<a.*href="\([^"]*\)".*$/\1/')
CID=$(echo "$CNTURI" | sed -e 's/^.*{\([^}]*\)}.*$/\1/')

[ -z "$SILENT" ] && echo "ACID/SITEID/CID: \"$ACID\" / \"$SITEID\" / \"$CID\"" >&2

FNEWURI="$URI/kintera_cms/content/folder.asp?cid=%7B$CID%7D&new=1"
getURL "$FNEWURI" "" ""

URLFOLDER=$(eval echo "$FOLDER" \| $urlEncode)
FMAKEURI="$URI/kintera_cms/content/folder.asp?cid=\{$CID\}"
args="--data cid=%7B$CID%7D --data forminited=1 --data folder_title=$URLFOLDER --data createbtn=1"
for var in folder_id folderDelete noopener targetURL folder_description; do
    args="$args --data $var="
done
getURL "$FMAKEURI" "$args" ""

CEDITURI="$URI/kintera_cms/content/contentedit.asp?cid=\{$CID\}"
FID=$(getURL "$CEDITURI" "" "TF" \
      | sed -e "/$FOLDER/"'!d' -e 's/^.*<option[^>]*value="{\([^"]*\)}"[^>]*>'"$FOLDER"'.*$/\1/')
FID="00F60A45-3D3E-44A9-961D-C775EE8FD158"
echo "FOLDER/FID: \"$FOLDER\"/\"$FID\"" >&2

RELCOUNT=$(echo \
  '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
      <output method="text"/>
      <template match="/"><value-of select="count(//release)"/></template>
   </stylesheet>' \
  | xsltproc --novalid - $SOURCE)

echo "Processing $RELCOUNT releases" >&2

TEMPFILE=text.tmp
CCREATEURI="$URI/kintera_cms/content/contentedit.asp"
#for (( i = 2; i <= 2; i++ )); do
for (( i = 1; i <= RELCOUNT; i++ )); do
    TITLE=$(echo \
        '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
           <param name="index"/>
           <template match="/"><apply-templates select="//release[$index]/title"/></template>
         </stylesheet>' \
        | xsltproc --novalid --param index $i - $SOURCE | sed -e '/<?xml/d')
    [ -z "$SILENT" ] && echo "Adding: \"$TITLE\"" >&2
    echo $TITLE > $TEMPFILE
    eval cat $TEMPFILE \| $urlEncode > $TEMPFILE.new
    TITLE=$(cat $TEMPFILE.new)

    echo \
        '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
           <param name="index"/>
           <template match="/"><apply-templates select="//release[$index]/subtitle[1]/node()"/></template>
           <template match="node()|@*"><copy><apply-templates select="node()|@*"/></copy></template> 
         </stylesheet>' \
        | xsltproc --novalid --param index $i - $SOURCE | sed -e '/<?xml/d' > $TEMPFILE
    eval cat $TEMPFILE \| $urlEncode > $TEMPFILE.new
    SUBTITLE=$(cat $TEMPFILE.new)

    DATE=$(echo \
        '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
           <param name="index"/>
           <output method="text"/>
           <template match="/"><value-of select="//release[$index]/@date"/></template>
         </stylesheet>' \
        | xsltproc --novalid --param index $i - $SOURCE)
    args=$(echo $DATE | perl -p -e 's/^(\d{4})(([1-9]\d)|0(\d))(([1-9]\d)|0(\d))/--data content_year=$1 --data content_month=$3$4 --data content_day=$6$7/')

    echo \
        '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
           <param name="index"/>
           <template match="/"><apply-templates select="//release[$index]/text/*"/></template>
           <template match="node()|@*"><copy><apply-templates select="node()|@*"/></copy></template> 
         </stylesheet>' \
        | xsltproc --novalid --param index $i - $SOURCE | sed -e '/<?xml/d' > $TEMPFILE
    eval cat $TEMPFILE \| $urlEncode > $TEMPFILE.new
    CONTENT=$(cat $TEMPFILE.new)

    echo \
        '<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
           <param name="index"/>
           <template match="/"><apply-templates select="//release[$index]/text/p[1]"/></template>
           <template match="node()|@*"><copy><apply-templates select="node()|@*"/></copy></template> 
         </stylesheet>' \
        | xsltproc --novalid --param index $i - $SOURCE | sed -e '/<?xml/d' > $TEMPFILE
    eval cat $TEMPFILE \| $urlEncode > $TEMPFILE.new
    SUMMARY=$(cat $TEMPFILE.new)

    args="$args --data cid=%7B$CID%7D --data content_folder_id=%7B$FID%7D --data content_folder_title=$URLFOLDER --data content_title=$TITLE --data content_copyright=Copyright+2006 --data content_author=MPP+Staff --data content_body=$CONTENT --data content_subtitle=$SUBTITLE --data content_summary=$SUMMARY"
     
    for arg in bin_id content_id archive_id noopener revert formvalidate opener_submit updatebtn targetURL opener_content_id opener_content_title opener_content_nowysiwyg template template_id copy_template_id author_name editor_name content_date_created content_date_modified content_segmentation_id_list content_staging_id readonly content_comment; do
        args="$args --data $arg="
    done

    for arg in forminited createbtn content_searchable content_nowysiwyg; do
        args="$args --data $arg=1"
    done

    for arg in blnBinContentEdit content_deleted content_writeprotect content_readonly content_template content_share_with_parent content_share_with_child content_counter; do
        args="$args --data $arg=0"
    done

    REALLYSILENT="$SILENT"
    #SILENT=T
    getURL "$CCREATEURI" "$args" ""
    [ -z "$REALLYSILENT" ] && SILENT=
done

#&content_keywords=tests
