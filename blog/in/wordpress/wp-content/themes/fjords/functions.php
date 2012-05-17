<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */

if ( function_exists( 'register_sidebars' ) )
    register_sidebars( 3 );

function resize_youtube( $content ) {
	return str_replace( "width='425' height='350'></embed>", "width='240' height='197'></embed>", $content );
}
add_filter( 'the_content', 'resize_youtube', 999 );

load_theme_textdomain( 'fjords', TEMPLATEPATH . '/languages' );

add_theme_support( 'automatic-feed-links' );

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '888888',
	'link' => '8ab459',
	'border' => 'dee4da',
	'url' => '63b4cd',
);

$content_width = 270;

add_filter( 'body_class', '__return_empty_array', 1 );

define( 'HEADER_TEXTCOLOR', 'ffffff' );
define( 'HEADER_IMAGE', '%s/imagenes_qwilm/beach.jpg' ); // %s is theme dir uri
define( 'HEADER_IMAGE_WIDTH', 900 );
define( 'HEADER_IMAGE_HEIGHT', 200 );

function header_style() {
?>
<style type="text/css">
#content, #sidebar-1, #sidebar-2, #sidebar-3 {
	background-image:url(<?php header_image(); ?>);
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#hode h4, #hode span {
	display: none;
}
<?php } else { ?>
#hode a, #hode {
	color: #<?php header_textcolor(); ?>;
}	
<?php } ?>
</style>
<?php
}

function admin_header_style() {
?>

<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1 {
font-family: "Lucida Grande",Tahoma,Arial,sans-serif;
font-size: 17px;
font-weight: bold;
margin-left: 15px;
padding-top: 15px;
}
#headimg h1 a {
	color:#<?php header_textcolor(); ?>;
	border: none;
	text-decoration: none;
}
#headimg a:hover
{
	text-decoration:underline;
}
#headimg #desc
{
	font-weight:normal;
	color:#<?php header_textcolor(); ?>;
	margin-left: 15px;
	padding: 0;
	margin-top: -10px;
font-family: "Lucida Grande",Tahoma,Arial,sans-serif;
	font-size: 11px;
}

<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headerimg h1, #headerimg #desc {
	display: none;
}
#headimg h1 a, #headimg #desc {
	color:#<?php echo HEADER_TEXTCOLOR ?>;
}
<?php } ?>


</style>

<?php }

add_custom_image_header( 'header_style', 'admin_header_style' );

add_custom_background();

function fjords_comment( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment;
	extract( $args, EXTR_SKIP );
?>
<div <?php comment_class( empty( $args[ 'has_children' ] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comentarios">
		<span class="comment-author vcard"><?php if ( $args[ 'avatar_size' ] != 0 ) echo get_avatar( $comment, $args[ 'avatar_size' ] ); ?>&nbsp;
		<span class="fn"><a href="<?php comment_author_url(); ?>">
		<?php printf ( __( '%1$s wrote @ %2$s at %3$s' ), comment_author() . '</a></span>' , '<span class="comment-meta commentmetadata">' . get_comment_date(), get_comment_time().'</span>' ) ?>
		</span>
	</div>
	<?php comment_text(); ?>
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ); ?>
	</div>
	</div>
<?php
}