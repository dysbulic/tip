<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */

if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'd9d9d9',
		'text' => '000000',
		'link' => '222222',
		'border' => '404040',
		'url' => 'ffffff',
	);
}

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 315;

// define widths
define( 'MIN_WIDTH', 560 );
define( 'MAX_WIDTH', 840 );

// Tell WordPress to run monotone_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'monotone_setup' );

function monotone_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'monotone', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . '/languages/$locale.php';
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'monotone' ),
	) );
}

// Register widgetized areas
function monotone_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Footer Area One', 'monotone' ),
		'id' => 'sidebar-1',
		'description' => __( 'An optional widget in the footer', 'monotone' ),
		'before_widget' => '<div id="%1$s" class="clearfix widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'monotone_widgets_init' );

// filters and actions
add_action( 'wp_head', 'monotone_header_function' );
add_filter( 'the_content', 'monotone_image_scrape', 0 );
add_action( 'publish_post', 'monotone_image_setup' );
add_action( 'publish_page', 'monotone_image_setup' );

function monotone_header_function() {
	global $vertical;
	if ( ! is_single() && is_home() && ! is_archive() )
		query_posts( array( 'post__not_in' => get_option( 'sticky_posts' ), 'posts_per_page' => 1 ) );
	if ( ! is_archive() && ! is_search() ) : ?>
		<style type="text/css" media="screen">
		<?php
		while ( have_posts() ) : the_post();
			// excecute the specific stylesheet
			monotone_print_stylesheet();
			// determine if an image is vertical or not
			if ( monotone_is_vertical( monotone_the_image_url( true ) ) ) { $vertical = true; }
 		endwhile; rewind_posts(); ?>
		</style>
	<?php endif;
}

// Returns the current monotone layout as selected in the theme options
function monotone_layout_type() {
	global $vertical;
	if ( $vertical ) {
		if ( ! is_archive() || ! is_search() ) {
			$layout = 'vertical';
		}
	}
	elseif ( is_archive() || is_search() || is_404() ) $layout = 'archive';
	else $layout = 'default';

	return $layout;
}

// Adds monotone_layout_type() to the array of body classes
function monotone_body_class( $classes ) {
	$classes[] = monotone_layout_type();

	return $classes;
}
add_filter( 'body_class', 'monotone_body_class' );

/**
 * Used by the preg_replace_callback() in monotone_image_scrape().
 * Most empty tags should be replaced but not all.
 * For example, stripping an empty <embed></embed> breaks YouTube videos.
 */
function monotone_replace_empty_tag( $m ) {
	$no_replace = array( 'embed' ); // expand as required
	if ( in_array( strtolower( $m[1] ), $no_replace ) )
		return $m[0]; // return the original untouched
	return '';
}

// remove image tag from post_content for display
function monotone_image_scrape( $entry ) {
	// don't scrape the image for the feed
	if ( is_feed() ) { return $entry; }

	$entry = str_replace( '[/wp_caption]','', $entry );
	$entry = str_replace( '[/caption]','', $entry );

	//remove image tag
	$entry = preg_replace( '/<img [^>]*src=(\"|\').+?(\1)[^>]*\/*>/','', $entry );

	//remove any empty tags left by the scrape.
	$entry = str_replace( '<p> </p>', '', $entry );
	$entry = preg_replace_callback( '|<([a-z]+)[^>]*>\s*</\1>|i', 'monotone_replace_empty_tag', $entry );
	$entry = preg_replace_callback( '|<([a-z]+)[^>]*>\s*</\1>|i', 'monotone_replace_empty_tag', $entry );
	$entry = preg_replace_callback( '|<([a-z]+)[^>]*>\s*</\1>|i', 'monotone_replace_empty_tag', $entry );
	return $entry;
}

// this resets post meta
function monotone_reset_colors( $post ) {
	global $post;
	delete_post_meta( $post->ID, 'image_md5' );
	delete_post_meta( $post->ID, 'image_url' );
	delete_post_meta( $post->ID, 'image_size' );
	delete_post_meta( $post->ID, 'image_tag' );
	delete_post_meta( $post->ID, 'image_color_base' );
	delete_post_meta( $post->ID, 'image_colors' );
	delete_post_meta( $post->ID, 'image_colors_bg' );
	delete_post_meta( $post->ID, 'image_colors_fg' );
}

function monotone_image_setup( $postid ) {
	global $post;
	$post = get_post( $postid );

	// get url
	if ( ! preg_match( '/<img ([^>]*)src=(\"|\')(.+?)(\2)([^>\/]*)\/*>/', $post->post_content, $matches ) ) {
		monotone_reset_colors( $post );
		return false;
	}

	// url setup
	$post->image_url = $matches[3];

	if ( ! $post->image_url = preg_replace( '/\?w\=[0-9]+/','', $post->image_url ) )
		return false;

	$post->image_url = esc_url( $post->image_url, 'raw' );
	$previous_md5 = get_post_meta( $post->ID, 'image_md5', true );
	$previous_url = get_post_meta( $post->ID, 'image_url', true );

	if ( ( md5( $post->image_tag ) != $previous_md5 ) or ( $post->image_url != $previous_url ) ) {
		monotone_reset_colors( $post );

		add_post_meta( $post->ID, 'image_url', $post->image_url );
		add_post_meta( $post->ID, 'image_md5', md5($post->image_tag ) );

		//image tag setup
		$extra = $matches[1].' '.$matches[5];
		$extra = preg_replace( '/width=(\"|\')[0-9]+(\1)/','', $extra );
		$extra = preg_replace( '/height=(\"|\')[0-9]+(\1)/','', $extra );
		$width = ( monotone_is_vertical( $post->image_url ) ) ? MIN_WIDTH : MAX_WIDTH;


		delete_post_meta( $post->ID, 'image_tag' );
		add_post_meta( $post->ID, 'image_tag', '<img src="'.$post->image_url.'?w='.$width.'" '.$extra.' />' );

		// get colors
		monotone_get_all_colors( $post );
		return false;
	}

	return true;
}

function monotone_is_vertical( $url ) {
	if ( preg_match( '/(jpg|jpeg|jpe|JPEG|JPG|png|PNG|gif|GIF)/',$url ) ) {
		global $post;
		$size = get_post_meta( $post->ID, 'image_size', true );
		if ( ! $size ) {
			$size = getimagesize( $url );
			add_post_meta( $post->ID, 'image_size', $size );
		}
		$post->image_width = $size[0];
		if ( $size ) {
			if ( $size[0] == $size[1] ) return true;
			if ( $size[0] < $size[1] ) return true;
			if ( $size[0] < MIN_WIDTH ) return true;
		}
		return false;
	}
	return false;
}

function monotone_the_image( $return = null ) {
	global $post;
	if ( get_post_status($post->ID) == 'private' ) {
		if ( ! is_page() && ! current_user_can( 'read_private_posts' ) ) {
			return false;
		} elseif ( is_page() && ! current_user_can( 'read_private_pages' ) ) {
			return false;
		}
	}

	if ( post_password_required() )
		return false;

	$tag = get_post_meta( $post->ID, 'image_tag', true );
	if ( ! $tag ) {
		monotone_image_setup( $post->ID );
		$tag = get_post_meta( $post->ID, 'image_tag', true );
	}
	$tag = preg_replace( '/width=(\"|\')[0-9]+(\1)/','', $tag );
	$tag = preg_replace( '/height=(\"|\')[0-9]+(\1)/','', $tag );
	if ( $return ) return $tag; /*else*/ echo $tag;

}

function monotone_the_image_url( $return = null ) {
	global $post;
	$tag = get_post_meta( $post->ID, 'image_url', true );
	if ( ! $tag ) {
		monotone_image_setup( $post->ID );
		$tag = get_post_meta( $post->ID, 'image_url', true );
	}
	if ( $return ) return $tag; /*else*/ echo $tag;
}

function monotone_the_thumbnail() {
	global $post;
	$src = preg_replace( '/\?w\=[0-9]+/','?w=125', monotone_the_image( true ) );
	if ( ! $src ) $src = '<img src="' . get_stylesheet_directory_uri(). '/images/placeholder.png" alt="Placeholder" />';
	$src = '<div class="image thumbnail">'.$src.'</div>';
	echo $src;
}

function monotone_get_all_colors( $post ) {
	//pull from DB
	$base->bg = get_post_meta( $post->ID, 'image_colors_bg',true );
	$base->fg = get_post_meta( $post->ID, 'image_colors_fg',true );

	// show return variable if full
	if ( $base->bg != '' && $base->fg != '' ) {
		return $base;
	} else {
	// else, get the colors
		include_once( 'csscolor.php' );
		$base = new CSS_Color( monotone_base_color( $post ) );
		//set bg
		$bg = $base->bg;
		//set fg
		$fg = $base->fg;
		if ( add_post_meta( $post->ID, 'image_colors_bg', $bg, false )
		&&  add_post_meta( $post->ID, 'image_colors_fg', $fg, false ) ) return $base;
	}
}

function monotone_print_stylesheet() {
	global $post;
	$color = monotone_get_all_colors( $post );

	// hack for array keys like -1 being stored in post_meta as 4294967295
	foreach ( $color->bg as $key => $value ) {
		if ( is_int( $key ) && $key > 0 ) $key = -( 4294967296 - $key );
		$new_color->bg[$key] = $value;
	}

	foreach ( $color->fg as $key => $value ) {
		if ( is_int( $key ) && $key > 0 ) $key = -( 4294967296 - $key );
		$new_color->fg[$key] = $value;
	}
	$color = $new_color;
	// end hack

	?>
	#page {
	  	background-color:#<?php echo $color->bg['-1']; ?>;
		color:#<?php echo $color->fg['-2']; ?>;
	}
	a, a:link, a:visited {
		color: #<?php echo $color->fg['-3']; ?>;
	}
	a:hover, a:active {
		color: #<?php echo $color->bg['+2']; ?>;
	}
	#header h1, #header h1 a, #header h1 a:link, #header h1 a:visited, #header h1 a:active {
		color: #<?php echo $color->fg['0']; ?>;
	}
	#header h1 a:hover {
		color: #<?php echo $color->bg['+2']; ?>;
	}
	.navigation a, .navigation a:link,
	.navigation a:visited, .navigation a:active {
		color: #<?php echo $color->fg['0']; ?>;
	}
	h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover h6 a:hover, .navigation a:hover {
		color: #<?php echo $color->fg['-2']; ?>;
	}
	.description,
	h3#respond,
	#comments,
	#content h1, #content #post h1, #content .content h1, h1 a, h1 a:link, h1 a:visited, h1 a:active,
	#content h2, #content #post h2, #content .content h2, h2 a, h2 a:link, h2 a:visited, h2 a:active,
	#content h3, #content #post h3, #content .content h3, h3 a, h3 a:link, h3 a:visited, h3 a:active,
	#content h4, #content #post h4, #content .content h4, h4 a, h4 a:link, h4 a:visited, h4 a:active,
	#content h5, #content #post h5, #content .content h5, h5 a, h5 a:link, h5 a:visited, h5 a:active,
	#content h6, #content #post h6, #content .content h6, h6 a, h6 a:link, h6 a:visited, h6 a:active { /* Use the corresponding foreground color */
		color: #<?php echo $color->fg['-1']; ?>;
	}
	#postmetadata, #commentform p, .commentlist li, #post, #postmetadata .sleeve, #post .sleeve, #content, h3#respond, h3#comments, #reply-title {
		color: #<?php echo $color->fg['-2']; ?>;
		border-color: #<?php echo $color->fg['-2']; ?>;
	}<?php
}

function monotone_base_color( $post ) {

	$url = get_post_meta( $post->ID, 'image_url', true );
	if ( ! $url ) {
		monotone_image_setup( $post->ID );
		$url = get_post_meta( $post->ID, 'image_url', true );
	}

	$ext = strtolower( pathinfo( trim( $url ), PATHINFO_EXTENSION ) );
	$ext = explode( '?', $ext );
	$ext = $ext[0];

	switch( $ext ) {
		case 'gif' : $im = imagecreatefromgif( $url );  break;
		case 'png' : $im = imagecreatefrompng( $url );  break;
		case 'jpg' : $im = imagecreatefromjpeg( $url ); break;
		case 'jpeg' : $im = imagecreatefromjpeg( $url ); break;
		default: return 'ffffff';
	}

	$height = imagesy( $im );
	$top = $height - 400;
	$width = imagesx( $im );

	// sample five points in the image, based on rule of thirds and center
	$rgb = array();

	$topy = round( $height / 3 );
	$bottomy = round( ( $height / 3 ) * 2 );
	$leftx = round( $width / 3 );
	$rightx = round( ( $width / 3 ) * 2 );
	$centery = round( $height / 2 );
	$centerx = round( $width / 2 );

	$rgb[1] = imagecolorat( $im, $leftx, $topy );
	$rgb[2] = imagecolorat( $im, $rightx, $topy );
	$rgb[3] = imagecolorat( $im,  $leftx, $bottomy );
	$rgb[4] = imagecolorat( $im,  $rightx, $bottomy );
	$rgb[5] = imagecolorat( $im, $centerx, $centery );

	// extract each value for r, g, b
	$r = array();
	$g = array();
	$b = array();
	$hex = array();

	$ct = 0; $val = 50;

	// process points
	for ( $i = 1; $i <= 5; $i++ ) {
		$r[$i] = ( $rgb[$i] >> 16 ) & 0xFF;
		$g[$i] = ( $rgb[$i] >> 8 ) & 0xFF;
		$b[$i] = $rgb[$i] & 0xFF;

		// find darkest color
		$tmp = $r[$i] + $g[$i] + $b[$i];

		if ( $tmp < $val ) {
			$val = $tmp;
			$ct = $i;
		}
		$hex[$i] = monotone_rgbhex( $r[$i],$g[$i],$b[$i] );
	}
	return $hex[3];
}

function monotone_rgbhex( $red, $green, $blue ) {
	return sprintf( '%02X%02X%02X', $red, $green, $blue );
}

// force 24 posts per page for archives
function monotone_posts_per_page() {
	return 24;
}
add_filter( 'pre_option_posts_per_page', 'monotone_posts_per_page' );

function monotone_page_menu() {
	global $wpdb; // fallback for primary navigation ?>
	<ul id="menu">
		<li><a href="<?php 	$post_datetimes = $wpdb->get_results( "SELECT YEAR ( max( post_date ) ) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970 AND post_type = 'post' AND post_status = 'publish'");
			echo get_year_link( $post_datetimes[0]->lastyear ); ?>"><?php _e( 'Archive', 'monotone' ); ?></a></li>
		<li><a href="<?php echo home_url( '/' ); ?>about/"><?php _e( 'About', 'monotone' ); ?></a></li>
	</ul>
<?php }

function monotone_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );
?>
<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
		<div class="gravatar"><?php echo get_avatar( $comment, 32 ); ?></div>
		<div class="comment-meta commentmetadata metadata">
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="">
				<?php printf( __( '%1$s at %2$s', 'monotone' ),
						get_comment_date( 'j M Y' ),
						get_comment_time()
				); ?>
			</a>
			<cite class="fn"><?php comment_author_link(); ?></cite>
			<?php edit_comment_link( __( 'edit', 'monotone' ), '<br />', '' ); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'reply', 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div>
		</div>
		<div class="content">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p><em><?php _e( 'Your comment is awaiting moderation.', 'monotone' ); ?></em></p>
			<?php endif; ?>
			<?php comment_text(); ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
}