<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if IE 6]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie6.css" type="text/css" media="screen" />
<![endif]-->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="container" class="group">
<?php do_action( 'before' ); ?>

<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
<?php if(get_bloginfo('description') != '') { ?>
<div id="bubble"><p><?php bloginfo('description'); ?></p></div> <!-- erase this line if you want to turn the bubble off -->
<?php } ?>
