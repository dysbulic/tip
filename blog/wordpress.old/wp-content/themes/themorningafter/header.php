<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if (gt IE 7) | (!IE)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php $morningafter_options = morningafter_get_theme_options();  ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
	<div class="container">
		
		<div id="header" class="column full-width clear-fix">

			<div id="logo" class="column first">
				<div class="title">
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h3'; ?>
					<<?php echo $heading_tag; ?> class="site-title">
						<a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
					</<?php echo $heading_tag; ?>>
					<div class="site-description desc"><?php bloginfo( 'description' ); ?></div>
				</div>
			</div><!-- end logo -->

			<div id="search_menu" class="column border_left last">
				<div id="search" class="column first">
					<h3 class="mast4"><?php _e( 'Search','woothemes' ); ?></h3>

					<div id="search-form">
						<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>/">
							<div class="clear-fix">
								<label for="s" class="none"><?php _e( 'Search for','woothemes' ); ?>:</label>
								<input type="text" name="s" id="s" class="search_input" value="<?php the_search_query(); ?>" />
								<label for="searchsubmit" class="none"><?php _e( 'Go','woothemes' ); ?></label>
								<input type="submit" id="searchsubmit" class="submit_input" value="Search" />
							</div>
						</form>
					</div>
				</div><!-- end #search -->
				<ul id="menu" class="clear-fix">
					<?php
						$links = array( 'home','about','archives','subscribe','contact' );
						foreach ( $links as $curlink ) {
							$link = trim( $morningafter_options[$curlink] );
							if ( ( $link != '' ) && ( $link != '#' ) ) {
								echo '<li><span class="'.$curlink.'"><a href="'.$morningafter_options[$curlink].'">'.__( ucfirst( $curlink ),'woothemes' ).'</a></span></li>'."\n";
							}
						}
					?>
				</ul>
			</div><!-- end #search_menu -->
		
		</div><!-- end #header -->

		<div id="navigation" class="clear-fix">
			<?php wp_nav_menu( array( 'depth' => 6, 'menu_class' => 'nav fl', 'theme_location' => 'primary' ) ); ?>
			<?php if ( $morningafter_options['show_feed_link'] == "1" ) { ?>
				<ul class="rss fr">
					<li class="sub-rss"><a href="<?php bloginfo( 'rss_url' ); ?>"><?php _e( 'Subscribe to RSS', 'woothemes' ); ?></a></li>
				</ul>
			<?php } ?>
		</div><!-- #navigation -->