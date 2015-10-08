#!/usr/bin/perl

my %check;
$check{command} = "/usr/sbin/traceroute -w2 -m20 %H";
$check{name} = "traceroute";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/host -a %H";
$check{name} = "name server lookup";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/nmblookup -A %H";
$check{name} = "net bios info lookup";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/sbin/safe_finger -l \@\%H";
$check{name} = "finger";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/nc %H 11";
$check{name} = "remote systat";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/nc %H 15";
$check{name} = "remote netstat";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/whois -h whois.geektools.com %H";
$check{name} = "Geektools whois";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/snmpwalk %H public system";
$check{name} = "Public SNMP walk (system only)";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/bin/snmpwalk %H private";
$check{name} = "Private SNMP walk";
$check{padding} = "   ";
push @checks, \%check;

# Unnecessary when using geektools; they proxy arin
#
#my %check;
#$check{command} = "/usr/bin/whois -h whois.arin.net %H";
#$check{name} = "Arin whois";
#$check{padding} = "   ";
#push @checks, \%check;

my %check;
$check{command} = "/usr/bin/nmap -sS -sU -O %H";
$check{name} = "portscan";
$check{padding} = "   ";
push @checks, \%check;

my %check;
$check{command} = "/usr/sbin/lsof -i";
$check{name} = "active network connections (local)";
$check{padding} = "   ";
push @checks, \%check;

foreach $site (@ARGV) {
    foreach $check (@checks) {
	print "Running $$check{name}:\n";
	$_ = $$check{command};
	s/%H/$site/;
	open(HANDLE, $_ . " 2>&1 " . "|") or print "Error: Could not run $$check{name}: ($_)";
	undef $lastline;
	while(<HANDLE>) {
	    s/\s+$//;
	    s/^\s+//;
	    if(defined($lastline) || /\S/) {
		print $$check{padding} . $_ . "\n";
		$lastline = $_;
	    }
	}
	close(HANDLE);
	$_ = $lastline;
	if(/\S/) {
	    print "\n";
	}
    }
}
