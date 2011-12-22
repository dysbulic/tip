<?php
/**
 * @package Shaan
 */

if ( ! isset( $content_width ) )
	$content_width = 600;

if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg'     => 'eff1f5',
		'text'   => '444444',
		'link'   => 'ff7200',
		'border' => 'c3c9d5',
		'url'    => '444444',
	);
}

if ( ! function_exists( 'shaan_setup' ) ) {
	/**
	 * Setup Shaan.
	 */
	function shaan_setup() {

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 100, 100, true );
		add_image_size( 'shaan_featured_image', 600, 250, true );

		define( 'HEADER_TEXTCOLOR', 'ffffff' );
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', 950 );
		define( 'HEADER_IMAGE_HEIGHT', 100 );
		add_custom_image_header( 'shaan_header_style', 'shaan_admin_header_style' );

		add_custom_background();

		register_nav_menus( array(
			'primary-menu' => __( 'Header Navigation', 'shaan' ),
			'footer-menu'  => __( 'Footer Navigation', 'shaan' ),
		) );

		add_action( 'widgets_init', 'shaan_register_sidebar' );
		add_action( 'wp_enqueue_scripts', 'shaan_enqueue_color_style' );
		add_action( 'wp_enqueue_scripts', 'shaan_comment_reply_script' );
		add_filter( 'excerpt_length', 'shaan_excerpt_length' );
		add_filter( 'excerpt_more', 'shaan_auto_excerpt_more' );
		add_action( 'comment_form_before_fields', 'shaan_comment_form_before_fields' );
		add_action( 'comment_form_after_fields', 'shaan_comment_form_after_fields' );
	}
}
add_action( 'after_setup_theme', 'shaan_setup' );

/**
 * Google Font.
 *
 * Hooks into the 'wp_enqueue_scripts' filter.
 *
 * @access private
 * @since Shaan 1.1.8-wpcom
 */
function shaan_enqueue_color_style() {
	wp_enqueue_script( 'shaan_font_molengo', 'http://fonts.googleapis.com/css?family=Molengo', array(), '20111029', 'all' );
}

/**
 * Comment Reply Script.
 *
 * Hooks into the 'wp_enqueue_scripts' filter.
 *
 * @access private
 * @since Shaan 1.1.8-wpcom
 */
function shaan_comment_reply_script() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}

if ( ! function_exists( 'shaan_header_style' ) ) {
	function shaan_header_style() {
		$image = get_header_image();
		$text_color  = get_header_textcolor();
		$description = get_bloginfo( 'description' );

		if ( empty( $image ) && ! empty( $description ) && $text_color == HEADER_TEXTCOLOR )
			return;
		?>
		<style type="text/css">

		<?php if ( empty( $description ) ) : ?>
			#site-title {
				width: 950px;
			}
			#site-description {
				display: none;
			}
		<?php endif; ?>

		<?php if ( 'blank' == $text_color ) : ?>
			#site-title,
			#site-description {
				position: absolute !important;
				clip: rect( 1px 1px 1px 1px ); /* IE6, IE7 */
				clip: rect( 1px, 1px, 1px, 1px );
			}
		<?php elseif ( HEADER_TEXTCOLOR != $text_color ) : ?>
			#site-title,
			#site-title a:link,
			#site-title a:visited,
			#site-title a:hover,
			#site-title a:active,
			#site-description {
				color: #<?php echo esc_html( get_header_textcolor() ); ?>;
			}
		<?php endif; ?>

		<?php if ( ! empty( $image ) ) : ?>
			#header {
				background: url( '<?php echo esc_url( $image ); ?>' );
				border: 5px solid #729ccb;
				overflow: hidden;
				padding: 0;
				position: relative;
				width: 950px;
				height: 100px;
			}
			#site-title {
				padding: 17px 0 0 20px;
			}
			#site-description {
				padding: 43px 20px 0 0;
			}
		<?php endif; ?>
		</style>
	<?php
	}
}

if ( ! function_exists( 'shaan_admin_header_style' ) ) {
	function shaan_admin_header_style() {
		$description = get_bloginfo( 'description' );
		$image = get_header_image();
		if ( empty( $image ) )
			$image = get_template_directory_uri() . '/images/body-bg.jpg';
		?>
		<style type="text/css">
			#headimg {
				background: url("<?php echo esc_url( $image ); ?>") !important;
				margin: 0;
				overflow: hidden;
				text-shadow: rgba( 0, 0, 0, .5 ) 1px 1px 1px;
				text-transform: uppercase;
				width: 960px !important;
				height: 100px;
			}
			#headimg h1 {
				float: left;
				font-family: Arial, Helvetica, san-serif;
				font-size: 60px;
				font-weight: normal;
				line-height: 100px;
				margin: 0;
				padding-left: 20px;
				width: <?php echo ( empty( $description ) ) ? 950 : 640; ?>px;
			}
			.rtl #headimg h1 {
				float: right;
				padding-right: 20px;
				padding-left: 0;
			}
			#headimg a {
				color: #21759b;
				text-decoration: none;
			}
			#desc {
				display: <?php echo ( empty( $description ) ) ? 'none' : 'block'; ?>;
				float: right;
				font-size: 14px;
				padding: 43px 20px 0 0;
				text-align: right;
				width: 250px;
			}
			.rtl #desc {
				float: left;
				padding-right: 0;
				padding-left: 20px;
				text-align: left;
			}

		</style>
	<?php
	}
}

/**
 * Register Sidebar.
 *
 * Hooks into the 'widgets_init' action.
 *
 * @access private
 * @since Shaan 1.1.8-wpcom
 */
function shaan_register_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'shaan' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'The sidebar widget area.', 'shaan' ),
		'before_widget' => '<div id="%1$s" class="section widget-container widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 * Length of Post Excerpts.
 *
 * Hooks into the 'excerpt_length' filter.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 *
 * @access private
 * @since Shaan 1.1.8-wpcom
 */
function shaan_excerpt_length( $length ) {
	return 55;
}

/**
 * Append Ellipsis to Auto Generated Excerpts.
 *
 * Hooks into the 'excerpt_more' filter.
 *
 * @param string $more content to appeand to auto-generated excerpts.
 * @return string A space followed by ellipsis.
 *
 * @access private
 * @since Shaan 1.1.8-wpcom
 */
function shaan_auto_excerpt_more( $more ) {
	return __( ' &hellip;', 'shann' );
}

if ( ! function_exists( 'shaan_comment' ) ) :
/**
 * Template for comments and pingbacks.
 */
function shaan_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">

		<div class="comment-meta">

			<div class="comment-avatar">
			<?php echo get_avatar( $comment, 40 ); ?>
			</div><!--.comment-avatar-->
			<div class="comment-author vcard">
				<cite class="fn"><?php comment_author_link(); ?></cite>
			</div><!-- .comment-author .vcard -->

			<div class="comment-date commentmetadata">
			<?php comment_date(); ?> &ndash; <?php comment_time(); ?> <?php edit_comment_link( __( '(Edit)', 'shaan' ), ' ' ); ?>
			</div><!-- .comment-date .commentmetadata -->

		</div><!--.comment-meta-->

		<?php if ( '0' == $comment->comment_approved ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'shaan' ); ?></em>
		<?php endif; ?>

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="pingback">
		<?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'shaan' ), ' ' ); ?>
	<?php
			break;
	endswitch;
}
endif;


/**
 * Wrap comment form fields in a custom div.
 *
 * Hooks into the "comment_form_before_fields" action.
 *
 * @since Shaan 1.1.8-wpcom
 * @access private
 */
function shaan_comment_form_before_fields() {
	echo "\n" . '<div class="comment-form-info">';
}

/**
 * Close the custom div opened by shaan_comment_form_before_fields().
 *
 * Hooks into the "comment_form_after_fields" action.
 *
 * @since Shaan 1.1.8-wpcom
 * @access private
 */
function shaan_comment_form_after_fields() {
	echo "\n" . '</div>';
}