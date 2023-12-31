<?php
/**
 * @package Indiefeed
 * @subpackage Components
 * components/com_podcast/admin.podcast.html.php
 * @link http://odin.himinbi.org/podcast_component/
 * @license GNU/GPL
 */

defined('_VALID_MOS') or die('Direct access not allowed.');

// include support libraries
require_once($mainframe->getPath('toolbar_html'));
 
// handle the task
$task = mosGetParam($_REQUEST, 'task', '');
 
switch ($task) {
 default:
   podcastToolbar::podcastList();
   break;
}
?>