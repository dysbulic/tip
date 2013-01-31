<?php
/**
 * @package WordPress
 * @subpackage Selecta
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if (!IE)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="header" class="clearfix">
		<div id="branding">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
				<<?php echo $heading_tag; ?> id="logo">
				<span>
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
			</<?php echo $heading_tag; ?>>
			<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		</div><!-- #branding-->

		<div id="access" role="navigation">
			<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
			<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'selecta' ); ?>"><?php _e( 'Skip to content', 'selecta' ); ?></a></div>
			<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'primary' ) ); ?>
		</div><!-- #access -->

		<?php
			// Do we have a header image around?
			if ( '' != get_header_image() ) :
		?>
		<div id="header-image">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" /></a>
		</div>
		<?php endif; ?>
		</div><!-- #header -->

	<?php // Check to see if this is the front page, and then check to see if the featured slider and / or latest posts have been enabled in the theme options
		if( is_front_page() && ! is_page() ) : ?>
		<div id="featured-posts-container" class="clearfix">
		<?php
			$options = selecta_get_options();
			if ( 'yes' == $options['theme_slider'] ) :
				get_template_part( 'showcase' );
			endif; // end check for showcase display
		?>
		</div><!-- #featured-posts-container -->
		<?php
			if ( 'yes' == $options['theme_latest_posts'] ) :
				get_template_part( 'latest-posts' );
			endif; //end check for latest posts display
		endif; // end check for front page
	?>