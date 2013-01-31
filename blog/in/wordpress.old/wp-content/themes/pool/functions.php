<?php
/**
 * @package WordPress
 * @subpackage Pool
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '0b76ae',
	'border' => 'b8d4ff',
	'url' => '0090da',
);

$content_width = 550;

add_filter( 'body_class', '__return_empty_array', 1 );

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'pool' ),
) );

function pool_page_menu() { // fallback for primary navigation ?>
	<ul>
		<li<?php if ( is_front_page() ) echo ' class="current_page_item"'; ?>><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Blog', 'pool' ); ?></a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }

// Custom background
add_custom_background();

function pool_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'pool_custom_background' );

define('HEADER_TEXTCOLOR', 'FFFFFF');
define('HEADER_IMAGE', '%s/images/logo.gif'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 795);
define('HEADER_IMAGE_HEIGHT', 150);

function pool_admin_header_style() {
?>
	<style type="text/css">
		#headimg {
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		}
		#headimg h1 {
			font-size: 30px;
			letter-spacing: 0.1em;
			margin: 0;
			padding: 20px 0 20px 30px;
			width: 300px;
		}
		#headimg a,
		#headimg a:hover {
			background: transparent;
			text-decoration: none;
			color: #<?php header_textcolor(); ?>;
			border-bottom: none;
		}
		#headimg #desc {
			display: none;
		}
		<?php if ( 'blank' == get_header_textcolor() ) { ?>
		#headerimg h1, #headerimg #desc {
			display: none;
		}
		#headimg h1 a, #headimg #desc {
			color:#<?php echo HEADER_TEXTCOLOR; ?>;
		}
	<?php } ?>
	</style>
<?php
}

function header_style() {
?>
	<style type="text/css">
		#header {
			background: #8EBAFD url(<?php header_image() ?>) left repeat-y;
		}
		<?php if ( 'blank' == get_header_textcolor() ) { ?>
		#header h1 a,
		#header #desc {
			display: none;
		}
		<?php } else { ?>
		#header h1 a,
		#header h1 a:hover,
		#header #desc {
			color: #<?php header_textcolor(); ?>;
		}
		<?php } ?>
	</style>
<?php
}

add_custom_image_header('header_style', 'pool_admin_header_style');

if ( function_exists('register_sidebars') )
        register_sidebars(1);

function pool_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID(); ?>">
		<div id="div-comment-<?php comment_ID(); ?>" class="vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php comment_text(); ?>
			<p class="alignright"><small class="comment-meta commentmetadata"><?php comment_type(__( 'Comment by', 'pool' ), __( 'Trackback by', 'pool' ), __( 'Pingback by', 'pool' ) ); ?> <span class="fn"><?php comment_author_link(); ?></span><?php _e('&#8212;' , 'pool'); ?> <?php comment_date(); ?> <a href="#comment-<?php comment_ID(); ?>"><?php _e( '#', 'pool' ); ?></a><?php edit_comment_link(__ ( 'Edit', 'pool' ), ' | ' ); ?></small></p>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			</div>
		</div>
<?php
}
