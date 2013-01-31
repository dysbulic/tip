<?php
/**
 * @package WordPress
 * @subpackage Unsleepable
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => 'da1071',
	'border' => 'cccccc',
	'url' => '0d78b6',
);

$content_width = 500; // actually 503

add_filter( 'body_class', '__return_empty_array', 1 );

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'unsleepable', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


/* blast you red baron! */
require_once (ABSPATH . WPINC . '/class-snoopy.php' );

// WordPress Wigets
if (function_exists( 'register_sidebar' )) 
	register_sidebar(array( 'before_widget' => '<li id="%1$s" class="widget %2$s">','after_widget' => '</li>', 'name' => __( 'Main Sidebar', 'unsleepable' ), 'id' => 'main-sidebar' ));

if (function_exists( 'register_sidebar' )) 
	register_sidebar(array( 'before_widget' => '<div id="%1$s" class="widget %2$s bottomblock">','after_widget' => '</div>', 'before_title' => '<h2>', 'after_title' => '</h2>', 'name' => __( 'Bottom Bar', 'unsleepable' ), 'id' => 'bottom-bar' ));

add_theme_support( 'automatic-feed-links' );

add_custom_background();

register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

function unsleepable_page_menu() { // fallback for primary navigation ?>
<ul id="menu">
	<?php wp_list_pages( 'sort_column=menu_order&depth=1&title_li=' ); ?>
</ul>
<?php }

	/* Function for seperating comments from track- and pingbacks. */
	function k2_comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
		global $comment;
		if (preg_match('|trackback|', $comment->comment_type))
			return $trackbacktxt;
		elseif (preg_match('|pingback|', $comment->comment_type))
			return $pingbacktxt;
		else
			return $commenttxt;
	}
	
function unsleepable_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $count_pings;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<a href="#comment-<?php comment_ID() ?>" class="counter" title="<?php echo esc_attr__( 'Permanent link to this comment', 'unsleepable' ); ?>"><?php echo $count_pings; $count_pings++; ?></a>
		<span class="commentauthor fn" style="font-weight: bold;"><?php comment_author_link() ?></span><small class="commentmetadata"> on <a href="#comment-<?php comment_ID() ?>" title="<?php if (function_exists('time_since')) { $comment_datetime = strtotime($comment->comment_date); echo time_since($comment_datetime) ?> ago<?php } else { echo esc_attr__( 'Permanent link to this comment', 'unsleepable' ); } ?>"><?php comment_date() ?></a> <?php _e( 'said:', 'unsleepable' ); ?></small>
		<?php if ( $user_ID ) { edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); } ?>
	</div>

	<div class="itemtext2">
		<?php comment_text() ?> 
	</div>

	<?php if ($comment->comment_approved == '0') : ?>
	<p class="alert"><strong><?php _e( 'Your comment is awaiting moderation.', 'unsleepable' ); ?></strong></p>
	<?php endif; ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

function unsleepable_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $count_pings;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? 'item' : 'item parent') ?> id="comment-<?php comment_ID() ?>"> 
	<a href="#comment-<?php comment_ID() ?>" title="<?php echo esc_attr__( 'Permanent link to this comment', 'unsleepable' ); ?>" class="counter"><?php echo $count_pings; $count_pings++; ?></a>
	<span class="commentauthor"><?php comment_author_link() ?></span>
</li>
<?php
}
