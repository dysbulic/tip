<?php load_theme_textdomain('redo_domain'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 7]>
<html id="ie7" xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<![endif]-->
<!--[if (gt IE 7) | (!IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?2" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>

	<?php
		$redo_pagemenu = 0;
	?>
	


<body class="<?php redo_body_class(); ?>" <?php redo_body_id(); ?>>

	<div id="header">
    	<div class="top">

			<div id="header_content" onclick="document.location='<?php bloginfo('url'); ?>';" style="cursor: pointer;">
				<div id="title" class="title">
					<h1><a href="<?php echo home_url( '/' ); ?>" title="Back to the front page"><?php bloginfo('name'); ?></a></h1>
           		</div>

				<div id="rightcolumnheader">
				<?php if($redo_pagemenu == 1) { ?>
				<ul id="menu">
					<?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
				</ul>
				<?php } ?>
				</div>

			</div>

		</div>

	</div>

	<?php if($redo_pagemenu == 0) { ?>
	<div class="navigation-menu">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_id' => 'alt_menu', 'menu_class' => 'redo-nav', 'fallback_cb' => 'redo_page_menu' ) ); ?>
	</div>
	<?php } ?>

<div id="page">	
	<hr />