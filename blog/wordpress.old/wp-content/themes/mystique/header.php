<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */

$options = mystique_get_theme_options();
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
<?php wp_enqueue_script( 'functions-js', get_template_directory_uri().'/js/functions.js', array( 'jquery') ); ?>
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page">

	<div id="container">

		<ul class="skip">
			<li><a href="#access"><?php _e( 'Skip to navigation', 'mystique' ); ?></a></li>
			<li><a href="#main"><?php _e( 'Skip to main content', 'mystique' ); ?></a></li>
			<li><a href="#sidebar"><?php _e( 'Skip to primary sidebar', 'mystique' ); ?></a></li>
			<li><a href="#sidebar2"><?php _e( 'Skip to secondary sidebar', 'mystique' ); ?></a></li>
			<li><a href="#footer"><?php _e( 'Skip to footer', 'mystique' ); ?></a></li>
		</ul>

		<div id="header">
			<div id="branding" class="clear-block">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
				<<?php echo $heading_tag; ?> id="logo">
				<span>
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</span>
				</<?php echo $heading_tag; ?>>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>
			</div><!-- #branding-->

			<div id="access" class="clear-block" role="navigation">
				<?php wp_nav_menu( array( 'container' => 'false', 'theme_location' => 'primary' ) ); ?>

				<div class="social-icons">

					<?php if ( $options['youtube_link'] != '' ) : ?>
						<a href="<?php echo $options['youtube_link']; ?>" class="youtube"><?php _e( 'You Tube', 'mystique' ); ?></a>
					<?php endif; ?>

					<?php if ( $options['flickr_link'] != '' ) : ?>
						<a href="<?php echo $options['flickr_link']; ?>" class="flickr"><?php _e( 'Flickr', 'mystique' ); ?></a>
					<?php endif; ?>

					<?php if ( $options['twitter_link'] != '' ) : ?>
						<a href="<?php echo $options['twitter_link']; ?>" class="twitter"><?php _e( 'Twitter', 'mystique' ); ?></a>
					<?php endif; ?>

					<?php if ( $options['facebook_link'] != '' ) : ?>
						<a href="<?php echo $options['facebook_link']; ?>" class="facebook"><?php _e( 'Facebook', 'mystique' ); ?></a>
					<?php endif; ?>

					<?php if ( $options['show_rss_link'] ) : ?>
						<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rss"><?php _e( 'RSS Feed', 'mystique' ); ?></a>
					<?php endif; ?>

				</div><!-- .social-icons -->

			</div><!-- #access -->

			<?php /*
				First, let's see if this if the option for "home page only" is on
				If it is, and this isn't the home page, we'll do nothing
			*/
				if ( $options['featured_post_home_only'] && !is_home() ) :
					// Do nothing
				else :
					// See if we have any sticky posts and use the latest to create a featured post
					$sticky = get_option( 'sticky_posts' );
					$featured_args = array(
						'posts_per_page' => 1,
						'post__in' => $sticky,
					);

					$featured = new WP_Query();
					$featured->query( $featured_args );

					// A featured image will only show if the post is sticky, has a thumbnail image and the thumbnail is at least as wide as HEADER_IMAGE_WIDTH.
					if ( $sticky ) :
						$featured->the_post();
					?>
					<?php if ( has_post_thumbnail() ) :
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); // get the thumbnail image
						// If the thumbnail image is at least as wide as our header image, display it along with the post excerpt.
						if ( $image >= HEADER_IMAGE_WIDTH ) : ?>
							<div class="featured-post featured-tiptrigger">
								<h2 class="showcase-heading"><?php echo( esc_attr( $options['featured_post_label'] ) ); ?> <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"> <?php the_post_thumbnail( 'large-feature' ); ?></a>
								<?php
									$featured_excerpt = get_the_excerpt();
									if ( '' != $featured_excerpt ) : ?>
										<?php the_excerpt();
									endif;
								
									// Set up featured post variable for use in loop-index.php
									if ( mystique_get_featured_post() != $post->ID )
										mystique_set_featured_post( $post->ID );
								?>
							</div><!-- #featured-post -->
						<?php endif; // end the check for the correct feature image width ?>
					<?php endif; // end the check for thumbnail image existence ?>
					<?php wp_reset_query(); // Reset all the query vars to the real query ?>
					<?php endif; // end the check for a sticky post ?>
				<?php endif; // end check for featured_post_home_only option ?>

			</div><!-- #header-->

			<div id="main">