<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<!--[if lte IE 7]><link href="<?php bloginfo( 'template_directory' ); ?>/css/style-ie.css" rel="stylesheet" type="text/css" /><![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="container">
<div id="header">
	<h1><a href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	<p><?php bloginfo( 'description' ); ?></p>

	<div id="menu">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'neutra_page_menu' ) ); ?>
	</div>

	<div id="search">
		<?php include (TEMPLATEPATH . '/searchform.php' ); ?>
	</div>
</div><!-- /header -->