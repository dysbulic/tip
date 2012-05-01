<?php

if(is_home()) {
	$homeTagStart = '<h1 id="blog-title">';
	$homeTagEnd = '</h1>';
} else {
	$homeTagStart = '<span id="blog-title">';
	$homeTagEnd = '</span>';
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php echo get_bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
	<meta name="robots" content="all" />
	<meta name="description" content="<?php echo get_bloginfo('description'); ?>" />
	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_url'); ?>" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/print.css" type="text/css" media="print" />
	<?php if(get_option('tarski_style')) { ?><link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/styles/<?php echo get_option('tarski_style'); ?>" type="text/css" media="screen,projection" /><?php } ?>

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>

<body class="center <?php if (is_page() || is_single() || is_404()) { echo " single"; } ?>"><div id="wrapper">

<div id="header">

	<?php if ( get_header_image() != false ) :	?>	
	<div id="header-image">
		<?php if (is_home()) { ?><img alt="" src="<?php header_image() ?>" /><?php } else { ?><a title="Return to front page" href="<?php echo home_url( '/' ); ?>"><img alt="" src="<?php header_image() ?>" /></a><?php } ?>
	</div>
	<?php endif; ?>

	<div id="title">
		<?php if (is_home()) { echo $homeTagStart; bloginfo('name'); echo $homeTagEnd; } else { ?><a title="Return to front page" href="<?php echo home_url( '/' ); ?>"><?php echo $homeTagStart; bloginfo('name'); echo $homeTagEnd; ?></a><?php } ?>
		<?php if (get_bloginfo('description') != '') { ?><p id="tagline"><?php echo get_bloginfo('description'); ?></p><?php } ?>
	</div>
	
	<div id="navigation">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'tarski_page_menu' ) ); ?>

		<ul id="nav-2">
			<li><a class="feed" title="<?php printf( __( 'Subscribe to the %1$s feed', 'tarski' ), get_bloginfo( 'name' ) ); ?>" href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><?php _e( 'Subscribe to feed', 'tarski' ); ?></a></li>
		</ul>
	</div>

</div>

<div id="content">
