<?php
/**
 * @package Inuit Types
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta  http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if lte IE 8]>
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/ie.css" type="text/css" media="screen" />
<![endif]-->

<!--[if lte IE 6]>
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/ie6.css" type="text/css" media="screen" />
<![endif]-->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<div class="wrapper">

	<div id="main_menu" class="top_menu">

	    <div class="fl">

		    <?php wp_nav_menu( 'sort_column=menu_order&theme_location=primary' ); ?>

	    </div>


	    <div class="fr">

			<span class="subscribe">

			<a href="<?php bloginfo('rss2_url'); ?>"><span class="rss-button"><?php _e( 'RSS', 'it' ); ?></span></a> &nbsp;<?php _e( 'Subscribe:', 'it' ); ?>&nbsp;

			<a href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><?php _e( 'RSS feed', 'it' ); ?></a>

			</span>

	    </div>

	</div><!-- #main_menu -->


		<div class="container">

			<div class="content <?php if ( !get_option('inuitypes_right_sidebar') ) { echo 'content_left'; } else { echo 'content_right'; } ?>">

								<div id="header">

			            <div class="blog-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></div>

		                <div class="blog-description"><?php bloginfo('description'); ?></div>

				</div>
