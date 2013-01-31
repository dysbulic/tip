<?php
/**
 * @package Adventure_Journal
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
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
	<div id="container">
		<div id="container2">
			<div id="menu" role="navigation">
				<span class="assistive-text"><?php _e( 'Main menu', 'adventurejournal' ); ?></span>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'adventurejournal' ); ?>"><?php _e( 'Skip to content', 'adventurejournal' ); ?></a></div>
				<div id="menu-wrap">
					<div id="nav-right"></div>
					<?php wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<div id="wrapper-top">
				<div id="page-curl"></div>
			</div>
			<div id="header">
				<div id="logo">
					<div id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
					<div id="site-description"><?php bloginfo( 'description' ); ?></div>
				</div>
				<div id="banner">
				<?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if (is_singular() && has_post_thumbnail( $post->ID ) && ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) && $image[1] >= HEADER_IMAGE_WIDTH ) {
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID );
					} else if ( get_header_image() ) { ?>
						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
				<?php }  ?>
				</div>
			</div><!-- #header -->
		<div id="wrapper-content">