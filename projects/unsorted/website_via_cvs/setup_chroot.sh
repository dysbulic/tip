#!/bin/bash

# Script to hard link the files from a normal redhat distribution
#  of apache into a chroot jail
#
# This script assumes that a separate is appropriately mounted at
#  files and contains the var files as well as the actual files being
#  served. Also that httpd.conf has been appropriately modified.
#
# 2002/06/05 - wjh

CHROOT_BASE=/usr/local/httpd

[ ! -d $CHROOT_BASE ] && echo "Chroot base \"$CHROOT_BASE\" not found" && exit

cd $CHROOT_BASE

# Gets all the files under a certain directory
#
function recursively_link {
    rvalue=0
    for file in $(find $1); do
	get_file $file $2 $3 || rvalue=1
    done
    return $rvalue
}

# The function takes three arguments:
#  1. Filename to retrieve
#  2. Part of the filename to remove (optional)
#  3. Directory to preface the new filename with (optional)
#
function prep_filename() {
    file=$1

    if [ "${file#$2}" != "$file" ]; then
	add_preface=true
	file=${file#$2}
    fi

    # Assume we won't be placing files in the root
    while [ "${file#/}" != "$file" ]; do
	file=${file#/}
    done
    
    # Add the preface if something was taken off the front
    if [ $add_preface ]; then
	preface=$3
	if [[ "$preface" != "" && "${preface%/}" == "$preface" ]]; then
	    preface="$preface/"
	fi
	file=$preface$file
    fi
    echo $file
}

# Retrieves a file from the existing file system and places a hard link in
#  the current directory. There are tree handled cases:
#  1. File is a directory: a new directory of the same name is created
#  2. File is a normal file: a hard link to that file is created in the
#      appropriate directory structure
#  3. File is a symlink: the file symlinked to is gotten and the appropriate
#      symlink is created
#
# The function takes three arguments: (which are passed to prep_filename)
#  1. Filename to retrieve
#  2. Part of the filename to remove (optional)
#  3. Directory to preface the new filename with (optional)
#
# The return status of the function is true if any changes are made, or
#  false otherwise
#
function get_file() {
    # This is pretty simple;
    #  1. check for the type
    #  2. create the directory structure
    file=$1
    cutfile=$(prep_filename "$@")

    retvalue=0
    if [ -L $file ]; then
	preface=${file%/*}/
	linkedfile=$(readlink $file)
	while [ "${linkedfile#../}" != $linkedfile ]; do
	    linkedfile=${linkedfile#../}
	    preface=${preface%/?*}/
	done
	linkedfile=$preface$linkedfile

	relativelink=$(prep_filename $linkedfile $2 $3)
	preface=${cutfile%/*}/
	
	[ ! -e $preface ] && mkdir -pv $preface

	while [ "${preface%%/*}" == "${relativelink%%/*}" ]; do
	    preface=${preface#*/}
	    relativelink=${relativelink#*/}
	done
	while [ "$preface" != "" ]; do
	    preface=${preface#*/}
	    relativelink=../$relativelink
	done

	if [ ! -e $cutfile ]; then
	    ln -sv $relativelink $cutfile
	    retvalue=1
	fi
	get_file $linkedfile $2 $3 || retvalue=1
    elif [[ "$cutfile" != "" && ( ! -e "$cutfile" || "$file" -ot "$cutfile" ) ]]; then
	if [[ -d $file && ! -e $cutfile ]]; then
	    mkdir -vp $cutfile
	elif [ ! -d $file ]; then
	    dir=${cutfile%/*}
	    if [[ $cutfile != $dir && ! -e $dir ]]; then
		mkdir -vp $dir
	    fi
	    ln -v $file $cutfile
	    retvalue=1
	fi
    fi
    return $retvalue
}

for file in $(find /etc/httpd/conf); do
    get_file $file /etc/httpd/conf/ etc
done

# Create links
#
for link in home-files/home var-files/var usr-.; do
    [ ! -e ${link%-*} ] && ln -vs ${link#*-} ${link%-*}
done

# Populate the sbin directory
#
for file in ab apachectl httpd logresolve rotatelogs suexec; do
    get_file /usr/sbin/$file /usr
done

# Populate the modules directory
#
for file in /usr/lib/apache/*; do
    get_file $file /usr/lib/apache/ modules
done

# Provide interpreters for cgi scripting
#
for file in perl python; do
    get_file /usr/bin/$file /usr
done

# Get supporting libraries for interpreters
#
for file in /usr/lib/php4/ /usr/lib/perl5/ /usr/lib/python1.5/ /usr/share/locale/; do
    recursively_link $file /usr/
done

for file in /lib/security; do
     recursively_link $file
done

# Link the libraries necessary to run
#
finished=false
while [ $finished = "false" ]; do
    finished=true
    for lib in $(ldd $(find lib/ bin/ sbin/ modules/ -type f -perm +111) | \
	perl -e 'while(<>) { print "$_\n" if ($_, $_) = (/(=>) (\S+).*/); }' | \
	sort | uniq); do
      [ -e $lib ] && get_file $lib
      [ $PIPESTATUS -eq 1 ] && finished=false
    done
done

# These libraries are not linked, but are used by apache
#
for lib in /lib/libnss_*; do
    get_file $lib
done

for file in group localtime hosts mime.types passwd shadow resolv.conf php.ini; do
    get_file /etc/$file
done

recursively_link /etc/httpd/conf/ /etc/httpd/conf/ etc/

# Create special files
#
[ ! -e dev ] && mkdir dev
[ ! -e dev/null ] && mknod dev/null c 1 3
[ ! -e dev/random ] && mknod dev/random c 1 8
