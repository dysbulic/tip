<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<nav id="access" role="navigation" class="contain">
		<?php wp_nav_menu( array(
			'container'      => false,
			'menu_id'        => 'primary-menu',
			'theme_location' => 'primary-menu'
		) ); ?>
	</nav>

	<header id="branding" class="contain" role="banner">
		<a id="home-link" href="<?php echo esc_url( home_url() ); ?>">

		<?php $title = get_bloginfo( 'blogname' ); ?>
		<?php if ( ! empty( $title ) ) : ?>
			<h1 id="site-title"><?php echo esc_html( $title ); ?></h1>
		<?php endif; ?>

		<?php $tagline = get_bloginfo( 'description' ); ?>
		<?php if ( ! empty( $tagline ) ) : ?>
			<h2 id="site-description"><?php echo esc_html( $tagline ); ?></h2>
		<?php endif; ?>

		</a>
	</header><!-- #branding -->

	<section id="page">