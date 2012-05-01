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
 
class item {
  public function __construct($myname) { $this->item = $myname; }
}

class PodcastHTML {
  /**
   * Static method to create the template object
   *
   * @return patTemplate
   */
  function &createTemplate() {
    global $option, $mosConfig_absolute_path;
    require_once($mosConfig_absolute_path . '/includes/patTemplate/patTemplate.php');
 
    $tmpl =& patFactory::createTemplate($option, true, false);
    $tmpl->setRoot(dirname(__FILE__) . '/templates');
 
    return $tmpl;
  }

  /**
   * A simple message
   */
  function listCurrentPodcasts() {
    // import the body of the page
    $tmpl =& PodcastHTML::createTemplate();
    $tmpl->setAttribute('body', 'src', 'podcastlist.html');
    //$tmpl->addVar('helloworld', 'name', "WJH");
    //$tmpl->addObject('items', array(array("item" => "One"), array("item" => "Two"), array("item" => "Three")), "");
    
    $items = array(new item("One"), new item("Two"), new item("Three"));

    $tmpl->addObject('casts', Podcast::loadPodcasts(), "r_");
    $tmpl->displayParsedTemplate('form');
  }
}
?>