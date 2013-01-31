<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="screen" />
	<![endif]-->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page">


<div id="header">
	<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('title'); ?></a></h1>
	<div class="description"><?php bloginfo('description'); ?></div>
</div>

<div class="hr">&nbsp;</div> <!-- because IE sucks at styling HRs -->

<div id="headerimg" class="clearfix">
	<div id="header-overlay"> </div>
	<div id="header-image"><img alt="" src="<?php header_image() ?>" /></div>
</div>

<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<div id="access">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'menu clearfix' ) ); ?>
	</div>
<?php else : ?>
	<div class="hr">&nbsp;</div>
<?php endif; ?>

<div id="wrapper" class="clearfix">