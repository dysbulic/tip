<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->
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
	if ( is_singular() && get_option( 'thread_comments' ) )
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
<div id="wrapper">
	<div id="header">
 		<div class="col-full">
			<div id="logo">
				<?php $heading_tag = is_singular() ? 'span' : 'h1'; ?>
				<<?php echo $heading_tag; ?> class="site-title">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</<?php echo $heading_tag; ?>>
				<span class="site-description"><?php bloginfo( 'description' ); ?></span>
			</div><!-- /#logo -->

			<div id="navigation">
				<?php wp_nav_menu( array( 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'primary', 'fallback_cb' => 'skeptical_page_menu' ) ); ?>
				<?php
					// Check to see if RSS get shown in header
					$options = skeptical_get_options();
					if ( 'yes' == $options['theme_rss'] ) :
				?>
				<ul class="rss fr">
					<li class="sub-rss"><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'Subscribe to RSS', 'woothemes' ); ?></a></li>
				</ul>
				<?php endif; ?>
			</div><!-- /#navigation -->
		</div><!-- /.col-full -->
	</div><!-- /#header -->
	<?php
		// Do we have a header image around?
		if ( '' != get_header_image() ) :
	?>
	<div id="header-image">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /></a>
	</div>
	<?php endif; ?>