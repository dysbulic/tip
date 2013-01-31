<?php
/**
 * @package WordPress
 * @subpackage Ambiru
 */

define('HEADER_TEXTCOLOR', 'E5F2E9');
define('HEADER_IMAGE', '%s/images/header.png'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 500);
define('HEADER_IMAGE_HEIGHT', 225);

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '555555',
	'link' => '557799',
	'border' => 'C8A799',
	'url' =>  '8FB1D3'
	);

$content_width = 460;

add_filter( 'body_class', '__return_empty_array', 1 );

register_sidebar( array(
	'name'          => sprintf(__('Bottom 1')),
	'id'            => 'bottom-1',
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

register_sidebar( array(
	'name'          => sprintf(__('Bottom 2')),
	'id'            => 'bottom-2',
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

function ambiru_admin_header_style() {
?>
<style type="text/css">
#headimg{
	font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
	line-height:1.5;
	background: url(<?php header_image() ?>) no-repeat;
	background-repeat: no-repeat;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	text-align:right;
	width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
	padding:30px 0;
}
#headimg h1{
	font-family: Sylfaen, Georgia, "Times New Roman", Times, serif;
	font-weight:normal;
	letter-spacing: -1px;
	margin:0;
	font-size:2em;
	margin-top:120px;
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
#headimg h1, #desc{
	margin-right:30px;
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

add_custom_image_header('header_style', 'ambiru_admin_header_style');

// Header navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'ambiru' ),
) );

function ambiru_page_menu() { // fallback for primary navigation ?>
<ul class="menu">
	<li class="page_item"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'ambiru' ); ?></a></li>
	<?php wp_list_pages( 'depth=1&title_li=' ); ?> 
</ul>
<?php }

add_custom_background();

add_theme_support( 'automatic-feed-links' );

function ambiru_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<cite class="fn"><?php comment_author_link(); ?></cite> <span class="says"><?php _e('said', 'ambiru'); ?></span>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.', 'ambiru') ?></em>
	<br />
<?php endif; ?>

	<small class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>">
	<?php comment_date(); ?>  <?php _e('at', 'ambiru'); ?> <?php comment_time(); ?></a> <?php edit_comment_link(__('e', 'ambiru'),'',''); ?></small>

	<?php comment_text(); ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php }