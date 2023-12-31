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
class podcastScreens {
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
    $tmpl =& podcastScreens::createTemplate();
    $tmpl->setAttribute('body', 'src', 'podcastlist.html');
    $tmpl->displayParsedTemplate('form');
  }

  /**
   * A polite hello
   * @param string The name of a person
   */
  function politeHello($name) {
    $tmpl =& podcastScreens::createTemplate();
    $tmpl->setAttribute('body', 'src', 'politehello.html');
    $tmpl->addVar('body', 'name', $name);
    $tmpl->displayParsedTemplate('form');
  }
}
?>