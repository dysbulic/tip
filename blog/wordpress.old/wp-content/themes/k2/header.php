<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_uri(); ?>" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_url'); ?>/css/print.css" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php 
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head(); 
	?>
</head>

<body <?php body_class(); ?>>
<div id="page">
	<div id="header">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h1>
		<p class="description"><?php bloginfo('description'); ?></p>
		
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'sort_column' => 'menu_order', 'menu_id' => 'nav' ) ); ?>

	</div>
		<hr />
