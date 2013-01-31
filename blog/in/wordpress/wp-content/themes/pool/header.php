<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />	</style>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<div id="content">
<?php do_action( 'before' ); ?>

	<div id="header" onclick="location.href='<?php echo home_url( '/' ); ?>';" style="cursor: pointer;">
		<h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h1>
	</div>

	<div id="pagesnav">
		<div class="alignleft">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'pool_page_menu' ) ); ?>
		</div>

		<div id="search">
			<form id="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
				<input type="text" id="s" name="s" onblur="this.value=(this.value=='') ? '<?php esc_attr_e( 'Search this Blog', 'pool' ); ?>' : this.value;" onfocus="this.value=(this.value=='<?php esc_attr_e( 'Search this Blog', 'pool' ); ?>') ? '' : this.value;" id="supports" name="s" value="<?php esc_attr_e( 'Search this Blog', 'pool' ); ?>" />
			</form>
		</div>
	</div><!-- #pagesnav -->
<!-- end header -->
