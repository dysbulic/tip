<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/javascript/tabs.js"></script>
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
?>
</head>
<body <?php body_class(); ?>>

<div id="top"></div>

<!-- Start BG -->
<div id="bg">
	
<!-- Start Header -->
<div class="header">
 <h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
 <ul class="rss">
  <li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'Entries (RSS)', 'albeo' ); ?></a></li>
  <li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>"><?php _e( 'Comments (RSS)', 'albeo' ); ?></a></li>
 </ul>
</div>
<!-- End Header -->

<div id="main-menu">
	<div class="menu">
	 	<?php wp_nav_menu( array( 'container' => false, 'menu_class' => 'custom-menu', 'theme_location' => 'primary', 'fallback_cb' => 'albeo_page_menu', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	</div>
</div>

<!-- Start Con-->
<div class="con<?php if( is_attachment() ) echo ' attachment-container'; ?>">

<!-- Start SL -->
<div class="sl-a">
<div class="sl-t"></div>
<div class="sl">
