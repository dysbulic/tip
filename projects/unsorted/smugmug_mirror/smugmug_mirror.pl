#!/usr/bin/perl

use strict;
use LWP::UserAgent;
 
if($#ARGV < 0) {
    print << 'EOH';
    Creates a mirror of a smugmug account to a gallery account.
    
    Usage: smugmug_mirror.pl <smugmug url> <gallery url>
    Example: smugmug_mirror.pl http://marcvalentin.smugmug.com http://odin.himinbi.org/gallery/
EOH
    exit 0;
}

my $useragent = LWP::UserAgent->new;
my $response = $useragent->get($ARGV[0]);
if ($response->is_success) {
    print $response->content;  # or whatever
} else {
    die $response->status_line;
}
