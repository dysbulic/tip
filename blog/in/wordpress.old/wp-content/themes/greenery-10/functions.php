<?php
/**
 * @package WordPress
 * @subpackage Greenery
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => 'a6b79b',
	'link' => '95c725',
	'border' => '89bcc2',
	'url' => 'cbad57',
);

$content_width = 470;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

define('HEADER_TEXTCOLOR', '80904F');
define('HEADER_IMAGE', '%s/images/header.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 740);
define('HEADER_IMAGE_HEIGHT', 171);

function header_style() {
?>
<style type="text/css">
#header {
	background-image: url(<?php header_image() ?>);
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header h1 {
	display: none;
}
<?php } else { ?>
#header h1, #header h1 a, #header a:hover {
	color: #<?php header_textcolor() ?>;
}

<?php } ?>
</style>
<?php
}

function greenery_10_admin_header_style() {
?>
<style type="text/css">

#headimg {
	background: url(<?php header_image() ?>);
 	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
 	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	margin: 0 0 10px 0;
}

#headimg h1 {
	padding: 40px 0 0 20px;
	font-size: 22px;
	color: #<?php header_textcolor() ?>;
	font-family: "Trebuchet MS",Tahoma,Arial,Sans-Serif;
	text-align: left;
}

#headimg h1 a {
	color: #<?php header_textcolor() ?>;
	border-bottom: none;
}

#desc { display: none; }

<?php if ( 'blank' == get_header_textcolor() ) { ?>
#headimg h1, #headimg #desc {
	display: none;
}
#headimg h1 a, #headimg #desc {
	color:#<?php echo HEADER_TEXTCOLOR ?>;
}
<?php } ?>

</style>
<?php
}

add_custom_image_header('header_style', 'greenery_10_admin_header_style');

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'greenery' )
) );

// Fallback for primary navigation
function greenery_page_menu() { ?>
	<ul class="menu">
		<li class="<?php if ( is_home() or is_archive() or is_single() or is_paged() or is_search() or ( function_exists( 'is_tag' ) and is_tag()) ) { ?>current_page_item<?php } else { ?>page_item<?php } ?>">
			<a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home' ); ?></a>
		</li>
		<?php wp_list_pages( 'sort_column=id&depth=1&title_li=' ); ?>
	</ul>
<?php }

function greenery_10_comment($comment, $args, $depth) {
	global $relax_comment_count;
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? 'item' : 'item parent') ?> id="comment-<?php comment_ID() ?>" >
		<div id="div-comment-<?php comment_ID() ?>">
		<div class="commentcounter"><?php echo $relax_comment_count; ?></div>
		
			<div class="comment-author vcard">
				<div class="commentgravatar">
					<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</div>
			
				<h3 class="commenttitle"><span class="fn"><?php comment_author_link() ?></span> <?php _e('said'); ?>,</h3>
			</div>
			<p class="commentmeta comment-meta commentmetadata">
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php comment_date() ?> @ <?php comment_time() ?>
				</a>
				<?php if (function_exists('quoter_comment')) { quoter_comment(); } ?>
				<?php edit_comment_link(__("Edit"), ' &#183; ', ''); ?>
	
			</p>
			<?php comment_text() ?>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div>		
	<?php $relax_comment_count++; ?>
<?php
}