<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till the opening tag for <div id="inner-wrap">
 *
 * @package WordPress
 * @subpackage Bold_Life
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
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
	<div id="top-wrap"></div><!-- #top-wrap -->
			<div id="wrap">
				<?php
				/* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.
				 * The menu assiged to the primary position is the one used.
				 * If none is assigned, the menu with the lowest ID is used.
				 */
				wp_nav_menu( array( 'theme_location' => 'primary', 'container_id' => 'nav' ) );
				?>
				<h1 class="logo">
					<a href="<?php echo home_url( '/' ); ?>">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
				<h3 class="description">
					<?php bloginfo( 'description' ); ?>
				</h3>
				<?php
					// If header image exists, output a DIV for it
					$header_image = get_header_image();
					if ( ! empty( $header_image ) )
						echo '<div id="header-image"></div>';
				?>
				<div id="inner-wrap">