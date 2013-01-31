<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?>?1 );
	</style>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/addEvent.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/sweetTitles.js"></script>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

<div id="header">
	<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'greenery_page_menu' ) ); ?>
	<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
</div>

<div id="content">
