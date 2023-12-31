<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Great Speeches</title>
    <link rel="stylesheet" type="text/css" href="../styles/outline.css"/>
    <style type="text/css">
      table { width: 100%; }
      .filename { padding-left: 2em; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <hr />
    <h1 style="color: red;">Notice: These files are no longer accessible. Copyright concerns have forced me to remove them. I apologize.</h1>
    <hr />
    <p>These are mp3's created from a set of cd's from <a href="http://www.rhino.com">Rhino Records</a> of <a href="http://www.rhino.com/search/NumberSearch.lasso?Number=70567">The Great Speeches of the 20<super>th</super> Century</a>. The cd's were made in the 90's so they go up as for as the first Bush presidency. All of this information should be public domain, so though the process of ripping cd's, encoding their contents and putting them in the internet is usually considered <a href="http://www.modernhumorist.com/mh/0004/propaganda/mp3.cfm">illicit</a>, I think I am legit. On the subject, while hunting down these track lists I noticed Rhino had some <a href="http://www.rhino.com/fun/napster">interesting psa's</a>.</p>
    <p>There are variable bitrate mp3&apos;s and may act strangely in some players. They have been tested in <a href="http://www.winamp.com">winamp</a>, <a href="http://www.mplayerhq.hu">mplayer</a> and <a href="http://www.microsoft.com/windows/windowsmedia/">Windows Media Player</a>. Some of the recordings are unclear or have volume fluxuations, but that is not the encoding, it is a function of the original from the equipment used to record them.</p>
    <p>Thanks to the <a href="http://www.xiph.org/paranoia/">cdparanoia</a> and <a href="http://www.mp3dev.org">lame</a> projects for their excellent software.</p>
<?php
$extension = ".tracklist.txt";
foreach(glob("*$extension") as $file) {
  $dirname = substr($file, 0, strlen($file) - strlen($extension));
  print "<h2>$dirname</h2>\n";
  print "<ol>\n";
  $lines = file($file);
  foreach($lines as $line_num => $line) {
    $line = rtrim($line);
    $audio = preg_split('/\\s+--\\s+/', $line);
    printf("  <li><a href='%s/%s'>$audio[0] - $audio[1]</a></li>\n",
           rawurlencode($dirname), rawurlencode("$audio[0] - $audio[1].mp3"));
  }
  print "</ol\n";
  // 
  // $stat = stat($fullfile);
  // $stat['size'] / 1024) . ' Kb'
}
?>
    <hr />
    <div style="text-align: center;">
      <a href="http://www.w3.org/Style/CSS/"><img src="http://www.himinbi.org/images/valid_css.png" alt="Made with CSS"/></a>
      <a href="http://www.apache.org/"><img src="http://www.himinbi.org/images/apache_logo.png" alt="Apache Powered"/></a>
      <a href="http://www.slashdot.org/"><img src="http://www.himinbi.org/images/linux_inside.png" alt="Linux Inside"/></a>
      <a href="http://www.w3.org/TR/xhtml1/"><img src="http://www.himinbi.org/images/xhtml_validated.png" alt="Valid XHTML"/></a>
      <a href="http://burnallgifs.org/"><img src="http://www.himinbi.org/images/burn_all_gifs.png" alt="Burn All Gifs"/></a>
    </div>
  </body>
</html>
