<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */

$themecolors = array(
	'bg' => '301e07',
	'border' => 'CC9752',
	'text' => 'cccc9a',
	'link' => 'ff99cc',
	'url' => '21B6A8',
);
$content_width = 570; // pixels

add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebars') )
    register_sidebars(2);

function darkwood_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div class="com-wrapper <?php if (1 == $comment->user_id) echo "admin"; ?>">
	        	<div id="comment-<?php comment_ID(); ?>" class="com-header">
					<?php if ( function_exists( 'get_avatar' ) ) { echo get_avatar( $comment, '48' ); } ?>

	                <p class="tp">
	                    <span class="commentauthor"><?php comment_author_link(); ?></span>
	                    <?php if ($comment->comment_approved == '0') : ?>
	                    <em><?php _e('Your comment is awaiting moderation'); ?>.</em>
	                    <?php endif; ?>
                        <br />
	                    <span class="commentmetadata">
	                    	<a href="#comment-<?php comment_ID(); ?>" title=""><?php printf( __('%1$s at %2$s'), get_comment_time(__('F jS, Y')), get_comment_time(__('H:i')) ); ?></a>
	                    	<?php edit_comment_link(__('Edit'),'&nbsp;&nbsp;',''); ?>
	                    </span>
					</p>

	            </div>

				<div class="comment-content"><?php comment_text(); ?></div>
			    <div class="reply">
			    	<p><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?></p>
			    </div>
		    </div>
<?php
}

function darkwood_ping($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>

		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div class="com-wrapper">
	        	<div id="comment-<?php comment_ID(); ?>" class="com-header">
                    <?php if( function_exists( 'get_avatar' ) ) { echo get_avatar( $comment, '48' ); } ?>

	                <p class="tp">
	                    <span><?php comment_author_link(); ?></span>
	                    <?php if ($comment->comment_approved == '0') : ?>
	                    <em><?php _e('Your comment is awaiting moderation'); ?>.</em>
	                    <?php endif; ?>

	                    <span class="commentmetadata">
	                    	<a href="#comment-<?php comment_ID(); ?>" title=""><?php printf( __('%1$s at %2$s'), get_comment_time(__('F jS, Y')), get_comment_time(__('H:i')) ); ?></a>
	                    	<?php edit_comment_link(__('Edit'),'&nbsp;&nbsp;',''); ?>
	                    </span>
					</p>

	            </div>

				<div class="comment-content"><?php comment_text(); ?></div>
			</div>
<?php
}

/*
	Custom background image and color
*/
define( 'BACKGROUND_IMAGE', '%s/images/background.jpg' ); // %s is theme dir uri
define( 'BACKGROUND_COLOR', '0f0100' ); // this is a placeholder until background color is enabled in core

function darkwood_custom_background() {
	$background = get_background_image();

	if ( !$background )
		return;

	$repeat = get_theme_mod('background_repeat', 'repeat');
	if ( 'no-repeat' == $repeat )
		$repeat = 'background-repeat: no-repeat;';
	else
		$repeat = 'background-repeat: repeat;';
	$position = get_theme_mod('background_position', 'left');
	if  ( 'center' == $position )
		$position = 'background-position: top center;';
	elseif ( 'right' == $position )
		$position = 'background-position: top right;';
	else
		$position = 'background-position: top left;';
	$attachment = get_theme_mod('background_attachment', 'fixed');
	if ( 'scroll' == $attachment )
		$attachment = 'background-attachment: scroll;';
	else
		$attachment = 'background-attachment: fixed;';
?>

<style type="text/css">
body {
	background-color: #<?php echo BACKGROUND_COLOR; ?>;
	background-image: url(<?php background_image(); ?>);
	<?php echo $repeat; ?>
	<?php echo $position; ?>
	<?php echo $attachment; ?>
}
</style>

<?php
}

add_custom_background('darkwood_custom_background');

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'darkwood' )
) );

// Fallback for primary navigation
function darkwood_page_menu() {
	wp_page_menu( 'sort_column=menu_order&depth=1' );
}