<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_uri(); ?>" />

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_directory_uri(); ?>/style_ie.css" />
<![endif]-->

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<!--[if lt IE 9]>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="site-wrapper">
	<h1 class="vcard author" id="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php esc_attr_e( 'Home', 'manifest' ); ?>" class="fn"><?php bloginfo( 'name' ); ?></a></h1>
	<nav id="main-nav">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'depth' => 2, 'fallback_cb' => 'manifest_page_menu' ) ); ?>
	</nav>
	<?php
		// Do we have a header image around?
		if ( '' != get_header_image() ) :
	?>
	<div id="header-image">
		<a href="<?php echo home_url( '/' ); ?>" title="<?php esc_attr_e( 'Home', 'manifest' ); ?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /></a>
	</div>
	<?php endif; ?>
	<div id="site-description">
		<?php bloginfo( 'description' ); ?>
	</div>
