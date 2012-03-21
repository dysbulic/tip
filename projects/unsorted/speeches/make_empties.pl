#!/usr/bin/perl

# I need files to exist to give the proper error from apache.
# Doing otherwise would require me changing the apache config
# which as a now demoted user I can no longer do...

foreach $disc ((["disc-1.list", "Best of Times, Worst of Times"],
                ["disc-2.list", "The Changing World"],
                ["disc-3.list", "The Dreams, The Inspirations, The Accomplishments"],
                ["disc-4.list", "The Political Arena"])) {
    open(LIST, "<", $$disc[0]) or die "Couldn't open \"$$disc[0]\"\n";

    while(<LIST>) {
        s/^\s+//;
        s/\s+$//;    
        ($title, $artist) = split/\s*--\s*/;
        $title =~ s/\.+$//;
        $file = $$disc[1] . "/" . $artist . " - " . $title . ".mp3";
        $file =~ s/\"/\\\"/g;
        $command = "touch \"$file\"";
        system $command;
        die "Warning: Could not: $command ($?)\n" if $? != 0;
    }
    close LIST;
}
