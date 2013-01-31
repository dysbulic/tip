<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<!--[if lt IE 7]>
<link href="<?php bloginfo( 'template_url' ); ?>/ie6.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript">var clear="<?php bloginfo( 'template_url' ); ?>/images/clear.gif"; //path to clear.gif</script>
<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/unitpngfix.js"></script>
<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

	<div id="header">
		<div id="logo">
			<h1><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<p id="description"><?php bloginfo( 'description' ); ?></p>
		</div>

		<div id="nav">
			<p id="subscribe-rss"><a href="<?php bloginfo( 'rss2_url' ); ?>" title="Subscribe to RSS feed">RSS</a></p>
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'darkwood_page_menu' ) ); ?>
		</div>

	</div><!-- /header -->