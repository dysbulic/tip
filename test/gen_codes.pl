#!/usr/bin/perl

for($row = 0; $row < 256; $row++) {
    print "    <tr>\n";
    printf("      <th>&amp;#x%02x?;</th>\n", $row);
    for($col = 0; $col < 16; $col++) {
        printf("      <td>&#x%02x%x;</td>\n", $row, $col);
    }
    print "    </tr>\n";
}
