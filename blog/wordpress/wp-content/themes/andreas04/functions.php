<?php

$themecolors = array(
	'bg' => 'e6e6e6',
	'text' => '000000',
	'link' => '226699',
	'border' => 'e6e6e6',
	'url' => '226699'
);

if ( function_exists('register_sidebars') )
	register_sidebars(2);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function andreas04_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background: #<?php echo get_background_color(); ?>; }
	</style>
	<?php }
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
	<style type="text/css">
		#container { background: none; border: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'andreas04_custom_background' );

add_theme_support( 'automatic-feed-links' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'andreas04' )
) );

// Fallback for primary navigation
function andreas04_page_menu() { ?>
<ul>
	<li><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'andreas04' ); ?></a></li>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

function andreas04_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<?php comment_text() ?>
	<p class="vcard"><cite>
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php comment_type(__('Comment','andreas04'), __('Trackback','andreas04'), __('Pingback','andreas04')); ?> 
	<?php _e('by','andreas04'); ?> 
	<span class="fn"><?php comment_author_link() ?></span> | 
	<?php comment_date() ?> <!-- @ <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a> --> 
	<?php edit_comment_link(__('Edit','andreas04'), ' | '); ?>
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'before'=>' | ', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?> 
	</cite></p>
	</div>
<?php
}