<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<!--[if IE]><link rel="stylesheet" href="<?php get_stylesheet_directory_uri(); ?>/ie.css" type="text/css" media="screen" /><![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">

	<div class="header">
		<div id="logo">
		<h1><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<h2><?php bloginfo( 'description' ); ?></h2>
		</div>

		<div id="nav">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'cleanhome_page_menu' ) ); ?>
		</div>
		
		<?php if ( '' != get_header_image() ) : ?>
		<div id="header-image">
			<a href="<?php echo home_url(); ?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /></a>
		</div>
		<?php endif; ?>
	</div>

	<?php if ( is_active_sidebar( 'header' ) ) : ?>
	<div id="blurb">
		<?php dynamic_sidebar( 'header' ); ?>
	</div>
	<?php endif; ?>
