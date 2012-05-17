<?php
/**
 * @package WordPress
 * @subpackage Neat
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '61636a',
	'link' => '36769c',
	'border' => 'dddddd',
	'url' => '399cc6',
);

$content_width = 450;

add_filter( 'body_class', '__return_empty_array', 1 );

load_theme_textdomain('neat');

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function neat_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'neat_custom_background' );


define('HEADER_TEXTCOLOR', 'blank');
define('HEADER_IMAGE', '%s/images/header.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 700);
define('HEADER_IMAGE_HEIGHT', 200);

function header_style() {
?>
<style type="text/css">
#header {
	font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
	text-align: left;
	background: url(<?php header_image() ?>);
 	width: <?php echo HEADER_IMAGE_WIDTH ?>px;
 	height: <?php echo HEADER_IMAGE_HEIGHT ?>px;
	margin: 0px;
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header * {
	display: none;
}
<?php } else { ?>
#header h1 {
	display: block;
	color: #<?php header_textcolor() ?>;
	font-size: 28px;
	margin: 0 0 5px 15px;
	padding-top: 15px;
}
#header .description {
	display: block;
	color: #<?php header_textcolor() ?>;
	font-size: 14px;
	margin-left: 15px;
}
#header a, #header a:active, #header a:hover, #header a:visited {
	color: #<?php header_textcolor() ?>;
}
<?php } ?>
</style>
<?php
}

function admin_header_style() {
?>
<style type="text/css">

#header {
	text-align: left;
	background: url(<?php header_image() ?>) no-repeat top left;
 	width: <?php echo HEADER_IMAGE_WIDTH ?>px;
 	height: <?php echo HEADER_IMAGE_HEIGHT ?>px;
	margin: 0px;
}

#header h1 {
	font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
	color: #<?php header_textcolor() ?>;
	font-size: 28px;
	margin: 0 0 5px 15px;
	padding-top: 10px;
}

#header .description {
	font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
	color: #<?php header_textcolor() ?>;
	font-size: 14px;
	margin-left: 15px;
}
#header a, #header a:active, #header a:hover, #header a:visited {
	font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
	color: #<?php header_textcolor() ?>;
	border-bottom: none;
}

<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headimg h1, #headimg #desc {
	display: none;
}
<?php } ?>

</style>
<?php
}

add_custom_image_header('header_style', 'admin_header_style');

if ( function_exists('register_sidebars') )
	register_sidebars(1);

function neat_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>

<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php comment_text() ?>
	<small class="comment-meta commentmetadata">
            &nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php bloginfo('stylesheet_directory'); ?>/images/<?php echo 'rtl' == get_bloginfo( 'text_direction' ) ? 'comment_arr-rtl.gif' : 'comment_arr.gif'; ?>" alt="" />
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php _e('by'); ?></a>
            <cite class="fn"><?php comment_author_link() ?></cite>
			 <?php comment_date() ?> at <?php comment_time() ?>
             <?php edit_comment_link('edit comment','',''); ?>
             <?php if ($comment->comment_approved == '0') : ?>
			 <em><?php _e('Your comment is awaiting moderation.'); ?></em>
             <?php endif; ?></small><br />
	</div>
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
</div>
<?php
}