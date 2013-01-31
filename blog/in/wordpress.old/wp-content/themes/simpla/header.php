<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>
<body <?php body_class(); ?>>

<div id="wrap">
	<div id="header">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<p class="description"><?php bloginfo( 'description' ); ?></p>
		<?php wp_nav_menu( array( 'container_id' => 'nav', 'container_class' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => false ) ); ?>
	</div><!-- / #header -->
	
	<?php
		// Check to see if the header image has been removed
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) :
	?>
	<div id="header-image">
		<a class="home-link" href="<?php echo home_url( '/' ); ?>">
			<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="Home" />
		</a>
	</div>
	<?php endif; ?>
