<?php
/**
 * @package WordPress
 * @subpackage Benevolence
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => 'ff3333',
	'border' => '000000',
	'url' => '394651',
	);

$content_width = 420;

add_filter( 'body_class', '__return_empty_array', 1 );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

load_theme_textdomain('benevolence');

function benevolence_widgets_init() {
	register_sidebars(1);
	unregister_widget('WP_Widget_Search');
	wp_register_sidebar_widget('search', __('Search', 'benevolence'), 'benevolence_search');
}
add_action('widgets_init', 'benevolence_widgets_init');

function benevolence_search() {
?>
<li>
	<form id="searchform" method="get" action="<?php bloginfo('url'); ?>">
	<h2><?php _e('Search:', 'benevolence'); ?></h2>
	<input type="text" class="input" name="s" id="search" size="15" />
	<input name="submit" type="submit" tabindex="5" value="<?php esc_attr_e( 'GO', 'benevolence' ); ?>" />
	</form>
</li>
<?php
}

?>
<?php

define( 'HEADER_TEXTCOLOR', '000000' );
define( 'HEADER_IMAGE', '%s/images/masthead.jpg' ); // %s is theme dir uri
define( 'HEADER_IMAGE_WIDTH', 700 );
define( 'HEADER_IMAGE_HEIGHT', 225 );

register_default_headers( array(
	'masthead' => array(
		'url' => '%s/images/masthead.jpg',
		'thumbnail_url' => '%s/images/masthead-thumbnail.jpg',
		/* translators: header image description */
		'description' => __( 'Grass', 'benevolence' )
	),
	'ice' => array(
		'url' => '%s/images/ice.jpg',
		'thumbnail_url' => '%s/images/ice-thumbnail.jpg',
		/* translators: header image description */
		'description' => __( 'Ice', 'benevolence' )
	),
	'red-soil' => array(
		'url' => '%s/images/red-soil.jpg',
		'thumbnail_url' => '%s/images/red-soil-thumbnail.jpg',
		/* translators: header image description */
		'description' => __( 'Red Soil', 'benevolence' )
	),
	'water-and-rocks' => array(
		'url' => '%s/images/water-and-rocks.jpg',
		'thumbnail_url' => '%s/images/water-and-rocks-thumbnail.jpg',
		/* translators: header image description */
		'description' => __( 'Water and Rocks', 'benevolence' )
	)
) );

function header_style() {
?>
<style type="text/css">
#masthead{
	background: url(<?php header_image() ?>) no-repeat;
}
<?php if ( 'blank' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) ) { ?>
#blogTitle, #blogTitle a {
	display: none;
}
<?php } else { ?>
#masthead h1#blogTitle, #masthead #blogTitle a, #blogTitle a:hover {
	color: #<?php header_textcolor() ?>;
}

<?php } ?>
</style>
<?php
}

function benevolence_admin_header_style() {
?>
<style type="text/css">

#headimg {
	position: relative;
	top: 0px;
	background: url(<?php header_image() ?>);
 	width: 700px;
 	height: 225px;
	margin: 0px;
	margin-top: 0px;
}

#headimg h1 {
	position: relative;
	top: 50px;
	left: 20px;
	font-family: 'Arial Black';
	color: #<?php header_textcolor() ?>;
	font-size: 8pt;
	text-transform: uppercase;
	text-align: left;
}

#headimg h1 a {
	color: #<?php header_textcolor() ?>;
	border-bottom: none;
}

#desc { display: none; }

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

add_custom_image_header('header_style', 'benevolence_admin_header_style');

function benevolence_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? 'commentBox' : 'commentBox parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php comment_text() ?>
	<span class="comment-author vcard">
	<i><?php comment_type(__('Comment','benevolence'), __('Trackback','benevolence'), __('Pingback','benevolence')); ?> <?php _e('by','benevolence'); ?> <cite class="fn"><?php comment_author_link() ?></cite>
	</span>
	<span class="comment-meta commentmetadata">
	<?php comment_date(); ?> @ <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time() ?></a> <?php edit_comment_link(__('Edit This','benevolence'), ' |'); ?></i>
	</span>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
	<br />
<?php
}