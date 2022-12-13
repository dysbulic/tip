<?php
/* I'm not really sure where to put this since I can't put it in the settings.php
   and I don't want to insert it at the top of all my content
 */
theme_add_style('sites/default/style.css');

function phptemplate_stylesheet_import($stylesheet, $media = 'all') {
  if(strpos($stylesheet, 'misc/drupal.css') == 0) {
    return theme_stylesheet_import($stylesheet, $media);
  }
}

/*
function squiggle_menu_links($links) {
  return _phptemplate_callback('menu_links', array('links' => $links));
}
*/

function squiggle_menu_item($mid, $children = '', $leaf = TRUE) {
  return _phptemplate_callback('menu_item', array('mid' => $mid, 'children' => $children, 'leaf' => $leaf));
} 

/*
function squiggle_links($links) {
  return _phptemplate_callback('links', array('links' => $links));
}

function squiggle_tabs($links) {
  return theme_menu_links($links);
  return _phptemplate_callback('tabs', array('links' => $links));
}
*/
?>
