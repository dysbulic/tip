<?php
define( 'TITAN_FUNC_PATH',  get_template_directory() . '/functions' );

$themecolors = array(
	'bg' => 'f9f7f5',
	'border' => 'f9f7f5',
	'text' => '444444',
	'link' => '4265a7',
	'url' => '4265a7'
);
$content_width = 497;

// Add support for post thumbnails (added in 2.9)
if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );

// Required functions
require_once( TITAN_FUNC_PATH . '/comments.php' );
require_once( TITAN_FUNC_PATH . '/titan-extend.php' );

// Enable widgets
if ( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar(
		array(
			'name' => __( 'Sidebar' ),
			'id' => 'titan_sidebar'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Left' ),
			'id' => 'footer_left'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Center' ),
			'id' => 'footer_center'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Right' ),
			'id' => 'footer_right'
		)
	);
}

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'titan' ),
) );

function titan_page_menu() {
	global $titan;
// fallback for primary navigation ?>
<ul id="nav" class="wrapper">
	<?php if ( $titan->hideHome() !== 'true' ) : ?>
	<li class="page_item <?php if (is_front_page()) echo ( 'current_page_item' ); ?>"><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Home', 'titan' ); ?></a></li>
	<?php endif; ?>
    <?php if ( $titan->hidePages() !== 'true' ) : ?>
    	<?php wp_list_pages( 'title_li=&exclude='. $titan->excludedPages() ); ?>
    <?php endif; ?>
    <?php if ( $titan->hideCategories() != 'true' ) : ?>
    	<?php wp_list_categories( 'title_li=&exclude=' . $titan->excludedCategories() ); ?>
    <?php endif; ?>
</ul>
<?php }