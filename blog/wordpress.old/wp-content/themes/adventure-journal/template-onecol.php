<?php
/**
 * Template Name: One column, no sidebars
 *
 * A page template with no sidebars
 *
 * @package Adventure_Journal
 *
 * This template loads the same code as page.php.  The real
 * magic happens in adventurejournal_layout_classes() which
 * sets a different body class if this template is selected.
 */

global $content_width;
$content_width = 930;
get_template_part( 'page' );
?>