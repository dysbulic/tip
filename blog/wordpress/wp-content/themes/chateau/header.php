<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<div id="page-inner">
		<header id="branding" role="banner">
			<nav id="menu" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' =>  'primary', 'container' => '', 'menu_class' => 'menu clear-fix' ) ); ?>
			</nav><!-- end #menu -->
			
			<div id="main-title">
				<hgroup>
					<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
						$blog_description = get_bloginfo( 'description' );
						if ( ! empty( $blog_description ) ) : ?>
							<h2 id="site-description">~ <?php echo $blog_description; ?></h2>
					<?php endif; ?>
				</hgroup>
			</div><!-- end #main-title -->
			
			<?php
				// Has the text been hidden?
				if ( 'blank' != get_header_textcolor() ) :
			?>
				<div id="search">
					<?php get_search_form(); ?>
				</div><!-- end #search -->
			<?php endif; ?>
			
			<div id="main-image">
			<?php
				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( ! empty( $header_image ) ) :
			?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						if ( !is_404() && !is_attachment() ) { // Check if this is not 404 or attachment page
							// Check if this is a post or page, if it has a post-thumbnail
							if ( is_singular() && has_post_thumbnail( $post->ID ) && ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) && $image[1] >= HEADER_IMAGE_WIDTH ) :
							echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
						else : ?>
							<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php bloginfo( 'name' ); ?>" title="<?php bloginfo( 'name' ); ?>" />
					<?php endif; } // end check for featured image or standard header ?>
				</a>
			<?php endif; // end check for removed header image ?>
			</div><!-- end #main-image -->
		</header><!-- #branding -->
		<div id="main" class="clear-fix">