<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<meta name="copyright" content="Design is copyright 2009 - <?php echo date('Y'); ?> The Theme Foundry" />
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" rel="stylesheet" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'template_url' ); ?>/stylesheets/ie.css" />
<![endif]-->
<!--[if lte IE 7]>
<script type="text/javascript">
	sfHover=function(){var sfEls=document.getElementById("navigation").getElementsByTagName("LI");for(var i=0;i<sfEls.length;i++){sfEls[i].onmouseover=function(){this.className+=" sfhover";}
	sfEls[i].onmouseout=function(){this.className=this.className.replace(new RegExp(" sfhover\\b"),"");}}}
	if (window.attachEvent)window.attachEvent("onload",sfHover);
</script>
<![endif]-->
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'template_url' ); ?>/stylesheets/ie6.css" />
<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/javascripts/pngfix.js"></script>
<script type="text/javascript"> DD_belatedPNG.fix( '#navigation, div.comments a' );</script>
<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_enqueue_script( 'jquery' ); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="wrapper">
	<div class="skip-content"><a href="#content"><?php _e( 'Skip to content', 'paperpunch' ); ?></a></div>
	<div id="header" class="clear">
		<ul id="follow">
			<li>
				<a href="<?php bloginfo( 'rss2_url' ); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/images/flw-rss.png" alt="<?php _e( 'RSS Feed', 'paperpunch' ); ?>"/></a>
			</li>
		</ul>
		<?php if (is_front_page()) echo( '<h1 id="title">' ); else echo( '<div id="title">' );?><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a><?php if (is_front_page()) echo( '</h1>' ); else echo( '</div>' );?>
		<div id="description"><?php bloginfo( 'description' ); ?></div>
		</div><!--end header-->
		
		<div id="navigation" class="clear">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'paperpunch_page_menu' ) ); ?>
		</div><!--end navigation -->

	<div id="content">
