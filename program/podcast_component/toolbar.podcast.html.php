<?php
/**
 * @package Indiefeed
 * @subpackage Components
 * components/com_podcast/admin.podcast.html.php
 * @link http://odin.himinbi.org/podcast_component/
 * @license GNU/GPL
 */

defined('_VALID_MOS') or die('Direct access not allowed.');

/**
 * @package HelloWorld
 */
class podcastToolbar {
  /**
   * Displays toolbar
   */
  function podcastList(){
    mosMenuBar::startTable();
    mosMenuBar::apply('polite', 'Be Polite');
    mosMenuBar::spacer();
    mosMenuBar::help('podcastlist.html', true);
    mosMenuBar::endTable();
  }
}
?>