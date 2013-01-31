<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" rel="stylesheet" type="text/css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
<div id="header">
	<h1 id="logo"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	<p id="description"><?php bloginfo( 'description' ); ?></p>

	<?php if ( '' != get_header_image() ) : ?>
		<div id="custom-header-img"><a href="<?php bloginfo( 'url' ); ?>/"><img src="<?php header_image(); ?>" alt="" height="200" width="980" /></a></div>
	<?php endif; ?>

	<div id="nav">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => 'koi_page_menu' ) ); ?>
	</div>

	<?php $koi_options = get_option( 'koi_theme_options' ); // hide-header-search
		if ( ! $koi_options['hide-header-search'] ) :
			get_search_form();
		endif;
	?>
</div>
<!--/header -->