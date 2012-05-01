<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/ie.css" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<!--[if IE]>
	<style type="text/css">
		#main {
			background-color: transparent !important;
			background: url(<?php bloginfo( 'template_url' ); ?>/images/bgs/ie-bg.png) repeat-x;
			position: relative;
			padding-top: 120px;
		}
		#ie-wrap {
			background: #fff;
			margin: 0;
		}
		#header-image {
			margin: 0;
		}
		#site-description {
			position: absolute;
			top: 20px;
			width: 868px;
		}
	</style>
	<![endif]-->
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="header">
		<div id="logo">
			<h1>
				<a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
			</h1>
		</div>
		<div class="page-list">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'spectrum_page_menu' ) ); ?>
		</div>
	</div>
	<div id="main-wrap">
		<div id="main">
		<!--[if IE]>
			<div id="ie-wrap">
		<![endif]-->
			<div id="site-description">
				<h2>
					<?php bloginfo( 'description' ); ?>
				</h2>
			</div>
		<?php if ( '' != get_header_image() ) : ?>
			<div id="header-image">
				<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
			</div>
		<?php endif; ?>
			<div id="content">