<?php
/**
 * @package Vertigo
 */

/**
 * Set theme colors for WordPress.com.
 */
$themecolors = array(
	'bg' => '000000',
	'border' => '444444',
	'text' => 'bbbbbb',
	'link' => 'ee3322',
	'url' => '777777',
);

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 430;

// Load theme options
require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

/**
 * Get theme options
 * If empty, use the default values
 * @return array
 **/
function vertigo_get_theme_options() {
	$default_options = vertigo_get_default_options();
	return get_option( 'vertigo_theme_options', $default_options );
}

// Tell WordPress to run vertigo_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'vertigo_setup' );

if ( ! function_exists( 'vertigo_setup' ) ):

function vertigo_setup() {

	$vertigo_options = vertigo_get_theme_options();
	$accent_color = $vertigo_options['accent_color'];
	
	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'vertigo', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'vertigo' ) );

	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// This theme allows users to upload a custom header.
	define( 'HEADER_TEXTCOLOR', $accent_color );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 960 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 240 );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See vertigo_admin_header_style(), below.
	add_custom_image_header( 'vertigo_header_style', 'vertigo_admin_header_style' );
}
endif;

// Add custom header support
if ( ! function_exists( 'vertigo_header_style' ) ) :

// Styles the header image and text displayed on the blog
function vertigo_header_style() {
	$header_image = get_header_image();
	
	// If no custom options for text are set, let's bail
	if ( HEADER_TEXTCOLOR == get_header_textcolor() && empty( $header_image ) )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Do we have a custom header image?
		if ( ! empty( $header_image ) ) :
	?>
		#branding {
			background: url(<?php header_image(); ?>);
			height: 130px; /* 240 - 110 for top padding */
			width: 960px;
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
 	 		position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}

	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>

	</style>
	<?php
}
endif;

if ( ! function_exists( 'vertigo_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in vertigo_setup().
 *
 */
function vertigo_admin_header_style() {
	$vertigo_options = vertigo_get_theme_options();
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background-color: #000;
		<?php if ( '' == get_header_image() )
			echo 'background-image: url( ' . get_template_directory_uri() . '/images/header-bg.png ) !important;';
		?>
		background-position: 50% 0;
		background-repeat: no-repeat;
		border: none;
		height: 130px!important;
		padding: 110px 0 0;
		width: 940px;
	}
	#headimg h1 {
		font-size: 36px;
		font-weight: normal;
		line-height: 54px!important;
		margin: 0;
		text-align: center;
	}
	#headimg h1 a {
		color: #<?php echo $vertigo_options['accent_color']; ?>;
		line-height: 1.5em!important;
		margin: 0;
		padding: 0;
		text-decoration: none;
	}
	#headimg h1 a:hover {
		color: #<?php echo $vertigo_options['accent_color']; ?>;
		text-decoration: underline;
	}
	#desc {
		color: #fff!important;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 13px;
		margin: 0 auto;
		padding: 15px 0 0px 120px;
		text-align: center;
		width: 500px;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( HEADER_TEXTCOLOR != get_header_textcolor() ) :
	?>
		#site-title a {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
<?php
}
endif;

// Add Vertigo colors
function vertigo_colors() {
?>
<style type="text/css">
	/* <![CDATA[ */
	/* Accent Color */
	<?php $vertigocolors = dirname( __FILE__ ) . '/inc/style.colors.php';
	if ( is_file( $vertigocolors ) )
		require( $vertigocolors );
	?>
	/* ]]> */
</style>
<?php }
add_action( 'wp_head', 'vertigo_colors' );

// Remove inline styles printed when the gallery shortcode is used.
add_filter( 'use_default_gallery_style', '__return_false' );

if ( ! function_exists( 'vertigo_comment' ) ) :
// Template for comments and pingbacks.
function vertigo_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 24 ); ?>
			<?php printf( __( '%s &sdot;', 'vertigo' ) . ' ', sprintf( '%s', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'vertigo' ); ?></em><br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata">
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php /* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'vertigo' ), get_comment_date(), get_comment_time() ); ?>
			</a><?php edit_comment_link( __( '&sdot; Edit', 'vertigo' ), ' ' ); ?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-## -->

	<?php
			break;
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="pingback">
		<p><?php _e( 'Pingback:', 'vertigo' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '&sdot; EDIT', 'vertigo' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif; // ends check for vertigo_comment()


// Enqueue scripts
function vertigo_scripts() {
	$vertigo_options = vertigo_get_theme_options();
	if ( 'true' == $vertigo_options['vertigo_font'] ) {
		wp_enqueue_script( 'cufon', get_template_directory_uri() . '/js/cufon-yui.js', 'jquery' );
		wp_enqueue_script( 'hitchcock', get_template_directory_uri() . '/js/hitchcock_500.font.js' );
		wp_enqueue_script( 'cufon_activate', get_template_directory_uri() . '/js/cufon-activate.js' );
		wp_enqueue_script( 'audio-player', get_template_directory_uri() . '/js/audio-player.js' );
	}
}
add_action( 'wp_enqueue_scripts', 'vertigo_scripts' );


// Grab the first URL from a Link post 
function vertigo_url_grabber() {
	global $post;
	$first_url = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all( '/<a.+href=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	$first_url = $matches[1][0];
	if ( empty( $first_url ) )
		return false;
	return $first_url;
}

// Grab the first image from an Image post
function vertigo_image_grabber() {
	$content = get_the_content();
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );
	$first_img = $matches[0][0];
	if ( empty( $first_img ) )
		return false;
	return $first_img;
}

// Remove the first image the_content for display
function vertigo_content_wo_first_image() {
	 ob_start();
	 the_content();
	 $content_wo_first_image = preg_replace( '/<img[^>]+./','', ob_get_contents(), 1 );
	 ob_end_clean();
	 return $content_wo_first_image;
}

// Grab the URL the first audio attachment in a post
function vertigo_get_audio_file() {
	global $wpdb, $post;
	$audio = '';
	$query = $wpdb->prepare( "SELECT guid FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'attachment' AND post_mime_type = 'audio/mpeg' ORDER BY menu_order ASC LIMIT 1", $post->ID );
	$first_audio = $wpdb->get_results( $query );
	if ( $first_audio )
		return $first_audio[0]->guid;
	return false;
}

// Add scripts for setting up the Audio Player
function vertigo_audio_player_setup () {
	$vertigo_options = vertigo_get_theme_options();
	$accent_color = $vertigo_options['accent_color'];
	echo "\n" . '<script type="text/javascript">
	//<![CDATA[
	AudioPlayer.setup( "' . get_template_directory_uri() . '/swf/player.swf", {
		transparentpagebg: "yes",
		bg: "000000",
		leftbg: "000000",
		rightbg: "000000",
		track: "000000",
		text: "ffffff",
		lefticon: "ffffff",
		righticon: "' . $accent_color . '",
		righticonhover: "ffffff",
		border: "000000",
		tracker: "' . $accent_color . '",
		rightbghover: "000000",
		animation: "no",
		width: "300",
		loader: "666666"
	});
	//]]>
	</script>' . "\n";
}
add_action( 'wp_head', 'vertigo_audio_player_setup', 10 );

// Replaces "[...]" (appended to automatically generated excerpts) with an "Read more.".
function vertigo_auto_excerpt_more( $more ) {
	return '... <a href="'. get_permalink() . '">' . __( 'Read more.', 'vertigo' ) . '</a>';
}
add_filter( 'excerpt_more', 'vertigo_auto_excerpt_more' );

// Show post-meta for use in loop
function vertigo_entry_meta() {
?>
		<div class="entry-meta">
			<?php edit_post_link( __( '(Edit)', 'vertigo' ), '<span class="edit-link">', '</span><br /><br />' ); ?>
			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'vertigo' ), __( '<b>1</b> Comment', 'vertigo' ), __( '<b>%</b> Comments', 'vertigo' ) ); ?></span>
			<?php endif; ?>
		</div><!-- .entry-meta -->
<?php
}

// Show post-info for use in loop
function vertigo_entry_info() {
?>
		<footer class="entry-info">
			<p class="permalink"><a href="<?php the_permalink(); ?>">*</a></p>
			<div class="data">
				<?php
					printf( __( '<span class="posted">posted on <a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a><br /></span>by <a href="%4$s" title="%5$s">%6$s</a><br />', 'vertigo' ),
						get_permalink(),
						get_the_date( 'c' ),
						get_the_time( get_option( 'date_format' ) ),
						get_author_posts_url( get_the_author_meta( 'ID' ) ),
						esc_attr( sprintf( __( 'View all posts by %s', 'vertigo' ), get_the_author() ) ),
						get_the_author()
					);
				?>
				<?php printf( __( 'filed under %s', 'vertigo' ), get_the_category_list( ', ' ) ); ?><br />
				<?php the_tags( __( 'tagged as', 'vertigo' ) . ' ', ', ', '' ); ?>
			</div><!-- .data -->
		</footer><!-- #entry-info -->
<?php
}

/**
 * Register our sidebars and widgetized areas.
 */
function vertigo_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'vertigo' ),
		'id' => 'sidebar-1',
		'description' => __( 'An optional widget area for your site footer', 'vertigo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'vertigo' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'vertigo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

}
add_action( 'widgets_init', 'vertigo_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function vertigo_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-1' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Check number of pages available to display navigation
 */
function vertigo_pagination() {
	global $wp_query, $paged;

	$max_page = $wp_query->max_num_pages;
	if ( 1 == $max_page )
		return; // don't show when only one page

	if ( empty( $paged ) )
		$paged = 1;

	echo '<p class="pages">' . sprintf( __( 'Page %1$s of %2$s', 'vertigo' ), $paged, $max_page ) . '</p> ';
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */