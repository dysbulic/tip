<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" title="Freshy"/>
<!--[if IE 7]>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lt IE 7]>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" media="screen" />
<![endif]-->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head();
?>
</head>
<body>

<div id="page">
	
	<!-- header -->
	<div id="header">
		<div id="title">
			<h1>
				<a href="<?php echo home_url( '/' ); ?>">
					<span><?php bloginfo('name'); ?></span>
				</a>
			</h1>
			<div class="description">
				<small><?php bloginfo('description'); ?></small>
			</div>
		</div>
		<div id="title_image"></div>
	</div>
	
	<!-- main div -->
	<div id="frame">

	<!-- main menu -->
	<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_class' => 'menu custom-menu primary', 'fallback_cb' => 'freshy_page_menu' ) ); ?>
	
	<hr style="display:none"/>
