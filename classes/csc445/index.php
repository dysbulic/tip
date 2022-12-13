<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>CSC445: Languages and Machines</title>
    <link rel="stylesheet" href="../../styles/main.css" type="text/css" />
    <style type="text/css">
    </style>
  </head>
  <body>
    <p>This is my homework from when I was taking languages and machines. It was a fun class and I decided to learn <a href="">LaTeX</a> to do my homework in. You can see a definite progression between the first and last assignments though really altering how TeX displays is still serious voodoo to me.</p>
<ul>
<?php
for($i = 1; $i <= 6; $i++) {
  printf('<li>Homework #%d (' .
         '<a href="homework_%02d.tex"><acronym title="&#x3C4;&#x3AD;&#x3C7;&#x3BD;&#x3B7;">TeX</acronym></a> | ' .
         '<a href="homework_%02d.pdf"><acronym title="Portable Document Format">PDF</acronym></a>)</li>' . "\n", $i, $i, $i);
}
?>
</ul>
  </body>
</html>
