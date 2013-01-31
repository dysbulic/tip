<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

/**
 * Enqueue the Spectrum scripts.
 */
function spectrum_script_init() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'spectrum-min', get_bloginfo( 'template_directory' ) . '/js/spectrum-min.js', array( 'jquery' ) );
}
add_action( 'init', 'spectrum_script_init' );

/**
 * Get the Spectrum theme options
 */
require_once( get_template_directory() . '/theme-options.php' );

/**
 * Theme colors for WP.com
 */
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'dddddd',
	'text' => '444444',
	'link' => 'ac6c13',
	'url' => '6ab32e',
);

/**
 * Set content_width
 */
$content_width = 540;

/**
 * Register widget areas.
 */
function spectrum_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'spectrum' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'spectrum' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-box">',
		'after_widget' => '</div>',
		'before_title' => '<div class="sidebar-title"><h4>',
		'after_title' => '</h4></div>'
	));
}

add_action( 'widgets_init', 'spectrum_widgets_init' );

add_theme_support( 'automatic-feed-links' );

add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'quote', 'status' ) );

/**
 * Add Custom Menu support
 */
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

function spectrum_page_menu() { // fallback for primary navigation ?>
	<ul>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>

<?php }

/**
 * Add Cusotm Backgorund support
 */
add_custom_background();

// Allow custom colors to clear the background image
function spectrum_custom_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>
	<?php }
}
add_action( 'wp_head', 'spectrum_custom_background_color' );

/**
 * Add Custom Header Image support
 */
define( 'HEADER_IMAGE', '' );
define( 'HEADER_IMAGE_WIDTH', 938 );
define( 'HEADER_IMAGE_HEIGHT', 150 );
define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

function admin_header_style() {
    ?><style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
    </style><?php
}

add_custom_image_header( '', 'admin_header_style' );

/**
 * Tag Cloud
 */
function spectrum_tag_cloud( $tags ) {
	$tags = preg_replace_callback( "|(class='tag-link-[0-9]+)('.*?)(style='font-size: )([0-9]+)(px;')|",
		create_function(
			'$match',
			'$low=1; $high=10; $sz=($match[4])/(2); return "{$match[1]} tagSize-{$sz}{$match[2]}";'
		),
		$tags );
	return $tags;
}

add_action( 'wp_tag_cloud', 'spectrum_tag_cloud' );

/**
 *  Returns the current Spectrum theme options, with default values as fallback.
 */
function spectrum_get_theme_options() {
	$defaults = array(
		'ribbon_color' => 'green',
	);
	$options = get_option( 'spectrum_theme_options', $defaults );

	return $options;
}

/**
 *  Returns the current Spectrum color scheme as selected in the theme options.
 */
function spectrum_current_ribbon_color() {
	$options = spectrum_get_theme_options();
	return $options['ribbon_color'];
}

/**
 * Register our color scheme and add them to the queue.
 */
function spectrum_color_registrar() {
	$ribbon_color = spectrum_current_ribbon_color();

	if ( 'green' == $ribbon_color )
		return;

	wp_enqueue_style( $ribbon_color, get_template_directory_uri() . '/colors/' . $ribbon_color . '.css', null, null );
}
add_action( 'wp_enqueue_scripts', 'spectrum_color_registrar' );

/**
 * Date formats for Spectrum's title banner.
 */
function spectrum_date() {
	$date_format = get_option( 'date_format' );
	if ( 'd/m/Y' == $date_format ) : ?>
		<span class="month"><?php the_time( 'd' ); ?></span>
		<span class="day"><?php the_time( 'm' ); ?></span>
		<span class="year"><?php the_time( 'y' ); ?></span>
	<?php elseif ( 'm/d/Y' == $date_format ) : ?>
		<span class="month"><?php the_time( 'm' ); ?></span>
		<span class="day"><?php the_time( 'd' ); ?></span>
		<span class="year"><?php the_time( 'y' ); ?></span>
	<?php elseif ( 'Y/m/d' == $date_format ) : ?>
		<span class="year"><?php the_time( 'y' ); ?></span>
		<span class="month"><?php the_time( 'm' ); ?></span>
		<span class="day"><?php the_time( 'd' ); ?></span>
	<?php else: // all other date formats get one big span ?>
		<span><?php the_date(); ?></span>
	<?php endif;
}

function spectrum_comments( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment; ?>

	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
		<div class="avatar-holder">
			<?php if ( $args[ 'avatar_size' ] != 0 ) echo get_avatar( $comment, $args[ 'avatar_size' ] ); ?>
		</div>
		<div <?php comment_class(); ?> id="div-comment-<?php comment_ID(); ?>">
			<div class="comment-author-and-date">
				<div class="comment-author">
					<?php printf( __( '<strong>%s</strong> <em>said:</em>' ), get_comment_author_link() ); ?>
				</div>
				<div class="commentDate">
					<a href="<?php comments_link(); ?> "><?php printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() ); ?></a>
				</div>
			</div>
			<div class="commentText">
				<?php comment_text(); ?>
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="waiting4Mod"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
				<?php endif; ?>
				<p class="edit-comment"><?php edit_comment_link( __( '(Edit)' ),'','' ); ?></p>
				<p class="reply-link"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'reply_text' => '', 'max_depth' => $args[ 'max_depth' ] ) ) ); ?></p>
			</div>
		</div>

<?php } ?>