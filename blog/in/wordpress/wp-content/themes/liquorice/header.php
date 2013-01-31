<?php
/**
 * @package WordPress
 * @subpackage Liquorice
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if (!IE 7)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="canvas" class="hfeed">
<?php $options = get_option( 'liquorice_theme_options' ); ?>

	<div id="social-icons">
		<?php if ( $options['twitterurl'] != '' ) : ?>
			<a href="<?php echo $options['twitterurl']; ?>" class="twitter"><?php _e( 'Twitter', 'liquorice' ); ?></a>
		<?php endif; ?>

		<?php if ( $options['facebookurl'] != '' ) : ?>
			<a href="<?php echo $options['facebookurl']; ?>" class="facebook"><?php _e( 'Facebook', 'liquorice' ); ?></a>
		<?php endif; ?>

		<?php if ( ! $options['hiderss'] ) : ?>
			<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rss"><?php _e( 'RSS Feed', 'liquorice' ); ?></a>
		<?php endif; ?>
	</div><!-- #social-icons-->

    <ul class="skip">
    	<li><a href="#nav"><?php _e( 'Skip to navigation', 'liquorice' ); ?></a></li>
     	<li><a href="#primary-content"><?php _e( 'Skip to main content', 'liquorice' ); ?></a></li>
      	<li><a href="#secondary-content"><?php _e( 'Skip to secondary content', 'liquorice' ); ?></a></li>
      	<li><a href="#footer"><?php _e( 'Skip to footer', 'liquorice' ); ?></a></li>
    </ul>
	<div id="header">
   		<div id="branding">
		<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
			<span>
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</span>
			</<?php echo $heading_tag; ?>>
			<div id="site-description"><?php bloginfo( 'description' ); ?></div>
		</div><!-- #branding -->
		<div id="nav">
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
		</div><!-- #nav -->
	</div> <!-- #header -->