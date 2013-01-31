<?php
/**
 * @package Titan
 */
global $titan; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" rel="stylesheet" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'template_url' ); ?>/stylesheets/ie.css" />
<![endif]-->
<!--[if lte IE 7]>
<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/javascripts/nav.js"></script>
<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>	
	<div class="skip-content"><a href="#content"><?php _e( 'Skip to content', 'titan' ) ?></a></div>
	
	<div id="header" class="clear">
		<div id="follow">
			<div class="wrapper clear">
				<dl>
					<dt><?php _e( 'Follow:', 'titan' ); ?></dt>
					<dd><a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS', 'titan' ); ?></a></dd>
					<?php if ( $titan->twitterToggle() == 'true' ) : else : ?>
					<dd><a class="twitter" href="http://twitter.com/<?php echo ( $titan->twitter() !== '' ) ? $titan->twitter() : ''; ?>"><?php _e( 'Twitter', 'titan' ); ?></a></dd>
					<?php endif; ?>
				</dl>
			</div><!--end wrapper-->
		</div><!--end follow-->
		
		<div class="wrapper">
			<?php if ( is_home() ) echo ( '<h1 id="title">' ); else echo ( '<div id="title">' ); ?><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a><?php if ( is_home() ) echo ( '</h1>' ); else echo ( '</div>' ); ?>
			
			<div id="description">
				<?php bloginfo( 'description' ); ?>
			</div><!--end description-->
			
			<?php
				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( ! empty( $header_image ) ) :
			?>
				<a class="home-link" href="<?php echo home_url( '/' ); ?>">
					<img id="header-image" src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
				</a>
			<?php endif; ?>
	 	</div><!--end wrapper-->
	
		<div id="navigation">
			<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_id' => 'nav', 'menu_class' => 'wrapper', 'fallback_cb' => 'titan_page_menu' ) ); ?>
		</div><!--end navigation-->
	</div><!--end header-->
	<div class="content-background">
	<div class="wrapper">
		<div class="notice">
		<?php if ( ( is_front_page() ) && ( $titan->noticeState() == 'true' ) ) : ?>
			<div><?php echo $titan->noticeContent(); ?></div>
		<?php endif; ?>
		</div><!--end notice-->
		<div id="content">