<?php

$content_width = 500;

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '265E15',
	'border' => 'ffffff',
	'url' => '265E15'
);

add_theme_support( 'automatic-feed-links' );

add_custom_background();

register_sidebar(array(
	'before_widget' => '<li class="sidebox">',
	'after_widget' => '</li>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
));

// Register Nav Menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'mistylook' ),
) );

// Optionally add listed search to nav menus
function mistylook_nav_menu_search( $items ) {
	$mistylook_options = get_option('mistylook_theme_options'); // hide-header-search
	
	if ( !$mistylook_options['hide-header-search'] ) {
		$items .= '<li class="search"><form method="get" id="searchform" action="' . get_bloginfo('url') . '"><input type="text" class="textbox" value="' . esc_html( get_search_query() ) . '" name="s" id="s" /><input type="submit" id="searchsubmit" value="' . __('Search','mistylook') . '" /></form></li>';		
	}
	
	return $items;
}
add_filter( 'wp_nav_menu_items', 'mistylook_nav_menu_search' );

// A custom fallback for the MistyLook menus
function mistylook_menu_fallback() { ?>
	<div class="menu">
		<ul>
			<li <?php if(is_front_page()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('url'); ?>/" title="<?php _e('Home','mistylook'); ?>"><?php _e('Home','mistylook'); ?></a></li>
			<?php wp_list_pages('title_li=&depth=1'); ?>
			<?php $mistylook_options = get_option('mistylook_theme_options'); // hide-header-search ?>
			<?php if ( !$mistylook_options['hide-header-search'] ) : ?>
			<li class="search"><form method="get" id="searchform" action="<?php bloginfo('url'); ?>"><input type="text" class="textbox" value="<?php the_search_query(); ?>" name="s" id="s" /><input type="submit" id="searchsubmit" value="<?php _e('Search','mistylook'); ?>" /></form></li>
			<?php endif; ?>
		</ul>	
	</div>
<?php }

function mistylook_widgets_init() {
	unregister_widget('WP_Widget_Links');
	wp_register_sidebar_widget('links', __('Links'), 'mistylook_ShowLinks');
}
add_action('widgets_init', 'mistylook_widgets_init');

function mistylook_ShowLinks() {
	wp_list_bookmarks(array(
		'class' => 'linkcat widget sidebox'
	));
}

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/img/misty.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 760);
define('HEADER_IMAGE_HEIGHT', 190);
define( 'NO_HEADER_TEXT', true );

function mistylook_admin_header_style() {
?>
<style type="text/css">
#headimg {
	background: url('<?php header_image() ?>') no-repeat;
}
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
function mistylook_header_style() {
?>
<style type="text/css">
#headerimage {
	background: url('<?php header_image() ?>') no-repeat;
}
</style>
<?php
}
if ( function_exists('add_custom_image_header') ) {
	add_custom_image_header('mistylook_header_style', 'mistylook_admin_header_style');
}


function mistylook_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
		<div id="div-comment-<?php comment_ID( ); ?>">
		<div class="cmtinfo"><em><?php edit_comment_link(__('edit this','mistylook'),'',''); ?> <?php _e('on','mistylook'); ?> <a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date() ?> <?php _e('at','mistylook'); ?> <?php comment_time() ?></a><?php echo comment_reply_link(array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ')) ?></em> <?php echo get_avatar( $comment, 48 ); ?> <cite><?php comment_author_link() ?></cite></div>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.','mistylook'); ?></em><br />
			<?php endif; ?>
			<?php comment_text() ?>
			<br style="clear: both" />
		</div>
<?php
}

function mistylook_get_author_posts_link() {
	global $authordata;
	return sprintf(
		'<a href="%1$s" title="%2$s">%3$s</a>',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ),
		get_the_author()
	);
}

// We loves us a Theme Option or two. =]
add_action( 'admin_init', 'mistylook_theme_options_init' );
add_action( 'admin_menu', 'mistylook_theme_options_add_page' );

// Init theme options to white list our options
function mistylook_theme_options_init() {
	register_setting( 'mistylook_theme', 'mistylook_theme_options', 'mistylook_theme_options_validate' );
}

// Load up the menu page
function mistylook_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'mistylook_theme_options_do_page' );
}

// Create the options page
function mistylook_theme_options_do_page() {
	?>
	<div class="wrap">
	    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( 'true' == $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields('mistylook_theme'); ?>
			<?php $options = get_option('mistylook_theme_options'); ?>

			<table class="form-table">
				<?php
				/**
				 * Show search in header
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Hide the header search form?' ); ?></th>
					<td>
						<input id="mistylook_theme_options[hide-header-search]" name="mistylook_theme_options[hide-header-search]" type="checkbox" value="1" <?php checked('1', $options['hide-header-search']); ?> />
						<label class="description" for="mistylook_theme_options[hide-header-search]"><?php _e( 'Yes, I\'d like to hide the header search form.' ); ?></label>
					</td>
				</tr>
				<?php
				/**
				 * Show single post navigation
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Hide single post navigation?' ); ?></th>
					<td>
						<input id="mistylook_theme_options[hide-post-nav]" name="mistylook_theme_options[hide-post-nav]" type="checkbox" value="1" <?php checked('1', $options['hide-post-nav']); ?> />
						<label class="description" for="mistylook_theme_options[hide-post-nav]"><?php _e( 'Yes, I\'d like to hide the single post navigation links.' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function mistylook_theme_options_validate( $input ) {
	// Checkbox value should be 0 or 1
	$input['hide-header-search'] = ( $input['hide-header-search'] == 1 ? 1 : 0 );
	$input['hide-post-nav'] = ( $input['hide-post-nav'] == 1 ? 1 : 0 );

	return $input;
}