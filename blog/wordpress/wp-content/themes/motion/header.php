<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<!--[if lt IE 7]>
<link href="<?php bloginfo( 'template_url' ); ?>/ie6.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript">var clear="<?php bloginfo( 'template_url' ); ?>/images/clear.gif"; //path to clear.gif</script>
<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/unitpngfix.js"></script>
<![endif]-->

<?php if ( is_singular() && comments_open() ) { wp_enqueue_script( 'comment-reply' ); } ?>
<?php wp_enqueue_script( 'sfhover', get_template_directory_uri() . '/js/sfhover.js' ); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

<div id="top">
	<div id="topmenu">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'motion_page_menu' ) ); ?>
	</div>

	<div id="search">
		<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
			<p>
				<input type="text" value="<?php _e("Search this site..."); ?>" onfocus="if (this.value == '<?php _e("Search this site..."); ?>' ) { this.value = ''; }" onblur="if (this.value == '' ) { this.value = '<?php _e("Search this site..."); ?>'; }" name="s" id="searchbox" />
				<input type="submit" class="submitbutton" value="<?php _e( 'GO' ); ?>" />
			</p>
		</form>
	</div>
</div><!-- /top -->

<div id="header">
	<div id="logo">
		<a href="<?php echo home_url( '/' ); ?>"><img src="<?php header_image() ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"><?php bloginfo( 'description' ); ?></div>
	</div><!-- /logo -->

	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'header' ) ) : ?>
	<div id="headerbanner">
		<p><?php printf( __( 'Hey there! Thanks for dropping by %1$s! Take a look around and grab the <a href="%2$s">RSS feed</a> to stay updated. See you around!' ), get_bloginfo( 'name' ), get_bloginfo( 'rss2_url' ) ); ?></p>
	</div>
	<?php endif; ?>
</div><!-- /header -->

<?php if ( !motion_hide_categories() ) : ?>
<div id="catnav">
	<ul id="nav">
		<?php wp_list_categories( 'orderby=name&title_li=' ); ?>
	</ul>
</div><!-- /catnav -->
<?php endif; ?>
