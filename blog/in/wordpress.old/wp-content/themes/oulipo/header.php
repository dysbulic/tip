<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?><!DOCTYPE html>
<!--[if IE 6 ]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7 ]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !IE]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

	<?php if ( '' != get_header_image() ) : ?>
	<div id="header">
		<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
	</div>
	<?php endif; ?>

	<div id="search">
		<?php get_search_form(); ?>
	</div>

	<div id="main-nav">
		<h1 class="masthead"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<p class="description"><?php bloginfo( 'description' ); ?></p>

		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu-wrap' ) ); ?>
	</div>