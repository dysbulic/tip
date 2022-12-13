<?php print '<?xml version="1.0" encoding="utf-8"?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Website Statistics</title>
    <link rel="stylesheet" type="text/css" href="http://odin.himinbi.org/styles/main.css" />
    <link rel="stylesheet" type="text/css" href="http://odin.himinbi.org/styles/table.css" />
    <style type="text/css">
      table { border-collapse: collapse; width: 100%; }
      td { padding: .25em; }
      .site {
        width: 95%;
        margin: auto;
      }
      .site a {
        font-size: 20pt;
        font-weight: bold;
      }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Website Statistics Archive</h1>
    <p>I maintain a few different websites and like to have some idea
     of how much traffic they are getting. So, I am running <a
     href="http://awstats.sourceforge.net/">awstats</a> to keep track.
     This page is an index to those statistics.</p>
    <?php
      # The reports directory contains directories of the form
      #  stats_YYYY_MM and each of those contains html files of
      #  the form awstats.DOMAIN.html. I just have to go through, and
      #  make a structure where $sites[DOMAIN][YYYY][MM] is a boolean
      #  which is true if that report exists.
    
      $sites = array();
      $lastmods = array();
      $reportdir = opendir("reports");
      while($statdir = readdir($reportdir)) {
        $statdir = "reports/" . $statdir;
        if(is_dir($statdir) &&
           ereg("stats_([[:digit:]]+)_([[:digit:]]+)", $statdir, $match)) {
          $year = intval($match[1]);
          $month = intval($match[2]);
          $statdirhandle = opendir($statdir);
          while($report = readdir($statdirhandle)) {
            if(ereg("awstats\.(.+\.(localdomain|net|org|info|[[:alpha:]]{2}))\.html",
                    $report, $match)) {
              $domain = $match[1];
              $filetime = filemtime("$statdir/$report");
              $lastmods[$domain] = (!isset($lastmods[$domain])) ? $filetime :
                                    max($lastmods[$domain], $filetime);
              $sites[$domain][$year][$month] = true;
            }
          }
          closedir($statdirhandle);
        }
      }
      closedir($reportdir);

      function report_link($domain, $time) {
        return ("reports/stats_" . date("Y_m", $time) . "/" .
                "awstats.$domain.html");
      }

      if(is_callable("xslt_create")) {
        $xslt_engine = xslt_create();
        $xsl = "<stylesheet version=\"1.0\" xmlns=\"http://www.w3.org/1999/XSL/Transform\">
                  <output method=\"text\"/>
                  <template match=\"/\">
                    <value-of select=\"/xml/section[@id='domain']/table/tr/td[position()=3]\"/>
                  </template>
                </stylesheet>";
        $arguments = array('/_xsl' => $xsl);
      }
      
      foreach(array_keys($sites) as $site) {
        printf('<div class="site"><div><a href="http://%s">http://%s</a></div>', $site, $site);
        printf('<div>(Last Modified: <a href="%s">%s</a>)</div>' . "\n",
               report_link($site, $lastmods[$site]),
               date("F d Y H:i:s", $lastmods[$site]));
        print "  <table>\n";
        $years = array_keys($sites[$site]);
        sort($years);
        foreach($years as $year) {
          print "    <tr><th>$year</th>\n";
          for($month = 1; $month <= 12; $month++) {
            print '      <td>';
            if(isset($sites[$site][$year][$month])) {
              $time = strtotime("$year/$month/1");
              print ("<a href=\"". report_link($site, $time) . "\">" .
                     date("F", $time) . "</a>");
            }
            if(isset($xslt_engine)) {
              $datafile = sprintf("data/awstats%02d%04d%s.txt",
                                  $month, $year, $site);
              if(is_file($datafile)) {
                $hits = xslt_process($xslt_engine, $datafile, 'arg:/_xsl', NULL, $arguments);
                print "(" . $hits . ")";
              }
            }
            print "</td>\n";
          }
          print "    </th>\n";
        }
        print "  </table>\n";
        print "</div>\n";
      }
      if(isset($xslt_engine)) {
        xslt_free($xslt_engine);
      }
    ?>
    <hr />
    <h3>
      Generated with <a href="http://www.php.net">PHP</a> version:
      <?php print phpversion() ?> 
    </h3>
  </body>
</html>
