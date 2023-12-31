<?php
/**
 * @package Indiefeed
 * @subpackage Components
 * components/com_podcast/podcast.php
 * @link http://odin.himinbi.org/podcast_component/
 * @license GNU/GPL
 */

defined('_VALID_MOS') or die('Direct access not allowed.');

require_once($mainframe->getPath('class'));
require_once('podcast.archive.php');
require_once('podcast.listing.php');
require_once('podcast.detail.php');
require_once('podcast.edit.php');
 
class PodcastHTML {
  function printPodcast($podcast) {
    printPodcastInfo($podcast);
  }

  function printArchive($channel) {
    $params = array('pageno' => intval(mosGetParam($_REQUEST, 'page', 1)),
                    'perpage' => intval(mosGetParam($_REQUEST, 'perpage', 10)),
                    'channel' => $channel);
    $maxpages = Podcast::maxPages($channel) / $params['perpage'];
    $podcasts = Podcast::loadPodcasts($params);
    printPager($channel, $params['pageno'], $maxpages);
    printArchive($podcasts);
    printPager($channel, $params['pageno'], $maxpages);
  }

  function printCurrent($date) {
    $podcasts = Podcast::loadCurrentCasts($date);
    printListing($podcasts);
  }

  function printEditPage() {
    $castid = mosGetParam($_REQUEST, 'castid', undefined);
    printEditScreen(Podcast::loadCast($castid));
  }
}
