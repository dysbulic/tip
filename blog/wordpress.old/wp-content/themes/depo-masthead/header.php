<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/ie.css" type="text/css" media="screen" /><![endif]-->

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_enqueue_script( 'jquery' ); ?>

<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
?>

<script type="text/javascript">
/*<![CDATA[ */
//set title font size
jQuery(window).load(function() {

	function resize(selector, max) {
		jQuery(selector).each(function() {
			var width = jQuery(this).width();
			var height = jQuery(this).height();
			if(width > max) {
			var r = ( height / width );
			var w = max;
			var h = ( w * r );
			jQuery(this).height(h).width(w);
		}});
	}

	resize( '#home .gallery img', 90);
	resize( '#home .entry img', 300);
	resize( '#home .wp-caption img', 290);
	resize( '#home .wp-caption', 300);
	jQuery( '#home .wp-caption' ).css( 'height', 'auto' );
});
/* ]]> */
</script>
<?php
	$title_length = strlen( get_bloginfo( 'name' ) );
	if ( 0 != $title_length ) { // avoid empty title
?>
<style type="text/css" media="screen">
<?php
	if ( $title_length >= 30 )
		$title_length = 40;
		$font_size = ( 1000 / $title_length ) * 2;

	if ( $font_size > 120 )
		$font_size = 120;

	if ( preg_match( '/.*\s.*/', $title ) === false && ( $title > 20 ) )
		$font_size = 72;
		$font_size = round( $font_size, 2 ); // round to two decimals
?>
	#container h1.sitename {
		font-size: <?php echo $font_size; ?>px;
	}
</style>
<?php } ?>
</head>
<body<?php if ( ( is_front_page() or is_home() ) && ! is_page() ) { echo ' id="home"'; } ?> <?php body_class(); ?>>
<div id="page">

<h1 class="name"><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'description' ); ?>"><span>
<?php
	if ( get_option( 'depo-author-name' ) )
		echo get_option( 'depo-author-name' );

	else {
		$my_query = new WP_Query( 'showposts=1' );
		while ( $my_query->have_posts() ) {
			$my_query->the_post();
			$title = get_userdata( $post->post_author );
		}
		echo $title->display_name;
	}
?>
</span></a></h1>

<div id="container">
	<div class="sleeve">

		<div id="header">
			<h1 class="sitename">
			<?php if ( ! is_front_page() ) { ?><a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'description' ); ?>"><?php } ?>
			<?php bloginfo( 'name' ); ?>
			<?php if ( ! is_front_page() ) { ?></a><?php } ?>
			</h1>

			<div id="menu">
				<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'depomasthead_page_menu' ) ); ?>
			</div>

		</div>

		<div id="content" class="group">
