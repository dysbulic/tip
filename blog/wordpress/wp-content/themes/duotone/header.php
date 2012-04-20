<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

<title><?php wp_title( '&laquo;', true, 'right' ); ?> <?php if ( is_single() ) { ?> <?php _e( 'Blog Archive &laquo;' ); ?> <?php } ?><?php bloginfo( 'name' ); ?></title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
wp_enqueue_script('jquery');
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
?>

<!--[if lte IE 8]>
<style type="text/css"> 
	body { font-size:12px; } 
</style>
<![endif]-->
<script type="text/javascript" charset="utf-8">
	/* <![CDATA[ */
	jQuery(document).ready(function($){
		$('#sidebar > ul > li').css('display', 'none');
		$('#sidebar > ul > *:lt(3)').css('display', 'block');
	});
	/* ]]> */
</script>
</head>
<body<?php image_orientation(); ?>>

<div id="page">

<div id="header">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="description"><?php bloginfo( 'description' ); ?></div>

		<div id="menu">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'duotone_page_menu' ) ); ?>
		</div>
</div>

<div id="content">
	<div class="sleeve">
