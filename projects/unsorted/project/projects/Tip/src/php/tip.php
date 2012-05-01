<?php
/**
Author: Will Holcomb <will@dhappy.org>
Date: October 2009

Version 0.1a: Demo for Grassroots Technology X. Basic forwarding within a hierarchy maintained in a filesystem or database.

Filesystem tricks in use:
  directory link ~will/technoanarchy.org/tip.php# -> ../../../ (tipspace root)

Input:
  * URL
  * POST variables

Output:
  A HTTP response or redirect to a location in the WWW as navigated through Tipspace.
*/

$state = array();
$state['__TIP_DEBUG_LEVEL'] = 0;
$state['__TIP_RETURN_STATE'] = True;
$state['__TIP_RETURN_IN_STATE'] = $state['__TIP_RETURN_STATE'];
$state['__TIP_LOAD_IN_STATE'] = $state['__TIP_RETURN_STATE'];

if($state['__TIP_DEBUG_LEVEL'] > 0) header("Content-type: text/plain");
if($state['__TIP_DEBUG_LEVEL'] > 0) print 'Tipspace 0.1' . "\n\n";

// Maximum depth unpacking an array
$state['__TIP_MAX_PACK_DEPTH'] = 10;
$__TIP_PATH_SEP = '/';

// If a search reaches a file, send it directly to the client
$state['__TIP_GET_NO_READ_INTENTION'] = True;

$NULL = "\0";
$state['__TIP_HOME_DEFAULT'] = 'intro/';

$__TIP_USERNAMES = 'will|wj?holcomb';
$__TIP_HOSTNAMES = '(tip.)?technoanarchy.org';
$__TIP_SCRIPTNAMES = 'tip.php';
$__TIP_SERVER_SEPARATOR = '\\?';
$__TIP_NULL_PREFIXES = "%^(/(~$__TIP_USERNAMES))?(/$__TIP_HOSTNAMES)?(/$__TIP_SCRIPTNAMES)?$__TIP_SERVER_SEPARATOR?%";

$__TIP_NULL_PREFIXES__TIP_DEBUG_LEVEL = 9;

if($state['__TIP_DEBUG_LEVEL'] > $__TIP_NULL_PREFIXES__TIP_DEBUG_LEVEL) print "Null Prefixes: $__TIP_NULL_PREFIXES\n\n";

/**
Case: Null Input

Action: Votrex

Description:
  If one were to imagine Tipspace a fixture mythic kingdom, it would be a cave that one walks into and pops out randomly somewhere else.

  The somewhere else is probably somewhere people can live, but you might get cast into the Void.
*/

function __set() {
  global $state;
  $args = func_get_args();
  $key = (count($args) > 0) ? array_shift($args) : $state['__TIP_DEFAULT_KEY'];
  $value = (count($args) > 0) ? array_shift($args) : $state['__TIP_DEFAULT_VALUE'];

  if($state[$key] != $value) {
    if($state['__TIP_DEBUG_LEVEL'] > 5) print "Setting state['$key'] = '$value' from '${state[$key]}'\n";
    $state[$key] = $value;
    if($key != 'changed') $state['changed'] = True;
  }
  return $state;
}

function __return() {
  global $state;
  $args = func_get_args();
  $out = (count($args) > 0) ? array_shift($args) : $state['__TIP_DEFAULT_RETURN'];
  
  if($state['__TIP_RETURN_IN_STATE'] == True) __set('return', $out);
  return $out;
}

class HTTPAccept {
  public $types;

  public function __construct() {
    $headers = getallheaders();
    $parts = split(';', $headers['Accept']);
    $this->types = split(',', $parts[0]);
  }
}

// Maps the location
// Current mapping is from file paths to files
function __load() {
  global $state, $__TIP_PATH_SEP;
  $args = func_get_args();
  
  if(count($args) == 0) return; 
  $key = pack_list($args);
  
  $out = "";
  // If the key exists as a file
  if(file_exists($key)) {
    __loadfile($key);
  // If the key doesn't exist as a file
  // Check if content exists that matches the accept parameters
  } else {
    $out = search_accept_params();
  }
     /*
       print ('   ' .
              (('p' . (' ' . '=' . ' ') . ('"' . $prefix . '"')) . ', ' .
               ('c'. ' = ' . "\"$candidate\"") . ', ' .
               ('len(p) = ' . strlen($prefix)) . ', ' .
               ('substr(c,len(p)) = ' . substr($candidate, strlen($prefix)))
               )
              . "\n");
      */
  return __return($out);
}

// Loads a variable from a file
function __loadfile() {
  if($state['__TIP_GET_NO_READ_INTENTION']) {
    // If key is only to be sent out, don't read into memory
    readfile($key);
  } else {
    $out = file_get_contents($key);
    if($state['__TIP_LOAD_IN_STATE']) __set($key, $out);
  }
  return $out;
}
 
// Search for a file to load using the accept parameters
function search_accept_params() {
  $accept = new HTTPAccept();
  $acceptables = $accept->types;
  $out = "";
  for($i = 0; $out == "" && $i <= count($acceptables); $i++) {
    $composite = $key . $__TIP_PATH_SEP . $acceptables[$i];
    //print "Testing: $composite\n";
    if(file_exists($composite)) {
      $out = __loadfile($composite);
    }
    if($acceptables[$i] == 'application/xml') {
      search_xml_params();
    }
  }
}
 
function search_xml_params() {
  // ToDo: Search for specific namespaces in the xml/ hierarchy
}

// Globs and cleans inputs
function input() {
  global $state, $__TIP_NULL_PREFIXES;
  $args = func_get_args();

  __set('changed', False);
  foreach(array_keys($_GET) as $key) {
    __set($key, $_GET[$key]);
  }

  $out = (count($args) == 0 ? "" : pack_list($args));
  if($out == "") $out = preg_replace($__TIP_NULL_PREFIXES, '', $_SERVER['REQUEST_URI']);
  if($out == "" && !$state['changed']) $out = $__TIP_DEFHOME;

  if($state['__TIP_DEBUG_LEVEL'] > 3) print 'Input: ' . $out . "\n";
  
  return __return($out);
}

// From: http://www.jonasjohn.de/snippets/php/starts-with.htm
function starts_with($haystack, $needle) {
  return substr($haystack, 0, strlen($needle)) == $needle;
}

$state['pack_depth'] = 0;

function pack_list() {
  global $state;
  $args = func_get_args();

  $state['pack_depth']++;

  $out = "";
  for($i = 0; $i < count($args); $i++) {
    if(!is_array($args[$i])) {
      $out .= $args[$i];
    } else {
      for($j = 0; $j < count($args[$j]); $j++) {
        $out .= ((is_array($args[$i][$j]) && $state['pack_depth'] < $state['__TIP_MAX_PACK_DEPTH'])
                 ? pack_list($args[$i][$j]) : $args[$i][$j]);
      }
    }
  }
  $state['pack_depth'] = 0;

  if($state['__TIP_DEBUG_LEVEL'] > 3) print 'Packed: ' . $out . "\n";

  return $out;
}

$path = input();
$type = __load($path);

#print $path . "\n";
/**
 ToDo:
 Chuck all inputs into an array
 Alphebetize them
 Repeat til match:
 Concatenate list with null separators
 Search database
 Remove list element at random
*/
