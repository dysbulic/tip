<?php
/**
 * I have to figure out what to expand. There are two sources: things that were already expanded
 * (from a cookie) and things to be newly expanded (from the post parameters).
 */
$pathes = array();
if(isset($_POST['expand'])) $pathes = array_merge($pathes, $_POST['expand']);
if(isset($_COOKIE['expanded'])) {
  for($i = 0; $i < count($_COOKIE['expanded']) && $_COOKIE['expanded'][$i] != ' '; $i++) {
    array_push($pathes, $_COOKIE['expanded'][$i]);
  }
}
if(isset($_POST['collapse'])) {
  $pathes = array_diff($pathes, $_POST['collapse']);
}
$pathes = array_unique($pathes);

for($i = 0; $i < count($pathes); $i++) {
  setcookie("expanded[$i]", $pathes[$i]);
}
setcookie("expanded[$i]", ' '); // marks the end of valid entries

print('<?xml version="1.0" encoding="UTF-8"?' . ">\n")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Tree View</title>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript" src="treemenu.js"></script>
    <link type="text/css" href="treemenu.css" rel="alternate stylesheet" title="Base Tree Style"/>
    <link type="text/css" href="file_tree.css" rel="stylesheet" title="File Tree Style"/>
    <style type="text/css">
      #makelink {
        border: 1px solid;
        margin: 0 10%;
        padding: 1em;
      }
      #makelink a {
        display: block;
        text-decoration: none;
        padding: .5em .1em;
        width: 10em;
        margin-left: 30%;
        text-align: center;
        color: ButtonText;
        background-color: ButtonFace;
        border: 2px outset ButtonShadow;
      }
      #makelink a:hover {
        padding: .6em 0em .4em .2em;
        border-style: inset;
      }
    </style>
    <!--[if lt IE 7]>
    <link type="text/css" href="file_tree.ie.css" rel="stylesheet" title="File Tree Style"/>
    <![endif]-->
  </head>
  <body>
    <h1>Tree Menu</h1>
    <p>This is a structure for presenting tree-based hierarchies via HTML. It is designed to deal with large trees while maintaining fault-tolerance and standards compliance.</p>
    <ol>
      <li>Vanilla XHTML without javascript:
      <ul>
        <li>Plain <code>ul</code> structure</li>
        <li>Uses <code>POST</code> requests to change the state of the tree (maintaining <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html">HTTP/1.1 idempotence</a>)</li>
        <li>Tree state is saved in session cookies modified by the server</li>
        <li>Files:
        <ul>
          <li><a href="index.phps"><code>index.php</code></a>: this file</li>
          <li><a href="treemenu.css"><code>treemenu.css</code></a>: basic style to show a <code>ul</code> as a tree</li>
          <li><a href="file_tree.css"><code>file_tree.css</code></a>: style adding file and folder icons to the tree</li>
          <li><a href="file_tree.ie.css"><code>file_tree.ie.css</code></a>: style correcting IE&apos;s display differences</li>
        </ul>
        </li>
      </ul>
      </li>
      <li>XHTML with javascript:
      <ul>
        <li><code>POST</code> requests are replaced with <code>GET</code>s accessing a <acronym title="Representational State Transfer">REST</acronym> service</li>
        <li>Children are loaded once and subsequently shown and hidden using javascript</li>
        <li><code>ul</code> structure remains unchanged (children are hidden using <code>position: relative</code> and <code>overflow: hidden</code></li>
        <li>Variables are encapsulated reducing global namespace clutter</li>
        <li>Files:
        <ul>
          <li><a href="rest_filelist.phps"><code>rest_filelist.php</code></a>: filelist service
          <ul>
            <li><a href="400 - missing parameter.phps"><code>400 - missing parameter.php</code></a>: if <code>path</code> is not set</li>
            <li><a href="403 - forbidden.phps"><code>403 - forbidden.php</code></a>: if a descending path (<code>..</code>) is specified</li>
            <li><a href="406 - not acceptable.phps"><code>406 - not acceptable.php</code></a>: if <code>text/xml</code> is not acceptable</li>
          </ul>
          </li>
          <li><a href="../javascript_compatability/compatability.js"><code>compatability.js</code></a>: IE compatability library</li>
          <li><a href="treemenu.js"><code>treemenu.js</code></a>: treemaking code</li>
        </ul>
        </li>
      </ul>
      </li>
    </ol>
    <script type="text/javascript">
      function makeTree() {
        getTree('files');
        var makelink = document.getElementById("makelink");
        makelink.parentNode.removeChild(makelink);
      }
    </script>
    <div id="makelink">
      <p>No javascript has been run yet in this page. All of the state information is being managed via cookies.</p>
      <a href="javascript:makeTree()">Make DHTML Tree</a>
    </div>
<?php
#define("MAXDEPTH", 4);
#define("MAXENTRIES", 100);
define("MAXDEPTH", '');
define("MAXENTRIES", '');
define("FILEPREFIX", "..");

function searchdir($dir, $depth, &$filecount) {
  global $pathes;
  if(is_string(MAXDEPTH) || $depth < MAXDEPTH) {
    $dirhandle = opendir(FILEPREFIX . $dir);
    $files = array();
    while($filename = readdir($dirhandle)) {
      if($filename != "." && $filename != "..")
        if(substr($filename, 0, 1) != "." && substr($filename, -1, 1) != "~")
          array_push($files, $filename);
    }
    if(count($files) > 0) {
      sort($files);
      foreach($files as $filename) {
        if(++$filecount > MAXENTRIES && !is_string(MAXENTRIES)) break;
        $fullpath = "$dir/$filename";
        $fileID = preg_replace("/^\//", "", $fullpath);
        $isOpen = is_dir(FILEPREFIX . $fullpath) && !is_link(FILEPREFIX . $fullpath) && in_array($fullpath, $pathes);
        printf("      %" . ($depth * 2) . "s<li id=\"%s\" class=\"%s\">\n", '',
               $fileID, is_dir(FILEPREFIX . $fullpath) ? 'group' . (!$isOpen ? ' closed' : '') : 'leaf');
        if(is_dir(FILEPREFIX . $fullpath)) {
          printf("        %" . ($depth * 2) . "s<form class=\"tree-control\" method=\"post\" action=\"#%s\"><div>\n",
                 '', $fileID);
          printf("          %" . ($depth * 2) . "s<input type=\"hidden\" name=\"%s[]\" value=\"%s\"></input>\n",
                 '', in_array($fullpath, $pathes) ? 'collapse' : 'expand', $fullpath);
          printf("          %" . ($depth * 2) . "s<input type=\"submit\" value=\"\"></input>\n", '');
          printf("        %" . ($depth * 2) . "s</div></form>\n", '');
        }
        printf("      %" . ($depth * 2) . "s<a href=\"%s$fullpath\">$filename</a>", '', FILEPREFIX);
        // print(" (" . $filecount . ")");
        if($isOpen) {
          print("\n");
          printf("    %" . ($depth * 2) . "s<ul>\n", "");
          searchdir($fullpath, $depth + 1, $filecount);
          printf("    %" . ($depth * 2) . "s</ul>\n", "");
          printf("      %" . ($depth * 2) . "s", "");
        }
        print("</li>\n");
      }
    }
    closedir($dirhandle);
  }
}
?>
    <ul id="files">
      <?php $count = 0; searchdir("", 0, $count); ?>
    </ul>
    <script type="text/javascript">
      //var fileTree = getTree("files");
    </script>
  </body>
</html>
