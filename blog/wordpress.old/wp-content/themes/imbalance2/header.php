<?php
/**
 * @package Imbalance 2
 */
?><!DOCTYPE html>
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<?php
	$options = imbalance2_get_theme_options();
	$color = $options['color'];
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery( document ).ready( function( $ ) {
	// fluid grid
<?php if ( 'yes' == $options['fluid'] ) : ?>
	function wrapperWidth() {
		var wrapper_width = $( 'body' ).width() - 20;
		wrapper_width = Math.floor( wrapper_width / 250 ) * 250 - 40;
		if (wrapper_width < 1000 ) wrapper_width = 1000;
		$( '#wrapper' ).css( 'width', wrapper_width );
	}
	wrapperWidth();

	$( window ).resize( function() {
		wrapperWidth();
	} );
<?php endif ?>

	// search
	$( document ).ready( function() {
		$( '#s' ).val( 'Search' );
	} );

	$( '#s' ).bind( 'focus', function() {
		$( this ).css( 'border-color', '<?php echo $color ?>' );
		if ( $( this ).val() == 'Search' ) $( this ).val( '' );
	} );

	$( '#s' ).bind( 'blur', function() {
		$( this ).css( 'border-color', '#dedfe0' );
		if ( $( this ).val() == '' ) $( this ).val( 'Search' );
	} );

	// grid
	var $container = $( '#boxes' );
	$container.imagesLoaded( function() {
		$container.masonry( {
			itemSelector: '.box',
			columnWidth: 210,
		<?php if ( 'rtl' == get_option( 'text_direction' ) ) : ?>
			isRTL: true,
		<?php endif; ?>
			gutterWidth: 40
		} );
	} );

	var $featured_container = $( '#featured-posts' );
	$featured_container.imagesLoaded( function() {
		$featured_container.masonry( {
			itemSelector: '.box',
			columnWidth: 210,
		<?php if ( 'rtl' == get_option( 'text_direction' ) ) : ?>
			isRTL: true,
		<?php endif; ?>
			gutterWidth: 40
		} );
	} );

	$( '.texts' ).live( {
		'mouseenter': function() {
			if ( $( this ).height() < $( this ).find( '.abs' ).height() ) {
				$( this ).height( $( this ).find( '.abs' ).height() );
			}
			$( this ).stop( true, true ).animate( {
				'opacity': '1',
				'filter': 'alpha(opacity=100)'
			}, 0 );
		},
		'mouseleave': function() {
			$( this ).stop( true, true ).animate( {
				'opacity': '0',
				'filter': 'alpha(opacity=0)'
			}, 0 );
		}
	} );

	$( '.commentlist li div' ).bind( 'mouseover', function() {
		var reply = $( this ).find( '.reply' )[0];
		$( reply ).find( '.comment-reply-link' ).show();
	} );

	$( '.commentlist li div' ).bind( 'mouseout', function() {
		var reply = $( this ).find( '.reply' )[0];
		$( reply ).find( '.comment-reply-link' ).hide();
	} );
} );
/* ]]> */
</script>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">
	<div id="header" class="clear-fix">
		<div id="branding">
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			</<?php echo $heading_tag; ?>>
			<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php
				$header_image = get_header_image();
				if ( ! empty( $header_image ) ) :
			?>
			<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" id="header-image-link">
				<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" id="header-image" alt="" />
			</a>
			<?php endif; ?>
		</div>
		<div id="header-left">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu' ) ); ?>
		</div>
		<div id="header-center">
			<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container_class' => 'menu', 'fallback_cb' => '__return_false' ) ); ?>
		</div>
		<div id="header-right">
			<?php get_sidebar(); ?>
		</div>
	</div>

	<div id="main" class="clear-fix">