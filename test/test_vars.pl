#!/usr/bin/perl

$a = "25";
$b = 25;
$c = 102;
$d = "Joe is ";
$e = "forty";

push @test, "$a gt $c"; # interpolated
push @test, '$a < $c';
push @test, '$a eq $b';
push @test, '$a == $b';
push @test, 'print $d + $a . " "';
push @test, 'print $d . $a . " "';
push @test, 'print $d + $e . " "';

foreach $test (@test) {
    print "$test is ";
    (eval $test) ? print "true\n" : "false\n";
}

