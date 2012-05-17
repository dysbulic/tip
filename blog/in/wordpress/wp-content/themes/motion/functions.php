<?php
/**
 * @package Motion
 */

// Load admin options page
require_once( get_template_directory() . '/functions/options-page.php' );

$themecolors = array(
	'bg' => '002728',
	'border' => '034450',
	'text' => 'ffffff',
	'link' => 'a8ef9d',
	'url' => 'afdaff',
);

$content_width = 640;

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function motion_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'motion_custom_background' );

// Widgets
if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar(
		array(
			'name' => 'Sidebar',
			'id' => 'sidebar',
			'before_widget' => '<li id="%1$s" class="boxed widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => 'Footer Left',
			'id' => 'footer_left',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => 'Footer Middle',
			'id' => 'footer_middle',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => 'Footer Right',
			'id' => 'footer_right',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => 'Header',
			'id' => 'header',
			'before_widget' => '<div id="headerbanner" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		)
	);
}

register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

function motion_page_menu() { // fallback for primary navigation ?>
	<ul>
		<?php if ( !motion_hide_homelink() ) : ?><li class="page_item<?php echo ( is_home() || is_front_page() ? ' current_page_item' : '' ); ?>"><a href="<?php echo home_url( '/' ); ?>">Home</a></li><?php endif; ?>
		<?php wp_list_pages( 'depth=1&title_li=0&sort_column=menu_order' ); ?>
		<li><a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a></li>
	</ul>

<?php }


// Comments
function motiontheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
		<?php echo get_avatar($comment,$size='50'); ?>
		<div class="commentbody">
			<div class="author"><?php comment_author_link(); ?></div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _( '(Your comment is awaiting moderation...)' ); ?></em>
			<?php endif; ?>
			<div class="commentmetadata">
				<a href="#comment-<?php comment_ID(); ?>" title="">
					<?php printf( __( '%1$s at %2$s' ),
						get_comment_date( get_option( 'date_format' ) ),
						get_comment_time()
					); ?>
				</a>
				<?php edit_comment_link( __( 'edit' ), '&nbsp;&nbsp;' , '' ); ?>
			</div>
			<?php comment_text(); ?>
		</div><!-- /commentbody -->

		<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
	</div><!-- /comment -->
<?php
}

function motiontheme_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
		<div class="commentbody">
			<div class="author"><?php comment_author_link(); ?></div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _( '(Your comment is awaiting moderation...)' ); ?></em>
			<?php endif; ?>
			<?php comment_text(); ?>
		</div><!-- /commentbody -->
	</div>
<?php
}

// Custom header image
define( 'HEADER_TEXTCOLOR', '' );
define( 'HEADER_IMAGE', '%s/images/genericlogo.png' );
define( 'HEADER_IMAGE_WIDTH', 50 );
define( 'HEADER_IMAGE_HEIGHT', 50 );
define( 'NO_HEADER_TEXT', true );

function admin_header_style() {
?>

<style type="text/css">
#headimg {
	background-color: #005760;
	background-position: 50% 50%;
	background-repeat: no-repeat;
	height: <?php echo HEADER_IMAGE_HEIGHT;?>px;
	width: <?php echo HEADER_IMAGE_WIDTH;?>px;
	padding: 25px;
}
#headimg h1, #headimg #desc {
	display: none;
}
</style>

<?php }

add_custom_image_header( '', 'admin_header_style' );

// Theme options: hide categories, hide home link
function motion_hide_categories() {
	return get_option( 'motion_hide_categories' );
}

function motion_hide_homelink() {
	return get_option( 'motion_hide_homelink' );
}

if ( ! function_exists( 'motion_post_meta' ) ) :
	function motion_post_meta() {
		if ( is_multi_author() ) {
			printf( __( 'Filed under: %1$s by %2$s &mdash; ' ),
				get_the_category_list(', '),
				get_the_author_meta( 'display_name' )
			);
		} else {
			printf( __( 'Filed under: %1$s &mdash; ' ),
				get_the_category_list(', ')
			);
		}
	}
endif;