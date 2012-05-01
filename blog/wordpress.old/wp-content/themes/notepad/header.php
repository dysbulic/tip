<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

		<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

		<link href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php bloginfo( 'template_directory' ); ?>/css/print.css" media="print" rel="stylesheet" type="text/css" />
		<!--[if IE]>
		<link href="<?php bloginfo( 'template_directory' ); ?>/ie.css" media="screen" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if lte IE 6]>
		<link href="<?php bloginfo( 'template_directory' ); ?>/ie6.css" media="screen" rel="stylesheet" type="text/css" />
		<![endif]-->

		<link  rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<?php if ( is_singular() && comments_open() ) { wp_enqueue_script( 'comment-reply' ); } ?>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="pagewrapper">
		<div id="header">
			<h1 id="logo">
				<a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
			</h1>
			<p class="description"><?php bloginfo( 'description' ); ?></p>

			<p class="socialmedia">
			<?php
				global $notepad_media_links;
				$options = get_option('notepad_theme_options');
				foreach ( $notepad_media_links as $notepad_media_link ) :
					if ( $options["$notepad_media_link[0]url"] != '' ) :
						?>
							<a href="<?php echo $options["$notepad_media_link[0]url"]; ?>"><img alt="<?php echo $notepad_media_link[1]; ?>" src="<?php bloginfo( 'template_directory' ); ?>/img/socialmedia/<?php echo $notepad_media_link[0]; ?>.png"><?php echo $notepad_media_link[1]; ?></a>
						<?php
					endif;
				endforeach;

				if ( $options['rss'] != '' ) : ?>
					<a href="<?php bloginfo( 'rss2_url' ); ?>"><img alt="<?php esc_attr_e( 'RSS', 'notepad-theme' ); ?>" src="<?php bloginfo( 'template_directory' ); ?>/img/socialmedia/rss.png"><?php _e( 'RSS', 'notepad-theme' ); ?></a>
				<?php
				endif;
			?>
			</p>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

		</div>
		<!--/header -->
		
		<?php
			// Check to see if the header image has been removed
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) :
		?>
		<div id="header-image">
			<a class="home-link" href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'notepad-theme' ); ; ?></a>
		</div>
		<?php endif; ?>
		
		<div id="wrapper">
			
