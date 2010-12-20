<?php
$inframe = ( isset($_SERVER['HTTP_REFERER']) &&
             preg_match("'" . $_SERVER['SCRIPT_NAME'] . "'", $_SERVER['HTTP_REFERER']) );
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Internal Frame Test<?php if($inframe) print " (inframe)" ?></title>
    <style type="text/css">
      body { background-color: white; }
      body {
        margin: 2em; 
        background-color: <?php print $inframe ? 'green' : 'orange' ?>;
      }
      iframe { width: 100%; height: 20em; border: 1px solid;  }
      [name~=url] { width: 30em; }
    </style>
    <script src="jquery/dist/jquery.js" type="text/javascript"></script>
    <?php if(!$inframe): ?>
    <script type="text/javascript">//<![CDATA[
      var msg = $('<div/>');
      var retryCount = 0;
      $(function() {
        $('body').append(msg);
        $('#frame').bind('load', function() { retryCount = 0; testStyle(); });
        $('form').submit(function() {
          try {
            $('#frame').attr('src', $('[name=url]').val());
          } finally {
            return false;
          }
        });
        $('#frame').attr('src', 'javascript_watch.html');
      });
      // Has to be defined in global scope for setTimeout
      function testStyle() {
        var iframe = $('#frame').get(0);
        var iDoc = iframe.contentDocument;

        try {
          // Body is undefined even when readyState is complete in Firefox 3.6
          if( !iDoc ||
              (iDoc.readyState && iDoc.readyState != 'complete' )
              || $('body', iDoc).length == 0 ) {
            // Uses a global variable for readyBound
            //$(iDoc).ready(testStyle);
            retryCount++;
            msg.append($('<p/>').text('Waiting: ' + (new Date()).toString()));
            if(retryCount > 50) {
              for(prop in iframe) {
                msg.append($('<p/>').text(prop + ' = ' + iframe[prop]));
              }
              throw "Retries exhausted";
            }

            setTimeout('testStyle()', 100);
            return;
          }
        } catch(e) {
          msg.append($('<p/>').text('Error: ' + e));
        }

        var styles = document.styleSheets;
        var iStyles = iDoc.styleSheets;
        msg.empty();
        msg.append($('<p/>').text('Local Stylesheets: ' + styles.length));
        msg.append($('<p/>').text('Frame Stylesheets: ' + iStyles.length));
        if( iStyles.length > 0 ) {
          msg.append($('<p/>').text
           ('Rule #1: ' + iStyles[iStyles.length - 1].cssRules[0].cssText +
            ' ➜ ' + styles[styles.length - 1].cssRules[0].cssText));

          try {
            //iStyles[iStyles.length - 1] = iDoc.cloneNode(styles[styles.length - 1]);
          } catch(e) {
            msg.append($('<p/>').text('Error: ' + e));
          }

          iStyles = iDoc.styleSheets;
          msg.append($('<p/>').text
           ("Rule #1: " + iStyles[iStyles.length - 1].cssRules[0].cssText +
            ' ≟ ' + styles[styles.length - 1].cssRules[0].cssText));


          try {
            //iStyles[iStyles.length - 1].cssRules[0].cssText = styles[styles.length - 1].cssRules[0].cssText;
          } catch(e) {
            msg.append($('<p/>').text('Error: ' + e));
          }

          iStyles = iDoc.styleSheets;
          msg.append($('<p/>').text
           ("Rule #1: " + iStyles[iStyles.length - 1].cssRules[0].cssText +
            ' ≟ ' + styles[styles.length - 1].cssRules[0].cssText));
        }

        var style = window.location.toString().replace(/[^\/]*$/, 'blue_background.css');
        var link = $('<link/>').attr({ rel: 'stylesheet',
                                       type: 'text/css',
                                       href: style });
        $('head', iDoc).append(link);
        $('body', iDoc).prepend($('<p/>').text('From containing frame'));
        msg.append($('<p/>').text('Frame Stylesheets: ' + iStyles.length));
      }
    //]]></script>
    <?php endif; ?>
  </head>
  <body>
    <?php if( !$inframe ): ?>
      <iframe id="frame" src="<?php print $_SERVER['SCRIPT_NAME'] ?>"></iframe>
      <p>
        <form action="">
          <input type="text" name="url" value="." />
          <input type="submit" value="Go" />
        </form>
      </p>
    <?php else: ?>
      <div>Testing</div>
    <?php endif ?>
  </body>
</html>
