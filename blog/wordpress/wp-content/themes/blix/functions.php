<?php
/**
 * @package WordPress
 * @subpackage Blix
 */

include (TEMPLATEPATH . '/BX_functions.php');

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '414141',
	'link' => '6C8C37',
	'border' => 'EDE8E2',
	'url' => '009193',
);

$content_width = 455;

add_filter( 'body_class', '__return_empty_array', 1 );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'blix', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


function widget_blix_categories() {
?>
	<h2><em><?php _e( 'Categories', 'blix' ); ?></em></h2>

	<ul class="categories">
	<?php wp_list_cats('sort_column=name'); ?>
	</ul>
<?php
}

function widget_blix_feeds() {
?>
	<h2><em><?php _e( 'Feeds', 'blix' ); ?></em></h2>

	<ul class="feeds">
	<li><a href="<?php bloginfo_rss('rss2_url'); ?> "><?php _e( 'Entries (RSS)' ); ?></a></li>
	<li><a href="<?php bloginfo_rss('comments_rss2_url'); ?> "><?php _e( 'Comments (RSS)' ); ?></a></li>
	</ul>
<?php
}

function widget_blix_recent_posts($args) {
	extract($args);
	$options = get_option('widget_recent_entries');
	$title = empty($options['title']) ? __( 'Recent Posts', 'blix' ) : $options['title'];
	if ( !$number = (int) $options['number'] )
		$number = 10;
	else if ( $number < 1 )
		$number = 1;
	else if ( $number > 15 )
		$number = 15;
?>
	<h2><em><?php _e($title); ?></em></h2>

	<ul class="posts">
	<?php BX_get_recent_posts($p, $number); ?>
	</ul>
<?php
}

register_sidebars(1, array(
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => "</div>",
	'before_title' => '<h2><em>',
	'after_title' => '</em></h2>',
));

wp_register_sidebar_widget('categories', __( 'Categories', 'blix' ), 'widget_blix_categories');
unregister_widget_control('categories');
wp_register_sidebar_widget('feeds', __( 'Feeds', 'blix' ), 'widget_blix_feeds');
wp_register_sidebar_widget('recent-posts', __( 'Recent Posts', 'blix' ), 'widget_blix_recent_posts');
unregister_widget_control('recent-posts');

define('HEADER_TEXTCOLOR', '009193');
define('HEADER_IMAGE', '%s/images/spring_flavour/header_bg.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 690);
define('HEADER_IMAGE_HEIGHT', 115);

function header_style() {
?>
<style type="text/css">
#header{
	background: url(<?php header_image() ?>) no-repeat;
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header h1, #header #desc {
	display: none;
}
<?php } else { ?>
#header h1 a, #desc {
	color:#<?php header_textcolor() ?>;
}
#desc {
	margin-right: 30px;
}
<?php } ?>
</style>
<?php
}

function blix_admin_header_style() {
?>
<style type="text/css">
#headimg{
	background: url(<?php header_image() ?>) no-repeat;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
  padding:0 0 0 18px;
}

#headimg h1{
	padding-top:40px;
	margin: 0;
}
#headimg h1 a{
	color:#<?php header_textcolor() ?>;
	text-decoration: none;
	border-bottom: none;
}
#headimg #desc{
	color:#<?php header_textcolor() ?>;
	font-size:1em;
	margin-top:-0.5em;
}

#desc {
	display: none;
}

<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headimg h1, #headimg #desc {
	display: none;
}
#headimg h1 a, #headimg #desc {
	color:#<?php echo HEADER_TEXTCOLOR ?>;
}
<?php } ?>

</style>
<?php
}

add_custom_image_header('header_style', 'blix_admin_header_style');

// Nav menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'connections' ),
) );

function blix_page_menu() { // fallback for primary navigation ?>
<ul class="menu">
	<li<?php if (is_home()) echo " class='selected'"; ?>><a href="<?php bloginfo('url'); ?>">Home</a></li>
	<?php wp_list_pages('depth=1&title_li=' ); ?>
</ul>
<?php }

global $commentcount;
$commentcount = 1;

function blix_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	global $comment_alt, $commentcount;
	if ( $comment_alt % 2 ) $commentalt = ' alt';
?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<p class="header<?php echo $commentalt; ?>"><strong><?php echo $commentcount ?>.</strong>

		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>

		<span class="fn"><?php if ($comment->comment_type == "comment") comment_author_link();
			  else {
			  		strlen($comment->comment_author) > 25 ? $author = substr($comment->comment_author,0,25) . "&hellip" : $author=substr($comment->comment_author,0,25);
			  		echo get_comment_author_link();

			  }
		?></span> &nbsp;|&nbsp; <?php printf( __( '%1$s at %2$s', 'blix' ), get_comment_date(), get_comment_time() ); ?></p>
	</div>
	<?php if ($comment->comment_approved == '0') : ?><p><em><?php _e( 'Your comment is awaiting moderation.', 'blix' ); ?></em></p><?php endif; ?>
	<?php comment_text(); ?>
	<?php edit_comment_link( __( 'Edit Comment', 'blix' ), '<span class="editlink">', '</span>' ); ?>
	<span class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	</span>
	</div>

<?php
	$commentcount++;
}

function blix_posted_on() {
	printf( __( '<em class="date">%1$s</em>', 'blix' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s at %2$s</a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
}

function blix_posted_by() {
	if ( is_multi_author() && ! is_author() ) {
		printf( __( '<em class="author"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></em>', 'blix' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'blix' ), get_the_author_meta( 'display_name' ) ) ),
			esc_attr( get_the_author_meta( 'display_name' ) )
		);
	}
}