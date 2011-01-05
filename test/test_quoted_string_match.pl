#!/usr/bin/perl

while(<>) {
    print "hi \"$1\" : \"$2\" : \"$3\"\n" if /^\'([^\'\\]|\\.)*\'$/;
}
