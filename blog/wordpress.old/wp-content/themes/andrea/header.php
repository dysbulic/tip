<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<?php $options = andrea_get_theme_options(); ?>
</head>
<body <?php body_class( $options['layout_choice'] ); ?>>
<div id="wrap" class="group">
	<div id="header">
		<?php if ( '' != get_header_image() ) : ?>
			<a href="<?php bloginfo( 'url' ); ?>/"><img src="<?php header_image(); ?>" alt="" /></a>
		<?php endif; ?>
		<h1><a href="<?php bloginfo( 'url' ); ?>/"><?php bloginfo( 'name' ); ?></a> &nbsp; <?php bloginfo( 'description' ); ?></h1>
	</div>
	<div id="nav" class="group">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary' ) ); ?>
		<div id="feed"><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS Feed', 'andrea' ); ?></a></div>
	</div>