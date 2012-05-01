<?php
  header('HTTP/1.0 200 OK'); // to avoid chunked data

  $session_path = dirname($_SERVER['SCRIPT_FILENAME']) . "/sessions/";
  $enabled = (is_dir($session_path) || @mkdir($session_path));
  
  session_save_path($session_path); 
  if($enabled) session_start();
  print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
?>
<?php $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'] ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>AJAX Uploads</title>
    <link rel="stylesheet" href="../styles/main.css" type="text/css"/>
    <style type="text/css">
      html, body { height: 100%; }
      h1, h2, h3 { text-align: left; margin-bottom: 0; }
      table { border-collapse: collapse; }
      th, td { border: 1px solid; text-align: left; padding: 0 .5em 0 .5em; }
      [name~='formtarget'], #props, #posttrace, #uploadserver { border: inset; height: 15em; overflow: auto; margin-left: 1em; width: 100%; }
      [name~='formtarget']:hover, #props:hover, #posttrace:hover, #uploadserver:hover { background-color: #FED; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <p class="warn">This code is not maintained and should not be used. For current code, see <a href="http://pecl.php.net/package/uploadprogress"><acronym title="PHP Extension Community Library">PECL</acronym>'s upload progress meter</a>.</p>
    <h1>AJAX Uploads</h1>
    <p>File uploads have long been an ugly spot in developing interfaces using HTML. Let's I've got a photo gallery. I don't know how many photos they're going to upload, so I don't know how many fields to show. Also, there is the possilibity of an error in the upload process, especially if the upload is sizable.</p>
    <p>The solution I like the best is the attachment field in <a href="http://gmail.com">Google Mail</a>'s composition interface. Each time you add a new attachment, it creates a new file input. That's simple enough, but what is cool is that it uploads the file in the background without reloading the page. This gets around the all or nothing upload process. The problem is only that: I have no idea how to do this.</p>
    <p>I guess the first place to start is with exactly which characteristics are avialable to the script that will be doing the uploading&hellip;</p>
    <form enctype="multipart/form-data" method="post" action="" onsubmit="return false">
      <div>
        <code>uploadProps: </code><input id="uploadProps" type="file" name="file" size=""></input>
        <input type="button" value="printProperties('uploadProps', 'props')" onclick="printProperties('uploadProps', 'props')"></input>
      </div>
    </form>
    <h2>Props:</h2>
    <div id="props"><pre id="printProperties">function printProperties(elementName, holderName) {
  element = document.getElementById(elementName);
  var count = 0;
  holder = document.getElementById(holderName);
  while(holder.childNodes.length > 0) holder.removeChild(holder.lastChild);
  for(prop in element) {
    if(count++ % 10 == 0) {
      table = document.createElement("table");
      holder.appendChild(table);
      header = document.createElement("tr");
      for(headerTitle in {"property":0, "type":1, "value":2}) {
        header.appendChild(document.createElement("th"));
        header.lastChild.appendChild(document.createTextNode(headerTitle));
      }
      table.appendChild(header)
    }
    row = document.createElement("tr");
    row.appendChild(document.createElement("td"));
    row.lastChild.appendChild(document.createTextNode(elementName + "[" + prop + "]"));
    row.appendChild(document.createElement("td"));
    try {
      row.lastChild.appendChild(document.createTextNode(typeof(element[prop])));
    } catch(exception) {
      row.lastChild.appendChild(document.createElement("em"));
      row.lastChild.lastChild.appendChild(document.createTextNode("exception"));
    }
    row.appendChild(document.createElement("td"));
    try {
      row.lastChild.appendChild(document.createTextNode(element[prop]));
    } catch(exception) {
      row.lastChild.appendChild(document.createTextNode(exception));
    }
    table.appendChild(row)
  }
}</pre></div>
    <script type="text/javascript">eval(document.getElementById("printProperties").firstChild.data)</script>
    <p>Not much useful there that I can see&hellip; <a href="http://blog.joshuaeichorn.com/archives/2006/03/14/php-ajax-file-upload-progress-meter-updates/">Joshua Eichorn</a> uses the <code>target</code> property on the <code>&lt;form&gt;</code> to change an <code>&lt;iframe&gt;</code> and leave the main page unaffected. I want to try that:</p>
    <form enctype="multipart/form-data" method="post" action="upload_props.php" target="formtarget" >
      <div>
        <input type="hidden" name="id" value="identifier"></input>
        <code>loadIFrame: </code><input type="file" name="file" size=""></input>
        <input type="submit" value="Upload"></input>
      </div>
    </form>
    <p>The submission actually has to go somewhere, so I made a little php page to show the results of the submission:</p>
    <h2>Target <code>&lt;iframe&gt;:</h2>
    <iframe name="formtarget" src="upload_props.phps"></iframe>
    <p>The problem is this page isn't called until after the file is completely uploaded. It is easy enough to deal with uploads, but so far as tracking progress, what can you do?</p>
    <p>What I'd like to do is to start the upload and then post <code>XMLHttpRequest</code>s to a <acronym title="Simple Object Access Protocol">SOAP</acronym> service that could return the file's size. The issue is two fold. Though I can use the files' general format to guess which files in the upload temp directory are current uploads, there is no way to know which file is associated with which upload. The second problem is that even if I were to match a file with an upload, how do I know the total file size so as to calculate a completion percentage?</p>
    <p>From looking on the internet, these problems are dealt with in three main fashions:</p>
    <ol>
      <li>Simply take the newest file matching the filename format for an upload and assume that it is the upload. So far as knowing the total size, you don't.</li>
      <li>Patch the php server to allow access to the post headers before the upload is complete.</li>
      <li>Do the upload with a <a href="http://www.perl.com"><acronym title="Practical Extraction and Report Language">perl</acronym></a> script which gives better access to the post parameters then hand off the rest of the handling to a php script.</li>
    </ol>
    <p>I don't really like any of these:</p>
    <ol>
      <li>This doesn't scale at all and doesn't provide for real progress.</li>
      <li>Many people are not administrators on the systems that host their sites. They don't have the option of patching the server and if they do it keeps them from being able to update automatically.</li>
      <li>This one is the best, but it requires perl which I have promised not to use after some non-web scripts were acidentally executed because they were in my web hierarchy.</li>
    </ol>
    <p>Is it not possible to do this in straight php? I can't use php's upload processing because it doesn't let me know the temporary file names or the total file size. Could I use php to do the uploads on my own? PHP has <a href="http://us2.php.net/manual/en/ref.sockets.php">socket functions</a>. Could I use those to recieve the <code>POST</code> and then pass the information on to another script?</p>
    <p>The problems are going to be twofold. One is how to execute the script to open the socket. Normally scripts are called in conjunction with a call to the server, but this script needs to be running simultaneously with the upload. Seems like I have two options:</p>
    <ul>
      <li><strong>Asynchronous:</strong> use php's <code>exec</code> function to kick off a server in the background. The server could handle multiple connections and do checking so there's only a single instance running. There is no particular reason that the server need be written in php other than it is likely installed on the server being used.</li>
      <li>
        <p><strong>Synchronous:</strong> I could use a couple <code>XMLHttpRequest</code>s to start the server and pass back a port number to connect to. This would keep everything running within Apache, but it seems a little complex since each upload would have to terminate and so different sockets would have to be used. What would the process look like?</p>
        <ol>
          <li><code>XMLHttpRequest</code> to get a port number (and the port would have to be reserved somehow)</li>
          <li><code>XMLHttpRequest</code> to start the server</li>
          <li><code><acronym title="HyperText Transfer Protocol">HTTP</acronym> POST</code> of the uploaded information to the given port</li>
          <li>multiple <code>XMLHttpRequest</code>s to track the progress</li>
          <li>the previously started server would end and the <code>XMLHttpRequest</code> would return</li>
        </ol>
        <p>Ports can't be shared because the server has to end. Also, there are limits on execution time for scripts though and I'm not even sure that the upload server script would be allowed to complete.</p>
        <p>All in all, it might be possible, but it doesn't seem to be the best solution in an environment that offers the ability to use the asynchronous option.</p>
      </li>
    </ul>
    <h2>HTTP POSTS</h2>
    <hr />
    
    <p>In either case I have to handle the <code>POST</code>s which I know pretty much nothing about. One simple way to start is to just point a form at the locl machine and see what comes though.</p>
    <p><em>Quick question, does anyone know how to do away with the <code>mkfifo</code> call in this code?</em></p>
    <pre><code>mkfifo web_loop;
cat web_loop | nc -lp 8080 > web_loop;</code></pre>
    <form enctype="multipart/form-data" method="post" action="http://localhost:8080/test_upload.php?id=identifier" target="formtarget">
      <div>
        <input type="hidden" name="id" value="identifier"></input>
        <code>localhost POST</code>: <input type="file" name="file" size=""></input>
        <input type="submit" value="Upload"></input>
      </div>
    </form>
    <h2>Sample Post Trace:</h2>
    <div id="posttrace"><pre>POST /test_upload.php?id=identifier HTTP/1.1
Host: localhost:8080
User-Agent: Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.0.2) Gecko/20060308 Firefox/1.5.0.2
Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5
Accept-Language: en-us,en;q=0.5
Accept-Encoding: gzip,deflate
Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7
Keep-Alive: 300
Connection: keep-alive
Referer: http://wholcomb.intranet/sites/himinbi.org/odin/ajax_uploader/
Content-Type: multipart/form-data; boundary=---------------------------1019292671580723810704877633
Content-Length: 544

-----------------------------1019292671580723810704877633
Content-Disposition: form-data; name="id"

identifier
-----------------------------1019292671580723810704877633
Content-Disposition: form-data; name="file"; filename="test.txt"
Content-Type: text/plain

Test text... Wisom some mime in it:
-----------------------------147483316912648177091998097157
Content-Disposition: form-data; name="id"

identifier
-----------------------------147483316912648177091998097157--

-----------------------------1019292671580723810704877633--</pre></div>

    <p>This looks pretty straightforward. <code>HTTP</code> headers are one per line with continuations allowed if they are whitespace indented. They are separated from the body by a blank line. The body then just has boundaries followed by some more headers and then the content. Simple enough. One possible issue is that the <code>Content-Length</code> is for the whole <code>POST</code> and not just the file bit. There could be multiple files in the same post. It is possible to include a <code>Content-Length</code> header on the file entry but <a href="http://www.mozilla.com">Firefox</a> at least doesn't seem to use it.</p>

    <p>So long as only one file is uploaded at a time though the total size and file size should be relatively close.</p>

    <?php
      //exec("php upload_server.php &");
      pclose(popen("php upload_server.php &", "r"));
    ?>

    <form enctype="multipart/form-data" method="post" action="http://<?php print $_SERVER['HTTP_HOST'] ?>:9877/test_upload.php?id=identifier" target="uploadserver">
      <div>
        <input type="hidden" name="id" value="identifier"></input>
        Upload Server: <input type="file" name="file" size=""></input>
        <input type="submit" value="Upload"></input>
      </div>
    </form>

    <h2>Simple Upload Server:</h2>
    <iframe id="uploadserver" name="uploadserver" src="upload_server.phps"></iframe>

    <p>While the upload is being processed, I want to be able to query as to it's status. I was thinking I'd use SOAP, but to do the query I need some way to identify the upload. The user should only be on one upload page at a time, so I can embed a <acronym title="Unique Identifier">UID</acronym> in the form.</p>

  </body>
</html>
