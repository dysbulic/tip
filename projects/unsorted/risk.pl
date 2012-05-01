#!/usr/bin/perl

# What are the probabilities of winning a Risk attack?
#  http://www.hasbro.com/risk/
# The rules are that a person may attack with 1-3 die and the
#  defender may respond with 1-2 or die (but never more than
#  were used to attack). Ties in rolls go to the defending
#  side.
# I would like to know what the probabilities of each of the
#  possible outcomes from a roll.
# This has some shortcuts on paper, but I think
#  that the computer would deal better with it by just brute
#  forcing through everyting...

$die_max = 6;
$max_a_die = 3;
$max_d_die = 2;

for($num_a_die = 1; $num_a_die <= $max_a_die; $num_a_die++) {
    for($num_d_die = 1; $num_d_die <= $max_d_die; $num_d_die++) {
        undef @a_dice;
        for($i = 0; $i < $num_a_die; $i++) {
            $a_dice[$i] = 1;
        }
        do {
            @a_dice_s = sort(@a_dice);
            # The number of die that matter are <= to the number of defending die
            $a_id = "";
            for($i = 0; $i <= $#a_dice && $i < $num_d_die; $i++) {
                $a_id .= $a_dice_s[$#a_dice_s - $i];
            }

            # If this combo has not been checked yet, check it
            if(!defined($a_rolls{$a_id}{$num_d_die})) {
                undef @d_dice;
                for($i = 0; $i < $num_d_die; $i++) {
                    $d_dice[$i] = 1;
                }
                do {
                    @d_dice_s = sort(@d_dice);
                    $wins = 0;
            
                    # Count how many it wins
                    for($i = 0; $i <= $#d_dice_s && $i <= $#a_dice_s; $i++) {
                        if($a_dice_s[$#a_dice_s - $i] > $d_dice_s[$#d_dice_s - $i]) {
                            $wins++;
                        }
                    }
                    # print "@a_dice_s v @d_dice_s => Wins: $wins\n";

                    $a_rolls{$a_id}{$num_d_die}{$wins}++;
                } while(inc_die(@d_dice));
            }
            while(($index, $count) = each(%{$a_rolls{$a_id}{$num_d_die}})) {
                $wins{$num_a_die}{$num_d_die}{$index} += $count;
            }
        } while(inc_die(@a_dice));
    }
}

print "Defender's Losses at Risk:\n\n";
print "Defending |  Attacking Die\n";
print " Die      |";
for($i = 1; $i <= $max_a_die; $i++) {
    printf "%30d |", $i;
}
print "\n";
print "-" x (11 + 31 * $max_a_die + $max_a_die) . "\n";
for($d_index = 1; $d_index <= $max_d_die; $d_index++) {
    $max_men = 0;
    undef %total_count;
    for($i = 1; $i <= $max_a_die; $i++) {
        while(($men, $count) = each(%{$wins{$i}{$d_index}})) {
            $max_men = $men if $men > $max_men;
            $total_count{$i} += $count;
        }
    }
    for($men = 0; $men <= $max_men; $men++) {
        printf "%9d |", $d_index;
        for($a_index = 1; $a_index <= $max_a_die; $a_index++) {
            $count = $wins{$a_index}{$d_index}{$men};
            if($count > 0) {
                printf(" %d %s: %4d/%-4d pos (%05.2f%%) |",
                       $men, $men == 1 ? "man" : "men",
                       $count, $total_count{$a_index},
                       $count / $total_count{$a_index} * 100);
            } else {
                print " " x 31 . "|";
            }
        }
        print "\n";
    }
    print "-" x (11 + 31 * $max_a_die + $max_a_die) . "\n";
}
                   


# foreach $roll (sort(keys %a_rolls)) {
#     printf "%02dx => %2d (", $roll, $a_rolls{$roll}{count};
#     $total{count} += $a_rolls{$roll}{count};

#     for($i = 0; $i <= $max_wins; $i++) {
#         printf("%2d", $a_rolls{$roll}{wins}[$i]);
#         print "/" if $i < $max_wins;
#         $wins = $a_rolls{$roll}{count} * $a_rolls{$roll}{wins}[$i];
#         $total{wins}[$i] += $wins;
#         $total += $wins;
#     }
#     print ")\n";
# }
# printf("total: %d (", $total{count});
# for($i = 0; $i <= $max_wins; $i++) {
#     printf("%d [%.2f%%]", $total{wins}[$i], 100 * $total{wins}[$i] / $total);
#     print " / " if $i < $max_wins;
# }
# print ")\n";


sub inc_die {
    my $done = 0;
    $index = $#_;
    do {
        $_[$index]++;
        if($_[$index] > $die_max) {
            $_[$index] = 1;
            $index--;
        } else {
            $done = true;
        }
    } while($index >= 0 && !$done);
    return $index >= 0;
}

sub max {
    $max = shift(@_);
    foreach $v (@_) {
        $max = $v if $v > $max;
    }
    return $max;
}
