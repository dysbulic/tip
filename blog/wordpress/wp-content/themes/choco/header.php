<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package WordPress
 * @subpackage Choco
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if (!IE 7)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page">
	<div id="header" class="clear-fix">
		<div id="logo">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
				<span>
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
			</<?php echo $heading_tag; ?>>
			<div class="description"><?php bloginfo( 'description' ); ?></div>

		</div><!-- #logo -->
		
		<div id="nav">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary') ); ?>
		</div><!-- #nav -->

	</div><!-- #header -->
	
	<div id="main">
		<a href="<?php bloginfo( 'rss_url' ); ?>" id="rss-link">RSS</a>
		<div id="main-top">
			<div id="main-bot" class="clear-fix">
				<div id="content">