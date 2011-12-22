<?php
/**
 * @package WordPress
 * @subpackage Iceburgg
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '545454',
	'link' => '003366',
	'border' => 'ecf7fd',
	'url' => '003949',
);

$content_width = 565;

add_filter( 'body_class', '__return_empty_array', 1 );

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'iceburgg', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebars') )
	register_sidebars(3);

// No CSS, just IMG call

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/imgs/freehead.gif'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 790);
define('HEADER_IMAGE_HEIGHT', 150);
define( 'NO_HEADER_TEXT', true );

function iceburgg_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}

</style>
<?php
}

function header_style() {
?>
<style type="text/css">
#header{
	background: url(<?php header_image() ?>) no-repeat;
}
</style>
<?php
}

add_custom_image_header('header_style', 'iceburgg_admin_header_style');

function iceburgg_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background: #<?php echo get_background_color(); ?>; }
	</style>
	<?php }
}
add_action( 'wp_head', 'iceburgg_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'iceburgg' )
) );

// Fallback for primary navigation
function iceburgg_page_menu() { ?>
	<ul class="menu">
		<li><a href="<?php echo home_url( '/' ); ?>">Home</a></li>
		<?php wp_list_pages( 'depth=1&title_li=' ); ?>
	</ul>
<?php }

/* Function for seperating comments from track- and pingbacks. */
function comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
	global $comment;
	if (preg_match('|trackback|', $comment->comment_type))
		return $trackbacktxt;
	elseif (preg_match('|pingback|', $comment->comment_type))
		return $pingbacktxt;
	else
		return $commenttxt;
}

function iceburgg_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
 <li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
 <div id="div-comment-<?php comment_ID() ?>">
      <span class="comment-author vcard">
      <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
      <span class="cauthor fn">
      <?php comment_author_link() ?>
      </span>
      </span>
      <?php if ($comment->comment_approved == '0') : ?>
      <em><?php _e( 'Your comment is awaiting moderation.', 'iceburgg' ); ?></em>
      <?php endif; ?>
      <br />
      <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" class="permalink" title=""></a>
      <?php comment_text() ?>
      <div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
 </div>
<?php
}