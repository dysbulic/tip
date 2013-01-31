<?php
	load_theme_textdomain('contempt');
	$pg_li = '';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page">
<?php do_action( 'before' ); ?>

<div id="header">
	<div id="headerimg" onclick="location.href='<?php bloginfo('url'); ?>';" style="cursor: pointer;">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>
</div>

<?php wp_nav_menu( array( 'container' => false, 'menu_id' => 'pagebar', 'theme_location' => 'primary', 'fallback_cb' => 'contempt_page_menu' ) ); ?>

<div id="grad" style="height: 65px; width: 100%; background: url(<?php bloginfo('stylesheet_directory'); ?>/images/blue_flower/topgrad.jpg);">&nbsp;</div>
