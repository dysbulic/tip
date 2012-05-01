<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '29303b',
	'link' => '909d73'
	);

$content_width = 510;

add_theme_support( 'automatic-feed-links' );

define('HEADER_TEXTCOLOR', 'B5C09D');
define('HEADER_IMAGE', '%s/img/just-train.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 741);
define('HEADER_IMAGE_HEIGHT', 142);

function header_style() {
?>
<style type="text/css">
#headimg {
	background:#7d8b5a url(<?php header_image() ?>) center repeat-y;
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headimg h1 a, #headimg #desc {
	display: none;
}
<?php } else { ?>
#headimg h1 a, #headimg h1 a:hover, #headimg #desc {
	color: #<?php header_textcolor() ?>;
}	
<?php } ?>
</style>
<?php
}

function connections_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1
{
	margin: 0;
	font-size: 1.6em;
	padding:10px 20px 0 0;
	text-align:right;
}
#headimg h1 a {
	color:#<?php header_textcolor() ?>;
	border: none;
	font-family: Georgia, "Lucida Sans Unicode", lucida, Verdana, sans-serif;
	font-weight: normal;
	letter-spacing: 1px;
	text-decoration: none;
}
#headimg a:hover
{
	text-decoration:underline;
}
#headimg #desc
{
	font-weight:normal;
	font-style:italic;
	font-size:1em;
	color:#<?php header_textcolor() ?>;
	text-align:right;
	margin:0;
	padding:0 20px 0 0;
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

add_custom_image_header('header_style', 'connections_admin_header_style');

if ( function_exists('register_sidebars') )
	register_sidebars(1);

register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

function connections_page_menu() { // fallback for primary navigation ?>
	<ul id="topnav">
		<li><a href="<?php bloginfo('url'); ?>" id="navHome" title="<?php _e('Posted Recently') ?>" accesskey="h"><?php _e('Home') ?></a></li>
		<?php wp_list_pages('depth=1&title_li=' ); ?>
	</ul>

<?php }

add_custom_background();

function connections_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<?php printf(__('<cite class="fn">%s</cite> <span class="says">Says:</span>'), get_comment_author_link()) ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.') ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" title="">
	<?php comment_date(); ?> at <?php comment_time(); ?></a> <?php edit_comment_link('e','',''); ?></small>

	<?php comment_text(); ?>
	
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}