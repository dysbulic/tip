<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<style type="text/css" media="screen">
@import "<?php bloginfo( 'stylesheet_url' ); ?>";
</style>
<?php
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
?>
</head>

<body <?php body_class( 'sidebars' ); ?>>

<div id="navigation"></div>

<div id="wrapper">
	<div id="container" class="clear-block">

	<div id="header">
		<div id="logo-floater">
			<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		</div>
	
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_class' => 'links primary-links', 'fallback_cb' => 'garland_page_menu' ) ); ?>

	</div> <!-- /header -->

<?php get_sidebar(); ?>

<div id="center"><div id="squeeze"><div class="right-corner"><div class="left-corner">
<!-- begin content -->
<div class="node">
