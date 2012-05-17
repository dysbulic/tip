<?php
/**
 * @package WordPress
 * @subpackage K2
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '444444',
	'link' => '2277dd',
	'border' => 'cccccc',
	'url' => '3371a3',
);

$content_width = 500;

add_filter( 'body_class', '__return_empty_array', 1 );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

// Sidebar registration for dynamic sidebars, one on the left or right depending on user layout selection, the other three in the footer.
if(function_exists('register_sidebar')) {
	register_sidebar(array('before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>'));
}

register_sidebar( array(
		'name' => __( 'Footer Area One', 'k2_domain' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'k2_domain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'k2_domain' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'k2_domain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'k2_domain' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'k2_domain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function k2_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

define('HEADER_TEXTCOLOR', 'ffffff');
define('HEADER_IMAGE', '%s/images/k2-header.png'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 780);
define('HEADER_IMAGE_HEIGHT', 200);

function k2_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	background-color: rgb(51, 113, 163);
}
#headimg h1
{
font-family: 'Trebuchet MS',Verdana,Sans-Serif;
font-size: 30px;
font-weight: bold;
letter-spacing: -1px;
padding-left: 40px;
padding-right: 40px;
padding-top: 75px;
margin: 0;
}
#headimg h1 a {
	color:#<?php header_textcolor() ?>;
	border: none;
	text-decoration: none;
}
#headimg a:hover
{
	text-decoration:underline;
}
#headimg #desc
{
	font-weight:normal;
	font-size:10px;
	color:#<?php header_textcolor() ?>;
	margin:0;
	padding:0 40px;
}

<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headerimg h1, #headerimg #desc {
	display: none;
}
#headimg h1 a, #headimg #desc {
	color:#<?php echo HEADER_TEXTCOLOR ?>;
}
<?php } ?>

</style>
<?php
}

function header_style() {
?>
<style type="text/css">
#header {
	background:#3371a3 url(<?php header_image() ?>) center repeat-y;
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header h1 a, #header .description {
	display: none;
}
<?php } else { ?>
#header h1 a, #header h1 a:hover, #header .description {
	color: #<?php header_textcolor() ?>;
}
<?php } ?>
</style>
<?php
}

add_custom_image_header('header_style', 'k2_admin_header_style');

// Register nav menu locations
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

// Add a home link to the default menu fallback wp_page_menu
function k2_page_menu_args( $args ) {
	$args['show_home'] = 'Blog';
	$args['depth'] = 1;
	return $args;
}
add_filter( 'wp_page_menu_args', 'k2_page_menu_args' );

// Add CLASS attribute of "menu" to the first <ul> occurence in wp_page_menu( )
function k2_add_menu_class( $menu ) {
	return preg_replace( '/<ul>/', '<ul id="nav" class="menu">', $menu, 1 );
}
add_filter( 'wp_page_menu', 'k2_add_menu_class' );

function k2_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $comment_index;
	$comment_index++;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<span class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<a href="#comment-<?php comment_ID(); ?>" class="counter" title="<?php esc_attr_e( 'Permanent Link to this Comment', 'k2_domain' ); ?>"><?php echo $comment_index; ?></a>
		<span class="commentauthor fn"><?php comment_author_link(); ?></span>
	</span>
	<small class="comment-meta">
	<?php
		printf('<a href="#comment-%1$s" title="%2$s">%3$s</a>',
			get_comment_ID(),
			(function_exists('time_since')?
				sprintf(__('%s ago.','k2_domain'),
					time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time())
				):
				__('Permanent Link to this Comment','k2_domain')
			),
			sprintf(__('%1$s at %2$s','k2_domain'),
				get_comment_date(),
				get_comment_time()
			)
		);
	?>
	</small>

	<div class="comment-content">
		<?php comment_text(); ?>
	</div>

	<?php if ('0' == $comment->comment_approved) { ?><p class="alert"><strong><?php _e('Your comment is awaiting moderation.','k2_domain'); ?></strong></p><?php } ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

function k2_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $ping_index;
	$ping_index++;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if (function_exists('comment_favicon')) { ?><span class="favatar"><?php comment_favicon(); ?></span><?php } ?>
	<a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e( 'Permanent Link to this Comment', 'k2_domain' ); ?>" class="counter"><?php echo $ping_index; ?></a>
	<span class="commentauthor"><?php comment_author_link(); ?></span>
	<small class="comment-meta">
	<?php
		printf(__('%1$s on %2$s','k2_domain'),
			'<span class="pingtype">' . __('Trackback','k2_domain') . '</span>',
			sprintf('<a href="#comment-%1$s" title="%2$s">%3$s</a>',
				get_comment_ID(),
				(function_exists('time_since')?
					sprintf(__('%s ago.','k2_domain'),
						time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time())
					):
					__('Permanent Link to this Comment','k2_domain')
				),
				sprintf(__('%1$s at %2$s','k2_domain'),
					get_comment_date(),
					get_comment_time()
				)
			)
		);
	?>
	<?php if ($user_ID) { edit_comment_link(__('Edit','k2_domain'),'<span class="comment-edit">','</span>'); } ?>
	</small>
<?php
}

// Load the K2 theme options
require_once ( get_template_directory() . '/inc/theme-options.php' );

/**
 *  Returns the K2 options with defaults as fallback
 */
function k2_get_theme_options() {
	$defaults = array(
		'theme_layout' => 'content-sidebar',
	);
	$options = get_option( 'k2_theme_options', $defaults );

	return $options;
}

/**
 *  Returns the current K2 layout as selected in the theme options
 */
function k2_current_layout() {
	$options = k2_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( 'content-sidebar' != $current_layout )
		return $current_layout;
	else
		return;
}

/**
 *  Adds k2_current_layout() to the array of body classes
 *
 */
function k2_body_class($classes) {
	$classes[] = k2_current_layout();

	return $classes;
}
add_filter( 'body_class', 'k2_body_class' );