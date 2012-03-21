<?php
/**
 * @package Indiefeed
 * @subpackage Components
 * components/com_podcast/podcast.php
 * @link http://odin.himinbi.org/podcast_component/
 * @license GNU/GPL
 */

defined('_VALID_MOS') or die('Direct access not allowed.');
 
// include support libraries
require_once($mainframe->getPath('admin_html'));
 
// handle the task
$task = mosGetParam($_REQUEST, 'task', '');
 
switch($task) {
 case 'polite':
   politeHello();
   break;
 default:
   podcastScreens::listCurrentPodcasts();
   break;
}

/**
 * Polite hello event
 */
function politeHello() {
  global $my;
  podcastScreens::politeHello($my->username);
}
?>