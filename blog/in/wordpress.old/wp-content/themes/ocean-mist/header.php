<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if IE 6]> 	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" media="screen" /> <![endif]-->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page">
  <div id="header">
    <h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
    <div class="description"><?php bloginfo( 'description' ); ?>&nbsp;</div>
    <div id="mainpic">
    	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
			if ( is_singular() &&
				has_post_thumbnail( $post->ID ) &&
				( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH ) ) ) &&
				$image[1] >= HEADER_IMAGE_WIDTH ) :
				echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
			endif;
		?>
    	</a>
    </div>
	  <?php wp_nav_menu( array( 'container' => false, 'menu_class' => 'pagetabs', 'theme_location' => 'primary', 'fallback_cb' => 'oceanmist_page_menu' ) ); ?>
  </div>
