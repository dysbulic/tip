<?php

  // Try to find a session
if(file_exists('/media/home/tmp/')) session_save_path('/media/home/tmp/');
if(file_exists('/media/tmp/')) session_save_path('/media/tmp/');
session_start();

if(!isset($_SESSION['read:default'])) $_SESSION['read:default'] = false;

// Is this an authorized user via HTTP?
if(!isset($_SERVER['PHP_AUTH_USER']) ||
   $_SERVER['PHP_AUTH_USER'] == "" ||
   $_SERVER['PHP_AUTH_USER'] == '__clean') {
  header('WWW-Authenticate: Basic realm="Madstone"');
  header('HTTP/1.0 401 Unauthorized');
  die("Who are you?");
 }

$_SESSION['last:user'] = $_SESSION['user'];
$_SESSION['user'] = $_SERVER['PHP_AUTH_USER'];
$_SESSION['pass'] = $_SERVER['PHP_AUTH_PASS'];

function bounce($SESSION) {
  if(!isset($SESSION['bounce URI'])) $SESSION['bounce URI'] = "http://google.com";
  $out = <<<EOT
<html>
  <head>
    <meta http-equiv="refresh" content="3;url=${SESSION['bounce URI']}" />
    <title>2010/1/1:DoH:access:http</title>
    <style type="text/css"> a { text-decoration: none; color: black; } </style>
    <style type="text/css"> #W { border-bottom: 1px solid dotted; } </style>
    <style type="text/css"> #W:hover { color: blue; } </style>
  </head>
  <body>
    <h1>There&quot;s nothing here yet. Try back <a href="http://help.dhappy.org">later</a>.</h1>
    <h1>-<a href="mailto:contact@dhappy.org">☮</a><a id="W" href="mailto:will@dhappy.org">W</a></h1>
  </body>
</html>
EOT;
  $SESSION['bounced'] = true;
  die($out);
}

function branch() {} // No branches, yet.

$_SESSION['read:auth'] = $_SESSION['read:default'];
require('users.inc');
if(!$_SESSION['read:auth']) bounce($_SESSION);
branch($session);
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n" ?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Vicious Cycle Engineering Test</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
      .note { margin-left: 2em; border: 3px double; background-color: #EEE; padding: 1em 2em; }
      iframe { width: 100%; border: 1px soild; }
      body { max-width: 800px; }
      li { display: list-item; min-height: 2em; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Vicious Cycle Engineering Test</h1>

    <p>My <a href="http://brett.madstones.com">little bro</a> applied for a software design position. He mentioned working on a challenging pre-test and, since <a href="../paypal/">these things</a> entertain me, I asked for a copy. He did his in about a day, unfortunately I am not as bright as he, and it has taken me two already.</p>

    <?php if(!$authenticated): ?>
    <p>Out of respect to the <a href="http://www.viciouscycleinc.com">Vicious Cycle</a> people and to keep my brother from getting in trouble, the bulk of these questions are not accessible unless you are <a href="?authenticate">authenticated</a>.<!-- The last one was the tough one for me and I the answer might be useful to the world at large, so it is available.--></p>
    <?php endif; ?>

    <blockquote><p>Answer all questions using C or C++ code. This exercise is designed to test your 3D mathematics abilities as well as your overall software design skills. Optimization and efficiency are as important as organization and clarity of code. If you don't know how to approach a problem, then research a solution for it! Please don't leave anything unanswered.</p></blockquote>

    <?php if($authenticated): ?>
    <ul>
      <li>Vectors are defined as <code>float[3]</code> where <code>[0]=x</code>, <code>[1]=y</code>, <code>[2]=z</code>.</li>
      <li>Matrices are defined as <code>float[3][3]</code> where <code>[0][0]</code> is row 1, <code>[1][0]</code> is row 2, <code>[2][0]</code> is row 3.</li>
      <li>Assume standard linear math calls such as <code>CrossProduct</code>, <code>DotProduct</code>, <code>MatMultiply</code>, etc (i.e. no need to implement).</li>
    </ul>
    <?php endif; ?>

    <ol>
      <li id="q1">
        <?php if($authenticated): ?>

        <blockquote><p>A standard deck of playing cards consists of 52 cards, defined as 4 suits of 13 card types (Ace, 2-King). Design code to manage and store a deck, identify card suit and type, and implement a routine to shuffle the deck.</p></blockquote>

        <ul>
          <li><a href="DeckOfCards.cpp">Brett</a></li>
          <li><a href="cards.cpp">Will</a></li>
        </ul>

        <?php endif; ?>
      </li>
      <li id="q2">
        <?php if($authenticated): ?>

        <blockquote>
          <p>Given a set of spheres, each with: arbitrary radius, differing speeds, and differing direction of travel, design a system that updates the positions and detects any collisions among the spheres. Assume the position is updated by a uniform time step and all speeds are constant. Compiled code not required.</p>
          <p>Each sphere has the following attributes:</p>
          <ul>
            <li>Position &mdash; a 3D vector</li>
            <li>Radius &mdash; float value</li>
            <li>Direction of movement &mdash; a 3D unit vector</li>
            <li>Speed &mdash; a float coefficient applied to the direction of movement vector</li>
          </ul>
        </blockquote>

        <ul>
          <li><a href="Spheres.cpp">Brett</a></li>
        </ul>

        <?php endif; ?>
      </li>
      <li id="q3">
        <?php if($authenticated): ?>

        <blockquote><p>Given a camera at point p1 compute a view matrix so that the camera looks at an arbitrary point p2. Assume vector 'up' as the up vector (hint: use {0,1,0} to estimate the true up vector). Compiled code not required.</p></blockquote>

        <ul>
          <li><a href="ViewMatrix.cpp">Brett</a></li>
          <li><a href="camera_position.php">Will</a></li>
        </ul>
        
        <?php endif; ?>
      </li>
      <li id="q4">
        <?php if($authenticated): ?>

        <blockquote>
          <p>What, if any, potential problems could arise from the following function?</p>
          <pre>void* memcpy( void *dest, void *src, size_t size ) {
  byte *pTo = (byte*)dest, *pFrom = (byte*)src;
  assert( dest != NULL &amp;&amp; src != NULL );
  while( size-- > 0 )
    *pTo++ = *pFrom++;
  return (dest);
}</pre>
        </blockquote>

        <ul>
          <li>
            <h2>Brett</h2>
            <p>If src is less than dest and they are accidentally separated by less than size bytes, overlap will cause confusion. Since it starts copying from the bottom up, the part of the data pointed to by src that is offset by more than size bytes will overwritten and then re-copied at the end of dest.</p>
            <p>If the user doesn't specify size correctly, they could unintintionally overwrite other data in memory.</p>
          </li>
          <li>
            <h2>Will</h2>
            <p><code>size_t</code> is an <code>unsigned int</code>, so that isn't a problem. <a href="http://www.google.com/codesearch?q=+bcopy+show:MrBkpH68VKM:77vT5q5HQpo:Aw16BKeyFVY">Google code search</a> shows the problem. Say that <code>src + size > dest</code>. As <code>src</code> is getting copied, it will overwrite bytes that it will later copy to <code>dest</code>. If <code>dest > src</code> then the code should start from the end of the block and copy backwards.</p>
          </li>
        </ul>

        <?php endif; ?>
      </li>
      <li id="q5">
        <blockquote>
          <p>Projectile Leading Problem: Given an initial shot position (Spos), a constant shot speed (Sspeed), an initial target position (Tpos), and a constant target velocity (speed + dir) (Tvel) derive the equation for calculating the shot direction (Sdir) such that at some time (t) the shot would hit the target. Be sure to derive an equation for t as well!</p>
          <p>Write a function to compute these values (Sdir and t).</p>
        </blockquote>

        <ul>
          <li><a href="ProjectileLeading.c">Brett</a></li>
          <li><a href="vector_targeting.php">Wayne</a></li>
          <li><a href="projectile_targeting.php">Will</a></li>
        </ul>
      </li>
    </ol>
  </body>
</html>
