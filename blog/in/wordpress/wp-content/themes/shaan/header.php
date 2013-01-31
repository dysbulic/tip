<?php
/**
 * @package Shaan
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" media="all" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="access">
	<?php wp_nav_menu( array(
		'container_class' => 'menu-header',
		'theme_location'  => 'primary-menu',
	) ); ?>
</div><!-- #access -->

<div id="wrapper" class="clearfix">
	<?php if ( '' == get_header_image() ) : ?>
		<div id="header">
			<h1 id="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		</div><!-- #header -->
	<?php else : ?>
		<a id="header" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<h1 id="site-title"><?php bloginfo( 'name' ); ?></h1>
			<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		</a><!-- #header -->
	<?php endif; ?>