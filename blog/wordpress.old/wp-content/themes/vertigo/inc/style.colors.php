<?php
/**
 * CSS based on values in Theme Options
 *
 * @package Vertigo
 */

$vertigo_options = vertigo_get_theme_options();
?>
	a,
	a:link,
	a:visited,
	.data a:hover,
	#respond .required,
	#site-generator a:hover {
		color: #<?php echo $vertigo_options['accent_color']; ?>;
	}
	.format-image .photo-wrap img:hover,
	#access .current_page_item a {
		border-color: #<?php echo $vertigo_options['accent_color']; ?>;
	}
	.format-link .hand,
	.format-link .entry-title,
	.nav-previous a,
	.nav-next a,
	#colophon #controls,
	.bypostauthor .avatar {
		background-color: #<?php echo $vertigo_options['accent_color']; ?>;
	}
	.data a {
		color: #666;
	}
	.comments-link a {
		color: #fff;
	}