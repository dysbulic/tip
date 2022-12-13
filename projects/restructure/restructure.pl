#!/usr/bin/perl

# This is a test of a wincvs commit under NT

%settings = check_args(@ARGV);

if($settings{help}) {
    print << 'EOH';
  restructure.pl
   restructures a ascii text file which is delineated into columns
    separated by spaces or columns.

   -r "[colnum[:tranform]]+" -> restructure
     colnum = column number for the output file
     transform = factor to transform the output by

     exe: -r "3:10 4 5 1:.01 3" would generate an output with the 3rd, 4th,
      5th, 1st and 3rd columns of the input with each element of the first
      3rd column multiplied by 10 and each element of the original 1st
      column (the 4th in the output) multiplied by .01.

   -p -> pad

     exe: -p would generate output columns padded to the length of the
      longest element in the column

   -d "separator" -> output column separator; default: " "

   -i "filename" -> input filename

   -s "rexex" -> input separator regex; default: ",\s+"

   -x -> xmlize

     exe: -> -x would generate output of the form:
      <data name="dataname">
        <column name="colname">
          <point index="1">123456</point>
          <point index="2">123456</point>
        </column>
        <column name="colname">
        </column>
      </data>

   -n "dataname" -> title for the data

    exe: -n "2001Earnings" would put the string 2001Earnings as the name
     property for the xml output or would use the table 2001Earnings as
     the table to insert into in the database.

   -b "[user][:pass]@[hostname][/dbname]" -> insert columns into database url

     exe: will:mypass@localhost/samples will insert the columns into the
      table specified by -n; the table must already be created and have
      column names matching the columns of the input.     

   -h -> help
EOH
    exit 0;
}

if($settings{filename}) {
    if($settings{verbose}) {
	print "Opening file: $settings{filename}\n";
    }
    open(STDIN, '<', $settings{filename}) or warn "Error opening:  \"$settings{filename}\" not found\n.";
}

$line_count = 0;
while(<STDIN>) {
    chomp;
    s/^\s+//;
    if($settings{columns}) {
	@line = split/$settings{input_separator}/;
	for($i = 0; $i <= $#{$settings{columns}}; $i++) {
	    if($line[$settings{columns}[$i]{index}]  =~ /^([+-]?)(?=\d|\.\d)\d*(\.\d*)?([Ee]([+-]?\d+))?$/) {
		$output[$line_count][$i] = $line[$settings{columns}[$i]{index}] * $settings{columns}[$i]{magnitude};
	    } else {
		$output[$line_count][$i] = $line[$settings{columns}[$i]{index}];
	    }
	}
    } else {
	$output[$line_count] = [ split/$settings{input_separator}/ ];
    }
    $line_count++;
}

if($settings{verbose}) {
    print "$line_count line" . ($line_count != 1 ? "s" : "") . " read\n\n";
}

if($settings{column}) {
    if($settings{pad}) {
	for($i = 0; $i <= $#output; $i++) {
	    for($j = 0; $j <= $#{$output[$i]}; $j++) {
		if($output[$i][$j] =~ /^([+-]?)(?=\d|\.\d)\d*(\.\d*)?([Ee]([+-]?\d+))?$/) {
		    @parts = split /\./, $output[$i][$j];
		    if(@parts > 2) {
			warn "\"$output[$i][$j]\" split into " . ($#parts + 1) . " parts\n";
		    }
		    $max_length[$j]{int} = max($max_length[$j]{int}, length $parts[0]);
		    $max_length[$j]{float} = max($max_length[$j]{float}, length $parts[1]);
		}
		$max_length[$j]{whole} = max($max_length[$j]{whole},
					     $max_length[$j]{int} + $max_length[$j]{float} + 1,
					     length $output[$i][$j]);
	    }
	}
	for($i = 0; $i <= $#max_length; $i++) {
	    $print_directive[$i]{float} = "%" . $max_length[$i]{whole} . "." . $max_length[$i]{float} . "f";
	    $print_directive[$i]{string} = "%" . $max_length[$i]{whole} . "s";
	}
	if($settings{verbose}) {
	    print "Max lengths of columns:\n";
	    for($i = 0; $i <= $#max_length; $i++) {
		printf " %20s : %2d %6s : %2d.%02d %9s\n",
		$output[0][$i],
		$max_length[$i]{whole},
		"($print_directive[$i]{string})",
		$max_length[$i]{int},
		$max_length[$i]{float},
		"($print_directive[$i]{float})";
	    }
	}
    } else {
	for($i = 0; $i <= $#{$output[0]}; $i++) {
	    $print_directive[$i]{float} = "%s";
	    $print_directive[$i]{string} = "%s";
	}
    }
    for($i = 0; $i <= $#output; $i++) {
	for($j = 0; $j <= $#{$output[$i]}; $j++) {
	    if($output[$i][$j] =~ /^([+-]?)(?=\d|\.\d)\d*(\.\d*)?([Ee]([+-]?\d+))?$/) {
		printf $print_directive[$j]{float}, $output[$i][$j];
	    } else {
		printf $print_directive[$j]{string}, $output[$i][$j];
	    }
	    if($j != $#{$output[$i]}) {
		print $settings{output_separator};
	    } else {
		print "\n";
	    }
	}
    }
}

if($settings{xml}) {
    print "<data name=\"$settings{name}\">\n";
    for($i = 0; $i <= $#{$output[0]}; $i++) {
	print "  <column name=\"$output[0][$i]\">\n";
	for($j = 1; $j <= $#output; $j++) {
	    if($output[$j][$i]) {
	      print "    <point index=\"$j\">$output[$j][$i]</point>\n";
	  } else {
              print "    <point index=\"$j\"/>\n";
	  }
	}
	print "  </column>\n";
    }
    print "</data>\n";
}

if($settings{database}) {
    if(!$settings{name}) {
	warn "In order to insert into a postgresql database -n must be set\n";
    } else {
	use Pg;
	$options = Pg::conndefaults();
	foreach $option ("host", "user", "password", "dbname") {
	    $statement .= "$option=$settings{database}{$option} " if $settings{database}{$option};
	}
	if($settings{verbose}) {
	    print "Database: $statement\n";
	}
	$db = Pg::connectdb($statement);
	die $db->errorMessage unless PGRES_CONNECTION_OK eq $db->status;
	if($settings{database}{create}) {
	    $statement = "create table $settings{name} (";
	    for($i = 0; $i <= $#{$output[0]}; $i++) {
		$statement .= "\"$output[0][$i]\" numeric";
		if($i < $#{$output[0]}) {
		    $statement .= ", ";
		} else {
		    $statement .= ");";
		}
	    }
	    if($settings{verbose}) {
		print "  creating table with command: \"$statement\"\n";
	    }
	    $result = $db->exec($statement);
	    die $db->errorMessage unless PGRES_COMMAND_OK eq $result->resultStatus;
	}
	for($i = 1; $i <= $#output; $i++) {
	    $statement = "insert into $settings{name} values (";
	    for($j = 0; $j <= $#{$output[$i]}; $j++) {
		$statement .= $output[$i][$j];
		if($j < $#{$output[$i]}) {
		    $statement .= ", ";
		} else {
		    $statement .= ");";
		}
	    }
	    if($settings{verbose}) {
		print "  inserting values with command: \"$statement\"\n";
	    }
	    $result = $db->exec($statement);
	    die $db->errorMessage unless PGRES_COMMAND_OK eq $result->resultStatus;
	}
    }
}

sub max {
    my $max = shift(@_);
    foreach (@_) {
        $max = $_ if $max < $_;
    }
    return $max;
}

sub check_args {
    $settings{output_separator} = " ";
    $settings{input_separator} = qr(,\s*);
    
    for($i = 0; $i <= $#_; $i++) {
        if($_[$i] =~ /-[h]/i) {
	    $settings{help} = 1;
        } elsif($_[$i] =~ /-[v]/i) {
	    $settings{verbose} = 1;
        } elsif($_[$i] =~ /-[x]/i) {
	    $settings{xml} = 1;
        } elsif($_[$i] =~ /-[e]/i) {
            $settings{exp} = 1;
        } elsif($_[$i] =~ /-[p]/i) {
	    $settings{pad} = 1;
        } elsif($_[$i] =~ /-[b]/i) {
	    $settings{database}{create} = 1;
	    $settings{database}{dbname} = "graph";
	    $settings{database}{user} = "will";
	    $settings{database}{password} = "";
	    $settings{database}{host} = "localhost";
	    $i++;
	} elsif($_[$i] =~ /-[c]/i) {
	    $settings{column} = 1;
        } elsif($_[$i] =~ /-[n]/i) {
	    $settings{name} = $_[$i + 1];
	    $i++;
        } elsif($_[$i] =~ /-[i]/i) {
	    $settings{filename} = $_[$i + 1];
        } elsif($_[$i] =~ /-[d]/i) {
	    $settings{output_separator} = $_[$i + 1];
	    $i++;
        } elsif($_[$i] =~ /-[s]/i) {
	    $settings{input_separator} = qr($_[$i + 1]);
	    $i++;
        } elsif($_[$i] =~ /-[r]/i) {
	    @cols = split /\s/, $_[$i + 1];
	    for($j = 0; $j <= $#cols; $j++) {
		@args = split /:/, $cols[$j];
		if(@args > 2) {
		    warn "\"$cols[$j]\" split into parts: (@args). More than one :? \n";
		}
		$settings{columns}[$j]{index} = $args[0] - 1;
		$settings{columns}[$j]{magnitude} = (($args[1]) ? $args[1] : 1);
		$count++;
	    }
	    $i++;
        }
    }

    if($settings{verbose} && $settings{columns}) {
	print "Setting column restructuring information; output columns:\n";
	for($j = 0; $j <= $#{$settings{columns}}; $j++) {
	    print "  output $j = input " . ($settings{columns}[$j]{index} + 1) . " * " . $settings{columns}[$j]{magnitude} . "\n";
	}
    }

    if(!($settings{column} || $settings{xml} || $settings{database})) {
	die "At least one output method has to be selected (-c, -x or -b)\n";
    }
    return %settings;
}
