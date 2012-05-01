<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php if ( is_single() ) { ?> Blog Archive &laquo; <?php } ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lte IE 8]>
<style type="text/css"> 
	body { font-size:12px; } 
</style>
<![endif]-->

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>
<body<?php
global $vertical;
//set vertical class or archive class, if needed
if($vertical && !is_archive() && !is_search()) { echo ' class="vertical"'; } 
if(is_archive() or is_search()) echo ' class="archive"';
?>>

<div id="page">

<div id="header">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
		
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_id' => 'menu', 'fallback_cb' => 'monotone_page_menu' ) ); ?>
</div>

<div id="content">
	<div class="sleeve">
