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
require_once($mainframe->getPath('front_html'));
  
global $mosConfig_live_site, $mainframe, $Itemid;

// handle the task
$task = mosGetParam($_REQUEST, 'task', '');

$castid = mosGetParam($_REQUEST, 'castid', undefined);
$channel = mosGetParam($_REQUEST, 'channel', undefined);

if($channel == undefined && $Itemid != undefined) {
  $menu = $mainframe->get('menu');
  $params = new mosParameters($menu->params);
  $channel = $params->def('channel', undefined);
}

$date = mosGetParam($_REQUEST, 'date', undefined);

switch($task) {
 case 'edit':
   PodcastHTML::printEditPage();
   break;
 case 'save':
   $podcast = Podcast::loadCast($castid);
   $podcast->updateFromRequest($_REQUEST);
   $podcast->save();
   break;
 case 'rate':
   $rating = mosGetParam($_REQUEST, 'rating', undefined);
   //if($rating != undefined) {
     $podcast = Podcast::loadCast($castid);
     $podcast->setRating($rating);
     //}
   break;
 case 'show':
 default:
   // A date and a channel identify a podcast and details should be shown
   // A channel by itself should show an archive for that channel
   // A date and no channel should show the entry closest to that date for all channels
   // If nothing is specified show the listing for the current date
   if($castid != undefined) {
     PodcastHTML::printPodcast(Podcast::loadCast($castid));
   } else if($date != undefined && $channel != undefined) {
     PodcastHTML::printPodcast(Podcast::loadCurrentCast($channel, $date));
   } else if($channel != undefined) {
     PodcastHTML::printArchive($channel);
   } else {
     PodcastHTML::printCurrent($date);
   }
   break;
}
?>
