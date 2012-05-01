<?php
// This only works with pdflib 2.0

$pdffilename = "hello.pdf";
$fontname = "Helvetica-Bold";
$fontsize = 24.0;
$fontencoding = 4; /* winansi */
$a4_width = 595.0;
$a4_height = 842.0;

$fp = fopen($pdffilename, "w");
$pdf = pdf_open($fp);
pdf_set_info_creator($pdf, "pdf_hello.php3");
pdf_set_info_author($pdf, "Will Holcomb");
pdf_set_info_title($pdf, "Will Holcomb's first document");

pdf_begin_page($pdf, $a4_width, $a4_height);
pdf_set_font($pdf, $fontname, $fontsize, 4);
pdf_set_text_pos($pdf, 50, 700);
pdf_show($pdf, "Will kicks ass and looks good doing it.");
pdf_set_text_pos($pdf, 55, 600);
pdf_show($pdf, "Line number two");
pdf_set_text_pos($pdf, 60, 500);
pdf_show($pdf, "Line number three");
pdf_set_text_pos($pdf, 60, 400);
pdf_show($pdf, "Line number four is too long to fit on one line becasue I wanted to see what would happen.");
pdf_end_page($pdf);
 
$pdf = pdf_close($pdf);
fclose($fp);
echo "<A HREF='$pdffilename'>Read Me</A>";
?>
