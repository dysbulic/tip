<?php
/**
 * @package WordPress
 * @subpackage Misty Look
 */

$content_width = 500;

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '265e15',
	'border' => 'ededed',
	'url' => '996633',
);

add_filter( 'body_class', '__return_empty_array', 1 );

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
function mistylook_nav_menu_search( $items, $args ) {
	$mistylook_options = get_option('mistylook_theme_options'); // hide-header-search

	if ( !$mistylook_options['hide-header-search'] && $args->theme_location == 'primary' ) {
		$items .= '<li class="search"><form method="get" id="searchform" action="' . get_bloginfo('url') . '"><input type="text" class="textbox" value="' . esc_html( get_search_query() ) . '" name="s" id="s" /><input type="submit" id="searchsubmit" value="' . __('Search','mistylook') . '" /></form></li>';
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'mistylook_nav_menu_search', 1, 2 );

// A custom fallback for the MistyLook menus
function mistylook_menu_fallback() { ?>
	<div class="menu">
		<ul>
			<li <?php if(is_front_page()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('url'); ?>/" title="<?php esc_attr_e( 'Home', 'mistylook' ); ?>"><?php _e('Home','mistylook'); ?></a></li>
			<?php wp_list_pages('title_li=&depth=1'); ?>
			<?php $mistylook_options = get_option('mistylook_theme_options'); // hide-header-search ?>
			<?php if ( !$mistylook_options['hide-header-search'] ) : ?>
			<li class="search"><form method="get" id="searchform" action="<?php bloginfo('url'); ?>"><input type="text" class="textbox" value="<?php the_search_query(); ?>" name="s" id="s" /><input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'mistylook' ); ?>" /></form></li>
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

define('HEADER_TEXTCOLOR', '265E15');
define('HEADER_IMAGE', '%s/img/misty.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 760);
define('HEADER_IMAGE_HEIGHT', 190);

if ( function_exists( 'add_custom_image_header' ) ) {
	add_custom_image_header( 'mistylook_header_style', 'mistylook_admin_header_style', 'mistylook_admin_header_image' );
}

function mistylook_header_style() {
	$header_image = get_header_image();
	if ( HEADER_TEXTCOLOR == get_header_textcolor() && empty( $header_image ) )
		return;
?>
<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#header h1,
		#header h2 {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#header h1 a,
		#header h2 {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	<?php if ( ! empty( $header_image ) ) : ?>
		#headerimage {
			background: url('<?php echo $header_image; ?>') no-repeat;
			height: 200px;
		}
	<?php endif; ?>
</style>
<?php
}

function mistylook_admin_header_style() {
?>
<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1 {
		font-family: Georgia, Verdana, Arial, Serif;
		margin: 0;
	}
	#headimg h1 a {
		font-size: 22px;
		font-variant: small-caps;
		letter-spacing: 1px;
		line-height: 19px;
		text-decoration: none;
		width: 450px;
	}
	#desc {
		font-family: Tahoma, Verdana, Arial, Serif;
		font-size: 12px;
		letter-spacing: 1px;
		line-height: 19px;
		margin: 5px 0 10px 0;
		width: 450px;
	}
<?php
	// If the user has set a custom color for the text use that
	if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
?>
	#headimg h1 a,
	#desc {
		color: #<?php echo get_header_textcolor(); ?>;
	}
<?php endif; ?>
	#headimg img {
		max-width: 760px;
		height: auto;
		width: 100%;
	}
</style>
<?php
}

function mistylook_admin_header_image() {
?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php
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

		<?php if ( isset( $_REQUEST['settings-updated'] ) && 'true' == $_REQUEST['settings-updated'] ) : ?>
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

				<?php
				/**
				 * Hide Post and Comments Feed Links
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Hide posts feed link and icon?' ); ?></th>
					<td>
						<input id="mistylook_theme_options[hide-post-feed-link]" name="mistylook_theme_options[hide-post-feed-link]" type="checkbox" value="1" <?php checked('1', $options['hide-post-feed-link']); ?> />
						<label class="description" for="mistylook_theme_options[hide-post-feed-link]"><?php _e( 'Yes, I\'d like to hide the posts feed link and icon.' ); ?></label>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Hide comments feed link and icon?' ); ?></th>
					<td>
						<input id="mistylook_theme_options[hide-comments-feed-link]" name="mistylook_theme_options[hide-comments-feed-link]" type="checkbox" value="1" <?php checked('1', $options['hide-comments-feed-link']); ?> />
						<label class="description" for="mistylook_theme_options[hide-comments-feed-link]"><?php _e( 'Yes, I\'d like to hide the comments feed link and icon.' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'mistylook' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function mistylook_theme_options_validate( $input ) {
	// Checkbox value should be 0 or 1
	$input['hide-header-search'] = ( isset( $input['hide-header-search'] ) && $input['hide-header-search'] == 1 ? 1 : 0 );
	$input['hide-post-nav'] = ( isset( $input['hide-post-nav'] ) && $input['hide-post-nav'] == 1 ? 1 : 0 );
	$input['hide-post-feed-link'] = ( isset( $input['hide-post-feed-link'] ) && $input['hide-post-feed-link'] == 1 ? 1 : 0 );
	$input['hide-comments-feed-link'] = ( isset( $input['hide-comments-feed-link'] ) && $input['hide-comments-feed-link'] == 1 ? 1 : 0 );

	return $input;
}
