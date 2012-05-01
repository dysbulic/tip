<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */

// Load scripts.
function skeptical_scripts() {
	if ( ! is_singular() || ( is_singular() && 'audio' == get_post_format() ) )
		wp_enqueue_script( 'audio-player', get_template_directory_uri() . '/js/audio-player.js', array( 'jquery' ), '20110801' );
}
add_action( 'wp_enqueue_scripts', 'skeptical_scripts' );

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 519;

// WP.com: Check the current color scheme and set the correct themecolors array
if ( ! isset( $themecolors ) ) {
	$options = get_option( 'skeptical_theme_options' );
	$color_scheme = $options['color_scheme'];
	switch ( $color_scheme ) {
		case 'red':
			$themecolors = array(
				'bg' => 'a8a8a8',
				'border' => '191819',
				'text' => '555555',
				'link' => 'c52020',
				'url' => 'c52020',
			);
			break;	
		case 'green':
			$themecolors = array(
				'bg' => 'd9e3d7',
				'border' => '979e96',
				'text' => '555555',
				'link' => '0C3204',
				'url' => '0C3204',
			);
			break;	
		case 'blue':
			$themecolors = array(
				'bg' => '9c9fa5',
				'border' => '191819',
				'text' => '555555',
				'link' => '2d4567',
				'url' => '2d4567',
			);
			break;	
		default:
			$themecolors = array(
				'bg' => 'efefef',
				'border' => 'a7a7a7',
				'text' => '555555',
				'link' => '000000',
				'url' => '000000',
			);
			break;
	}
}

function skeptical_setup() {
	// Enable custom background
	add_custom_background();

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'skeptical', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	//Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'video', 'quote', 'chat', 'audio' ) );

	// Enable the primary navigation menu
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'woothemes' ),
	) );

	// Enable Post Thumbnail and add two custom sizes
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'recent-image-thumb', 75, 75, true );
	add_image_size( 'skeptical-featured-image', 519, 519, false );

	// The default header text color
	define( 'HEADER_TEXTCOLOR', 'ffffff' );
	define( 'NO_HEADER_TEXT', true );

	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to skeptical_header_image_width and skeptical_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'skeptical_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'skeptical_header_image_height', 200 ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See skeptical_admin_header_style(), below.
	add_custom_image_header( '', 'skeptical_admin_header_style' );
}
add_action( 'after_setup_theme', 'skeptical_setup' );

// Styles the header image displayed on the Appearance > Header admin panel.
function skeptical_admin_header_style() { ?>
	<style type="text/css">
		#headimg {
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
		#heading {
			display: none;
		}
	</style>
<?php
}

// Fallback for the nav menu
function skeptical_page_menu() { ?>
	<ul id="main-nav" class="nav fl">
		<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
	</ul>
<?php
}

// Register widgetized areas
function skeptical_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'woothemes' ),
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'woothemes' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'woothemes' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'woothemes' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'woothemes' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

add_action( 'widgets_init', 'skeptical_widgets_init' );

// Sniff out the number of categories in use and return the number of categories.
function skeptical_category_counter() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	return $all_the_cool_cats;
}

// Post Meta in loop
function skeptical_post_meta() { ?>
	<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="date-link">
		<span class="post-date">
			<?php
				if ( is_sticky() ) :
					_e( 'Featured', 'woothemes' );
				else :
					the_time( get_option( 'date_format' ) );
				endif;
			?>
			<span class="bg">&nbsp;</span>
		</span>
	</a>
	<ul>
		<?php if ( is_multi_author() ) : ?>
			<li class="post-author"><?php printf( __( 'by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a>', 'woothemes' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'woothemes' ), get_the_author() ) ),
				esc_html( get_the_author() )
			); ?></li>
		<?php endif; ?>

		<?php if ( 1 != skeptical_category_counter() ) : ?>
			<li class="post-category"><?php printf( __( 'in %s', 'woothemes' ), get_the_category_list( __( ', ', 'woothemes' ) ) ); ?></li>
		<?php endif; ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<li class="comments"><?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></li>
		<?php endif; ?>

		<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<li>', '</li>' ); ?>
	</ul>
<?php
}

// Page navigation
function skeptical_pagenav() {
 	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<div class="nav-entries">
			<div class="nav-prev fl"><?php next_posts_link( __( '&laquo; Older Posts', 'woothemes' ) ); ?></div>
			<div class="nav-next fr"><?php previous_posts_link( __( 'Newer Posts &raquo;', 'woothemes' ) ); ?></div>
			<div class="fix"></div>
		</div>
	<?php endif;
}

// Load up the theme options
require( dirname( __FILE__ ) . '/inc/theme-options.php' );

// Get skeptical options
function skeptical_get_options() {
	$defaults = array(
		'color_scheme' => 'gray',
		'theme_rss' => 'yes'
	);
	$options = get_option( 'skeptical_theme_options', $defaults );
	return $options;
}

// Register our color schemes and add them to the style queue
function skeptical_color_registrar() {
	$options = skeptical_get_options();
	$color_scheme = $options['color_scheme'];

	if ( ! empty( $color_scheme ) ) {
		wp_register_style( $color_scheme, get_template_directory_uri() . '/styles/' . $color_scheme . '.css', null, null );
		wp_enqueue_style( $color_scheme );
	}
}
add_action( 'wp_enqueue_scripts', 'skeptical_color_registrar' );

// Show author info.
function skeptical_author_info() { ?>
	<div id="author-info" class="clearfix">
		<div id="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'skeptical_author_bio_avatar_size', 65 ) ); ?>
		</div><!-- #author-avatar -->
		<div id="author-description">
			<h4><?php esc_html( printf( __( 'About %s', 'woothemes' ), get_the_author() ) ); ?></h4>
			<?php the_author_meta( 'description' ); ?>
			<div id="author-link">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'woothemes' ), get_the_author() ); ?>
				</a>
			</div><!-- #author-link	-->
		</div><!-- #author-description -->
		<div class="fix"></div>
	</div><!-- #entry-author-info -->
<?php
}

// Add in-head JS block for audio post format.
function skeptical_add_audio_support() {
	if ( ! is_singular() || ( is_singular() && 'audio' == get_post_format() ) ) {
?>
		<script type="text/javascript">
			AudioPlayer.setup( "<?php echo get_template_directory_uri(); ?>/swf/player.swf", {
				bg: "111111",
				leftbg: "111111",
				rightbg: "111111",
				track: "222222",
				text: "ffffff",
				lefticon: "eeeeee",
				righticon: "eeeeee",
				border: "222222",
				tracker: "eb374b",
				loader: "666666"
			});
		</script>
<?php }
}
add_action( 'wp_head', 'skeptical_add_audio_support' );

// Comment Output
function skeptical_comment( $comment, $args, $depth ) {
	 $GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<span class="author"><?php comment_author_link(); ?></span> -
		<span class="date"><?php echo get_comment_date( get_option( 'date_format' ) ); ?></span>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<a name="comment-<?php comment_ID(); ?>"></a>
		<div class="comment-container">

		<?php if ( get_comment_type() == "comment" ){ ?>
			<?php $avatar_size = 80; ?>
			<div class="avatar"><?php echo get_avatar( $comment, $avatar_size ); ?></div>
		<?php } ?>

			<div id="comment-<?php comment_ID(); ?>" class="comment-content">
				<span class="name"><?php printf( __( '%s <span class="says">says :</span>', 'woothemes' ), get_comment_author_link() ); ?></span>
				<span class="date">
				<?php
					printf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						sprintf(__( '%1$s at %2$s', 'woothemes' ), get_comment_date(),  get_comment_time() )
					);
				?>
				</span>
				<span class="edit"><?php edit_comment_link( __( 'Edit', 'woothemes' ), '', '' ); ?></span>

				<?php comment_text(); ?>

				<?php if ( $comment->comment_approved == '0' ) { ?>
					<p class='unapproved'><?php _e( 'Your comment is awaiting moderation.', 'woothemes' ); ?></p>
				<?php } ?>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- /.reply -->
			</div><!-- /comment-content -->
			<div class="fix"></div>
		</div><!-- /.comment-container -->
		<div class="fix"></div>
	<?php
			break;
	endswitch;
}