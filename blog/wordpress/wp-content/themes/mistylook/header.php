<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>
<body id="section-index">


<div id="navigation" class="clearfix">
	<?php wp_nav_menu( 'theme_location=primary&container_class=menu&fallback_cb=mistylook_menu_fallback' ); ?>
</div><!-- end id:navigation -->

<div id="container">


<div id="header">
<h1><a href="<?php bloginfo('url');?>/" title="<?php bloginfo('name');?>"><?php bloginfo('name');?></a></h1>
<h2><?php bloginfo('description');?></h2>
</div><!-- end id:header -->


<?php if ( -1 != get_option('blog_public') ) : ?>
<div id="feedarea">
<dl>
	<dt><strong><?php _e('Feeds:','mistylook'); ?></strong></dt>
	<dd><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Posts','mistylook'); ?></a></dd>
	<dd><a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments','mistylook'); ?></a></dd>
</dl>
</div><!-- end id:feedarea -->
<?php endif; ?>

	<div id="headerimage">
</div><!-- end id:headerimage -->
