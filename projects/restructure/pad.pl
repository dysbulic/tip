#!/usr/bin/perl

$blank_format = " %2d ";
$format = " %8.3f ";

while(<>) {
    chomp;
    @values = split/\s/;
    printf($format, $values[0]);
    # 0 out position
    for $i (1..3) {
	printf($blank_format, 0);
    }
    # 0 out body angles
    for $i (1..3) {
	printf($blank_format, 0);
    }
    # 0 out coning angle and spin rate
    for $i (1..2) {
	printf($blank_format, 0);
    }
    # Columns come in:
    #  pitch, yaw, roll : <theta, psi, phi> : (1, 2, 3)
    # and go out:
    #  roll, pitch, yaw : <phi, theta, psi> : (3, 1, 2)
    for $i (3,1,2) {
	printf($format, $values[$i]);
    }
    # 0 out thruster magnitudes
    for $i (1..6) {
	printf($blank_format, 0);
    } 
    print "\n";
}
