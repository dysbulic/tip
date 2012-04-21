<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Header
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="wrapper">

<?php /* Header */ ?>
<div id="header">
	<div id="header_inner">
		<?php
		/* Main menu */
		wp_nav_menu(
			array(
				'theme_location' => 'primary-menu',
				'container'      => '',
				'menu_class'     => 'nav sf-menu',
			)
		);
		/* Search */ ?>
		<form id="search-form" role="search" method="get" action="<?php bloginfo( 'url' ); ?>">
			<input type="text" value="<?php _e( 'Search: type, hit enter', 'zbench' ); ?>" onfocus="if (this.value == '<?php _e( 'Search: type, hit enter', 'zbench' ); ?>' ) {this.value = '';}" onblur="if (this.value == '' ) {this.value = '<?php _e( 'Search: type, hit enter', 'zbench' ); ?>';}" name="s" id="s" />
			<input type="submit" id="search-submit" value="<?php _e( 'Search', 'zbench' ); ?>" />
		</form>
	</div>
</div>

<?php /* Content */ ?>
<div id="content">

	<?php /* Title */ ?>
	<div id="title">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<p><?php bloginfo( 'description' ); ?></p>
	</div>

	<?php 	$header = get_header_image(); if ( '' != $header ) : ?>
	<div id="header-background"><a href="<?php echo home_url( '/' ); ?>"><span><?php _e( 'Home', 'zbench' ); ?></span></a></div>
	<?php endif; ?>
