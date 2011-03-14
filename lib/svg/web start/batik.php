<?php
if(!isset($_GET['svg'])):
  header('HTTP/1.1 500 Internal Server Error');
  fpassthru(fopen("error.html", 'r'));
else:
  header('Content-type: application/x-java-jnlp-file');
  print '<?xml version="1.0" encoding="utf-8"?' . ">\n"
?>
<jnlp spec="1.0+"
      codebase="http://<?php print $_SERVER['HTTP_HOST'] . ereg_replace("[^/]*$", "", $_SERVER['SCRIPT_NAME']) ?>"
      href="<?php print ereg_replace("^[^?]*/", "", $_SERVER['REQUEST_URI']) ?>">
  <information>
    <title>Batik Squiggle Viewer</title>
    <vendor>Apache Batik Project</vendor>
    <homepage href="http://xmlgraphics.apache.org/batik/"/>
    <description>Batik Squiggle SVG Rendering Application</description>
    <!-- <icon href="little_state.png"/> -->
    <!-- <offline-allowed/> -->
  </information>

  <!-- A .htaccess causes these to redirect to http://www.ibiblio.org/maven/ -->
  <resources>
    <j2se version="1.4+"/>
    <jar href="batik/jars/batik-squiggle-1.6.jar"/>
    <jar href="batik/jars/batik-squiggle-ext-1.6.jar"/>
    <jar href="batik/jars/batik-dom-1.6.jar"/>
    <jar href="batik/jars/batik-css-1.6.jar"/>
    <jar href="batik/jars/batik-svg-dom-1.6.jar"/>
    <jar href="batik/jars/batik-ext-1.6.jar"/>
    <jar href="batik/jars/batik-gvt-1.6.jar"/>
    <jar href="batik/jars/batik-parser-1.6.jar"/>
    <jar href="batik/jars/batik-script-1.6.jar"/>
    <jar href="batik/jars/batik-bridge-1.6.jar"/>
    <jar href="batik/jars/batik-swing-1.6.jar"/>
    <jar href="batik/jars/batik-transcoder-1.6.jar"/>
    <jar href="batik/jars/batik-gui-util-1.6.jar"/>
    <jar href="batik/jars/batik-awt-util-1.6.jar"/>
    <jar href="batik/jars/batik-util-1.6.jar"/>
    <jar href="batik/jars/batik-xml-1.6.jar"/>
    <jar href="xerces/jars/xercesImpl-2.8.0.jar"/>
    <jar href="xml-apis/jars/xml-apis-1.3.03.jar"/>
    <jar href="rhino/jars/js-1.6R2.jar"/>
  </resources>

  <?php
    $image = 'http://' . $_SERVER['HTTP_HOST'];
    if(!ereg('^/', $_GET['svg']))
      $image .= ereg_replace("[^/]*$", "", $_SERVER['SCRIPT_NAME']);
    $qIndex = strpos($_GET['svg'], "?");
    if(!$qIndex) {
      $image .= $_GET['svg'];
    } else {
      $image .= substr($_GET['svg'], 0, $qIndex) . "?" . ereg_replace(" ", "+", substr($_GET['svg'], $qIndex + 1));
    }
  ?>

  <application-desc main-class="org.apache.batik.apps.svgbrowser.Main">
    <argument><?php print $image ?></argument>
  </application-desc>
</jnlp>

<?php
endif;
?>
