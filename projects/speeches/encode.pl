#!/usr/bin/perl

$lame_args = "-v -b 112 -h -o";
if(!$ARGV[0]) {
    print "Usage: encode.pl tracklist [basedir]\n";
    print "  For each line, \$i, in \$tracklist:\n";
    print "    Split line in to \$title -- \$artist\n";
    print "    lame $lame_args --tt \"\$title\" --ta \"\$artist\" --tn \$i\n";
    print "         \"\$basedir/track\$i.cdda.wav\" \"\$artist - \$title.mp3\"\n";
    exit 0;
}

open(LIST, "<", $ARGV[0]) or die "Couldn't open \"$ARGV[0]\"\n";

if($ARGV[1]) {
    $basedir = $ARGV[1] . "/";
}

while(<LIST>) {
    $i++;
    $source = sprintf("%strack%02d.cdda.wav", $basedir, $i);
    if(! -r $source) {
	warn "Cannot read \"$source\"; skipping\n";
    } else {
	s/^\s+//;
	s/\s+$//;    
	($title, $artist) = split/\s*--\s*/;
	$title =~ s/\.+$//;
	$destination = $basedir . $artist . " - " . $title . ".mp3";
	$tag_args = "--tt \"$title\" --ta \"$artist\" --tn $i";
	$command = "lame $lame_args $tag_args \"$source\" \"$destination\"";
	system "$command" or warn "Warning: $command failed\n";
    }
}

close LIST;
