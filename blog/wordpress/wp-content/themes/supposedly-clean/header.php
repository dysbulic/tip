<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
		<?php
			mytheme_sidebar();
			mytheme_colors();
			mytheme_theme();
		?>
	</style>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php 
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head(); 
	?>
</head>

<body>

	<div id="container">
		<div id="matt">
		<h1><a href="<?php bloginfo('url'); ?>" title="<?php _e('home', 'supposedly-clean'); ?>"><?php echo get_bloginfo('name'); ?></a></h1> <!-- make sure to give your blog a name-->
		<div id="boren_head"></div><!-- close boren head-->


	