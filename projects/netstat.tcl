#!/usr/bin/tcl

if {[llength $argv] == 0} {
    set devname "eth0"
} else {
    set devname [lindex $argv 0]
}

# tx:  0:bytes  1:packets     2:errs        3:drop  4:fifo 
#      5:frame  6:compressed  7:multicast
# rx:  8:bytes  9:packets    10:errs       11:drop 12:fifo
#     13:colls 14:carrier    15:compressed
proc getstats {} {
    upvar devname devname
    set file [open /proc/net/dev r]
    set stats {}
    while {([gets $file line] >= 0) && ($stats == {})} {
        if {[regexp -- "^\\s*${devname}:\\s*(\\S.*)" $line {} line]} {
            regsub -all {\s+} $line { } line
            set stats [split $line { }]
        }
    }
    close $file
    return $stats
}

set start_stats [getstats]
set start_time [clock clicks -millisecond]
set last_stats $start_stats
set last_time $start_time

while {[eof stdin] == 0} {
    after 1000

    set new_stats [getstats]
    set new_time [clock clicks -millisecond]

    set tot_time [expr ($new_time - $start_time) / 1000]
    set tot_tx [expr ([lindex $new_stats 0] - [lindex $start_stats 0]) / 1000]
    set tot_rx [expr ([lindex $new_stats 8] - [lindex $start_stats 8]) / 1000]

    set snap_time [expr ($new_time - $last_time) / 1000.0]
    set snap_tx [expr [lindex $new_stats 0] - [lindex $last_stats 0]]
    set snap_rx [expr [lindex $new_stats 8] - [lindex $last_stats 8]]

    puts [format "For ${devname} after %ds \[snap: %.02fs\]:" \
              $tot_time $snap_time]
    puts [format "  Transmitted: %dkB (%.02fkB/s) \[snap: %dB (%.02fB/s)\]" \
              $tot_tx [expr $tot_tx.0 / $tot_time] \
              $snap_tx [expr $snap_tx.0 / $snap_time]]
    puts [format "     Recieved: %dkB (%.02fkB/s) \[snap: %dB (%.02fB/s)\]" \
              $tot_rx [expr $tot_rx.0 / $tot_time] \
              $snap_rx [expr $snap_rx.0 / $snap_time]]
    set last_stats $new_stats
    set last_time $new_time
}
