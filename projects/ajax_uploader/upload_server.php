#!/usr/bin/php
<?php

/* To prevent multiple simultaneous executions, take out an exclusive read lock on the script */

$fd = fopen($argv[0], "r");
if(!flock($fd, LOCK_EX + LOCK_NB)) {
  exit; // already running
}

set_time_limit(0);  // Set time limit to indefinite execution
$address = 0; // bind all addresses '192.168.0.100';
$port = 9877;
$maxClients = 10;

// Array that will hold client information
$clients = Array();

$socket = socket_create(AF_INET, SOCK_STREAM, 0);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1); // allow rebinding of the address
socket_bind($socket, $address, $port) or die('Could not bind to address');
socket_listen($socket);

function error(&$client, $reason) {
  print "Error: $reason\n";
  if(!isset($client['errors'])) $client['errors'] = array();
  array_push($client['errors'], $reason);
}

function respond(&$client) {
  socket_write($client['socket'], "HTTP/1.0 200 OK\r\n");
  socket_write($client['socket'], "Content-Type: text/html\r\n\r\n");
  // I'd like to move this off into a template:
  // Like 'php response_template.php, but with the environment defined by this script 
  // Like 'require('response_template.php'), but reading the result into a variable (to put through the socket)
  $html = "<html>\n";
  $html .= "  <head><title>POST Result for " . $client['posturi'] . "</title></head>\n";
  $html .= "  <body>\n";
  $html .= "    <h1>POST Result for " . $client['posturi'] . "</h1>\n";
  if(isset($client['errors'])) {
    $html .= "    <h2>Errors:</h2>\n";
    $html .= "    <ul>\n";
    for($i = 0; $i < count($client['errors']); $i++) {
      $html .= "      <li>" . $client['errors'][$i] . "</li>\n";
    }
    $html .= "    </ul>\n";
  }
  if($client['itemcount'] > 0) {
    $html .= "<h2>Items: " . $client['itemcount'] . ":" . count($client['items']) . "</h2>\n";
    $html .= "    <table>\n";
    $html .= "      <tr><th>id</th><th>length</th><th>content type</th><th>substr(content, 0, 100)</th></tr>\n";
    for($i = 0; $i < count($client['items']); $i++) {
      $item = $client['items'][$i];
      $html .= "      <tr>\n";
      $html .= "        <td>" . $item['name'] . "</td>";
      $html .= "<td>" . strlen($item['content']) . "</td>";
      $html .= "<td>" . $item['headers']['Content-Type'] . "</td>";
      $html .= "<td>" . substr($item['content'], 0, 100) . "</td>\n";
      $html .= "      </tr>\n";
    }
    $html .= "    </table>\n";
  }
  $html .= "  </body>\n";
  $html .= "</html>\n";
  socket_write($client['socket'], $html);
  socket_close($client['socket']);
  unset($client['socket']);
}

function processHeaderLine(&$item, $line) {
  if(substr($line, 0, 1) == " ") {
    $line = trim($line);
    $item['headers'][$item['lastheader']] .= " " . $line;
  } else {
    if(!preg_match('/^(?P<name>\S+):\s+(?P<value>.*)$/', $line, $match)) {
      return "Invalid header: $line";
    } else {
      $item['headers'][$match['name']] = $match['value'];
      $item['lastheader'] = $match['name'];
    }
  }
}

$done = false;
while(!$done) {
  $read = Array(); // array of potentially ready sockets
  $read[0] = $socket;
  for($i = 0; $i < $maxClients; $i++) {
    if(isset($clients[$i]['socket'])) array_push($read, $clients[$i]['socket']);
  }
  $readyCount = socket_select($read, $write = NULL, $except = NULL, NULL); // blocks

  // if the socket ready to read is the server socket, that means there is a new connection
  if(in_array($socket, $read)) {
    for($i = 0; $i < $maxClients; $i++) {
      if(!isset($clients[$i]['socket'])) {
        $clients[$i]['socket'] = socket_accept($socket);
        $clients[$i]['state'] = 'method';
        break;
      } elseif($i == $maxClients - 1) {
        $clientSocket = socket_accept($socket);
        socket_write($clientSocket, "HTTP/1.0 503 Service Unavailable\r\n\r\n");
        $html = "<html><body>Server is full currently; please try again...</body></html>";
        socket_write($clientSocket, $html);
        socket_close($clientSocket);
      }
    }
    --$readyCount;
  }
  if($readyCount <= 0) continue;
  for($i = 0; $i < $maxClients; $i++) {
    $client = &$clients[$i];
    if(in_array($client['socket'] , $read)) { // check each client socket
      $input = socket_read($client['socket'] , 1024);
      if($input == null) unset($clients[$i]); // Zero length string meaning disconnected

      if(isset($client['length'])) $client['length'] += strlen($input);
      
      /* At this point, doing the upload is a state machine. We are
       * 1. Reading the HTTP method
       * 2. Reading the main HTTP headers
       * 3. Waiting for a MIME content break
       * 4. Waiting for a MIME header
       * 5. Reading MIME headers
       * 6. Reading a MIME section
       * 
       */
      
      $lines = preg_split("/\r\n/", $input);
      $currentIndex = 0;
      while(count($lines) > 0) {
        $line = array_shift($lines);
        // because the read goes to a line break, the text always ends in a line break
        // which creates an empty element at the end of the array
        if($line == "" && count($lines) == 0) continue;

        switch($client['state']) {
        case 'method':
          // This state is the start state and reads the first line of the request:
          // POST /test_upload.php?id=identifier HTTP/1.1
          if(!preg_match('/^(?P<method>\S+)\s+(?P<uri>\S+)\s+(?P<version>\S+)\s*$/', $line, $match)) {
            error($client, "Invalid POST: $line");
          } else {
            $client['posturi'] = $match['uri'];
          }
          $client['state'] = 'mainheaders';
          $client['itemcount'] = 0;
          $client['items'] = Array();
          break;
        case 'mainheaders':
          if($line == "") {
            $client['state'] = 'breakwait';
            $client['length'] = 0;
            if(!isset($client['headers']['Content-Type'])) {
              error($client, "No Content-Type in POST");
              //print_r($client['headers']);
            } elseif(!preg_match('/^\s*(?P<type>[^\s;]+)(;\s*(?P<qualifier>\S+))?/',
                                 $client['headers']['Content-Type'], $match)) {
              error($client, 'Invalid Content-Type: ' . $client['headers']['Content-Type']);
            } else {
              if($match['type'] == 'multipart/form-data') {
                $qualifier = $match['qualifier'];
                if(!preg_match('/boundary=(?P<boundary>\S+)/', $qualifier, $match)) {
                  error($client, 'No MIME boundary found: ' . $qualifier);
                } else {
                  $client['mime_boundary'] = '--' . $match['boundary'];
                }
              } else {
                error($client, 'Unknown MIME type: ' . $match['type']);
              }
            }
          } else {
            if($errorMessage = processHeaderLine($client, $line)) error($client, $errorMessage);
          }
          break;
        case 'breakwait':
          $line = trim($line);
          if($line != "") {
            if($line == $client['mime_boundary']) {
              $client['state'] = 'mimeheaders';
              $client['itemcount']++;
            } else {
              error($client, 'Junk looking for MIME boundary: ' . $line);
            }
          }
          break;
        case 'mimeheaders':
          $item = &$client['items'][$client['itemcount'] - 1];
          if($line == "") {
            $client['state'] = 'content';
            $item['content'] = "";
            if(!isset($item['headers']['Content-Disposition'])) {
              error($client, "No Content-Disposition in MIME section");
            } else {
              if(!preg_match('/form-data;\s*name="(?P<name>[^"]*)"(;\s*filename="(?P<file>[^"]*)")?/',
                             $item['headers']['Content-Disposition'], $match)) {
                error($client, 'Unknown MIME Content-Type: ' . $item['headers']['Content-Disposition']);
              } else {
                $item['name'] = $match['name'];
                if(isset($match['file']) && $match['file'] != "") $item['file'] = $match['file'];
              }
            }
          } else {
            if($errorMessage = processHeaderLine($item, $line)) {
              error($client, $errorMessage);
            }
          }
          break;
        case 'content':
          $item = &$client['items'][$client['itemcount'] - 1];
          if($line == $client['mime_boundary']) {
            $client['state'] = 'mimeheaders';
          } elseif($line == $client['mime_boundary'] . "--") {
            $client['state'] = 'done';
            respond($client);
          }
          if($client['state'] != 'content') {
             $item['content'] = substr($item['content'], 0, strlen($item['content']) - 2); // strip \r\n
             var_dump($item);
          } else {
            /* Using the split input for the content has the potential to malform
             * certain input, so avoid that...
             *
             * The input can't start with the content boundary or one of the previous
             * statements will be true for the first line.
             */
            $boundaryIndex = strpos($input, "\r\n" . $client['mime_boundary'], $currentIndex);
            if($boundaryIndex === false) { // the rest of the string is content
              $endIndex = strlen($input);
            } else {
              $endIndex = $boundaryIndex + 2; // include the \r\n since it will be removed later
            }
            $item['content'] .= substr($input, $currentIndex, $endIndex - $currentIndex);
            if($boundaryIndex !== false) { // skip over the content lines
              while(count($lines) > 0 && strpos($lines[0], $client['mime_boundary']) != 0) {
                $line = array_shift($lines);
                $currentIndex += strlen($line) + 2;
              }
            } else {
              $lines = array(); // how to clear the array?
            }
          }
          break;
        default:
          error($client, 'Unknown State: ' . $client['state']);
        }
        $currentIndex += strlen($line) + 2;
      }
    }
  }
}
socket_close($socket);
flock($fd, LOCK_UN); // release the lock
fclose($fd);
?>
