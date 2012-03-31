<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/ie.css" type="text/css" media="all" />
	<![endif]-->

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<div id="wrap">

	<?php
		if ( has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary-menu',
				'menu_id'        => 'primary-menu',
				'container'      => '',
				'fallback_cb'    => '__return_false'
			) );
		}
		else if ( ! has_nav_menu( 'secondary-menu' ) ) {
			echo '<ul class="menu">';
			wp_list_pages( 'title_li=' );
			echo '</ul>';
		}
	?>

	<div id="header">
		<?php
		$header_image = get_header_image();
		if ( ! empty( $header_image ) )
			echo '<a id="header-image" href="' . esc_url( site_url() ) . '"><img src="' . esc_url( $header_image ) . '" alt="" /></a>';

		echo '<h1 id="site-title"><a href="' . esc_url( site_url() ) . '">' . get_option( 'blogname' ) . '</a></h1>';

		$tagline = get_bloginfo( 'description' );
		if ( ! empty( $tagline ) )
			echo '<a id="site-description" href="' . esc_url( site_url() ) . '">' . $tagline . '</a>';
		?>
	</div><!-- /header -->

	<?php
		if ( has_nav_menu( 'secondary-menu' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'secondary-menu',
				'menu_id'        => 'secondary-menu',
				'container'      => '',
				'fallback_cb'    => '__return_false'
			) );
		}
	?>

	<div id="content">

		<?php $comet_options = comet_get_theme_options(); ?>
		<?php if ( in_array( $comet_options['theme_layout'], array( 'sidebar-content', 'sidebar-content-sidebar' ) ) ) : ?>
			<div id="c1">
				<?php get_sidebar( 'primary' ); ?>
			</div>
		<?php endif; ?>

		<div id="c2">
