#!/usr/bin/perl

use strict vars;

# A simple program to give the number of days between two dates

my @max_days = ( 31, 28, 31, 30, 31, 30,
                 31, 31, 30, 31, 30, 31 );
my $days; # accumulator

sub add_days {
    my $count = $max_days[$_[1] - 1];
    if($_[1] == 2 && $_[0] % 4 == 0 && $_[0] % 100 != 0) {
        $count += 1;
    }
    if($_[2]) {
        $count -= $_[2];
    }
    $days += $count;
#    printf("%05d: Added %02d day%s for %d %s\n",
#           $days, $count, ($count != 1 ? "s" : ""), $_[0], $months[$_[1] - 1]);
}

sub parse_date {
    my @split = split('/', $_[0]);
    return { year => $split[0], month => $split[1], day => $split[2] };
}

if(scalar(@ARGV) < 2) {
    print "Usage: $0 start_date end_date\n";
    print "Counts the number of days between the start and end dates\n";
    print "The date format is yyyy/mm/dd\n";
    print "Exe: $0 2003/07/11 2003/09/23\n";
} else {
    my @dates = ( parse_date($ARGV[0]), parse_date($ARGV[1]) );
    
    my $year = $dates[0]{year};
    my $month = $dates[0]{month};

    # Assume good data currently

    # The span may either cross several months or be within the current one:
    # The number of days is completely within this month:
    if($dates[0]{year} == $dates[1]{year} &&
       $dates[0]{month} == $dates[1]{month}) {
        $days = $dates[1]{day} - $dates[0]{day};
    }
    # If the years or months are different it spans multiple ones and the
    #  number of days in this month is to the end of the month:
    else {
        add_days($year, $month++, $dates[0]{day});
        while($year <= $dates[1]{year}) {
            while($month <= 12 &&
                  !($year == $dates[1]{year} && $month >= $dates[1]{month})) {
                add_days($year, $month++);
            }
            $month = 1;
            $year++;
        }
        $days += $dates[1]{day};
    }

#    printf("From $ARGV[0] to $ARGV[1] is $days day%s\n",
#           ($days != 1 ? "s" : ""));
    print $days;
}
