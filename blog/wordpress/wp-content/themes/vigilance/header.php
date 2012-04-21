<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<meta name="copyright" content="Vigilance Theme Design: Copyright 2008 - 2010 The Theme Foundry" />
<link href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" rel="stylesheet" />
<!--[if lt IE 8]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/stylesheets/ie.css" /><![endif]-->
<!--[if lte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/stylesheets/ie6.css" /><![endif]-->
<?php if ( rtl == get_bloginfo( text_direction ) ) : ?> 
<!--[if lt IE 8]>
<style type="text/css" media="screen">
	#nav ul li a { display:inline-block; }
	#title { margin-bottom: -4px !important; }
</style>
<![endif]-->
<?php endif;?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="skip-content"><a href="#content"><?php _e('Skip to content', 'vigilance'); ?></a></div>
	<div id="wrapper">
		<div id="header" class="clear">
			<?php if (is_front_page()) echo('<h1 id="title">'); else echo('<div id="title">'); ?><a href="<?php bloginfo('url'); ?>"><span><?php bloginfo('name'); ?></span></a><?php if (is_front_page()) echo('</h1>'); else echo('</div>'); ?>
			<div id="description">
				<h2><?php bloginfo('description'); ?></h2>
			</div><!--end description-->
			<div id="nav">
				<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'vigilance_page_menu' ) ); ?>
			</div><!--end nav-->
		</div><!--end header-->
		<div id="content" class="pad">
			<?php if ( is_front_page() ) get_template_part( 'header-alertbox' ); ?>