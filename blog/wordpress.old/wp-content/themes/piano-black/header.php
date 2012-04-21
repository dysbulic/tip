<?php
/**
 * @package WordPress
 * @subpackage Piano Black
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'piano-black' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">
	<div id="page" class="hfeed">
		<nav id="access" role="navigation">
			<h1 class="section-heading"><?php _e( 'Main menu', 'piano-black' ); ?></h1>
			<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'piano-black' ); ?>"><?php _e( 'Skip to content', 'piano-black' ); ?></a></div>
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #access -->

		<header id="branding" role="banner">
			<hgroup>
				<h1 id="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>

			<div id="header-meta">
			<?php
				// Check to see if Search and RSS get shown in header
				$options = piano_black_get_theme_options();
				if ( 'yes' == $options['theme_search'] ) :
			?>
				<form method="get" id="search-area" action="<?php echo home_url( '/' ); ?>">
					<div><input type="text" value="<?php esc_attr_e( 'Search', 'piano-black' ); ?>" name="s" id="search-input" onfocus="this.value='';" /></div>
					<div><input type="image" src="<?php echo get_template_directory_uri(); ?>/img/search-button.gif" alt="<?php esc_attr_e( 'Search', 'piano-black' ); ?>" title="<?php esc_attr_e( 'Search', 'piano-black' ); ?>" id="search-button" /></div>
				</form>
			<?php endif;

			if ( 'yes' == $options['theme_rss'] ) : ?>
				<a href="<?php bloginfo( 'rss2_url' ); ?>" id="rss-feed" title="<?php esc_attr_e( 'RSS Feed', 'piano-black' ); ?>"><?php _e( 'RSS', 'piano-black' ); ?></a>
			<?php endif; ?>
			</div><!-- #header-meta -->

		</header><!-- #branding -->

		<div id="main">