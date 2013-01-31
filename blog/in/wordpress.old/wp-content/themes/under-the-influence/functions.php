<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

/**
 * Set the default theme colors based on the current color scheme
 */


if ( ! isset( $themecolors ) ) {

	$uti_accent_color = get_option( 'uti_accent_color' );
	if ( ! empty( $uti_accent_color ) )
		$uti_accent_color = substr( get_option( 'uti_accent_color' ), 1 );
	else
		$uti_accent_color = '9bbc57'; // default accent color

	$uti_muted_color = get_option( 'uti_muted_accent' );
	if ( ! empty( $uti_muted_color ) )
		$uti_muted_color = substr( get_option( 'uti_muted_accent' ), 1 );
	else
		$uti_muted_color = '619500'; // default muted color

	$themecolors = array(
		'bg' => 'ffffff',
		'text' => '333333',
		'link' => $uti_muted_color,
		'border' => $uti_accent_color,
		'url' => $uti_muted_color,
	);
}

function uti_content_width() {
	$width_option = get_option( 'uti_column_width' );

	if ( ! empty( $width_option ) )
		$content_width = (int) $width_option;
	else
		$content_width = 780; // default column width
}
if ( ! isset( $content_width ) )
	add_action( 'init', 'uti_content_width' );

$options = array(
	array(
		'type' => 'open'
	),

	array(
		'name' => __( 'Accent Color', 'uti_theme' ),
		'desc' => __( 'Default: 900.', 'uti_theme' ),
		'id' => 'uti_accent_color',
		'std' => '#9bbc57',
		'id2' => '1',
		'type' => 'color'
	),

	array(
		'name' => __( 'Muted Accent Color', 'uti_theme' ),
		'desc' => __( 'Affects tags, metadata for posts and comments and the read more hover color. <br>Default: 955', 'uti_theme' ),
		'id' => 'uti_muted_accent',
		'std' => '#619500',
		'id2' => '2',
		'type' => 'color'
	),

	array(
		'name' => __( 'Default Header Design', 'uti_theme' ),
		'desc' => __( 'Default:2', 'uti_theme' ),
		'id' => 'uti_header_design',
		'std' => '2',
		'type' => 'select',
		'options' => array( '2', '1' )
	),

	array(
		'name' => __( 'Site Width', 'uti_theme' ),
		'desc' => __( 'Determines the with of the entire layout in px. Alterations are not recommended for the default three column layout. For a two-column layout it might be useful to slim down the page. The optimal site width is Main Column Width + Sidebar Width + 40. <br>Default: 970', 'uti_theme' ),
		'id' => 'uti_site_width',
		'std' => '970',
		'type' => 'text'
	),

	array(
		'name' => __( 'Main Column Width', 'uti_theme' ),
		'desc' => __( 'Determines the width of the content column for single pages in px. Also affects overview pages (front, archive and search pages) when in two column layout. <br>Default: 780', 'uti_theme' ),
		'id' => 'uti_column_width',
		'std' => '780',
		'type' => 'text'
	),

	array(
		'name' => __( 'Sidebar Width', 'uti_theme' ),
		'desc' => __( 'Determines the width of the sidebar in px. Alterations are not recommended for the default three column layout. <br>Default: 150', 'uti_theme' ),
		'id' => 'uti_sidebar_width',
		'std' => '150',
		'type' => 'text'
	),

	array(
		'name' => __( 'Max Picture Width', 'uti_theme' ),
		'desc' => __( 'Determines the maximum width in px of <strong>right aligned</strong> and <strong>left aligned</strong> pictures on overview pages (front, archive and search pages). <br>Default: 376', 'uti_theme' ),
		'id' => 'uti_pic_width',
		'std' => '376',
		'type' => 'text'
	),

	array(
		'name' => __( 'Max Picture Height', 'uti_theme' ),
		'desc' => __( 'Determines the maximum height in px of <strong>right aligned</strong> and <strong>left aligned</strong> pictures on overview pages (front, archive and search pages). <br>Default: 376', 'uti_theme' ),
		'id' => 'uti_pic_height',
		'std' => '367',
		'type' => 'text'
	),

	array(
		'name' => __( 'Three Column Layout', 'uti_theme' ),
		'desc' => __( 'Disabling the three column layout changes overview pages (front, archive and search pages) to a two column layout. <br>Default: on', 'uti_theme' ),
		'id' => 'uti_column',
		'std' => 'on',
		'type' => 'select',
		'options' => array( 'on', 'off' )
	),

	array(
		'name' => __( 'Description', 'uti_theme' ),
		'desc' => __( 'This allows you to toggle the description in the title. <br>Default: on', 'uti_theme' ),
		'id' => 'uti_description',
		'std' => 'on',
		'type' => 'select',
		'options' => array( 'on', 'off' )
	),

	array(
		'name' => __( 'Right Hand Footer Cell', 'uti_theme' ),
		'desc' => __( 'You can disable the right hand footer cell to slim down the page. This is especially useful for a thinner two-column layout. <br>Default: on', 'uti_theme' ),
		'id' => 'uti_footer_cell4',
		'std' => 'on',
		'type' => 'select',
		'options' => array( 'on', 'off' )
	),

	array(
		'name' => __( 'Show Author', 'uti_theme' ),
		'desc' => __( 'This allows you to toggle the author name. <br>Default: off', 'uti_theme' ),
		'id' => 'uti_show_author',
		'std' => 'off',
		'type' => 'select',
		'options' => array( 'off', 'on' )
	),

	array(
		'type' => 'close'
	)
);

function uti_add_admin() {

	global $options;

		if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) ) {

		if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] ) {

				foreach ($options as $value) {
					if ( array_key_exists('id',$value))
						update_option( $value['id'], esc_attr($_REQUEST[ $value['id'] ]) ); }

				foreach ($options as $value) {
					if(array_key_exists('type',$value) && ($value['type'] === "checkbox" || $value['type'] === "multiselect" ) && is_array($_REQUEST[ $value['id'] ])){
						$_REQUEST[ $value['id'] ] = implode(',',esc_attr( $_REQUEST[ $value['id'] ] ));
					}
					if( isset( $value['id'] ) && isset( $_REQUEST[ $value['id'] ] ) ) {
						update_option( $value['id'], esc_attr( $_REQUEST[ $value['id'] ] ) );
					} else {
						if ( isset ( $value['id'] ) )
							delete_option( $value['id'] );
					}
				}

				header("Location: themes.php?page=functions.php&saved=true");
				die;

		} else if( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] ) {

			foreach ($options as $value) {
				if ( array_key_exists('id',$value))
					delete_option( $value['id'] );
			}

			header("Location: themes.php?page=functions.php&reset=true");
			die;

		}
	}

	add_theme_page( "Theme Options", "Theme Options", 'edit_theme_options', basename(__FILE__), 'uti_admin' );
}

add_filter('gallery_style', create_function('$a', 'return "'."<div class='gallery'>".'";'));

define('HEADER_TEXTCOLOR', 'ffffff');
define('HEADER_IMAGE', ''); // %s is the template dir uri
define('HEADER_IMAGE_WIDTH', 980); // use width and height appropriate for your theme
define('HEADER_IMAGE_HEIGHT', 180);

// gets included in the site header
function header_style() {
	?><style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
	#header {
		background: url(<?php header_image(); ?>) no-repeat center;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: 140px;
	}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
	#header {
		height: 105px;
	}
	#header h1,#header h1 a,#header h1 a:visited {
		color: #<?php header_textcolor(); ?>;
	}
	#header h1 a:hover {
		border-bottom: 2px dotted #<?php header_textcolor(); ?>;
	}
	#header .description {
		color: #<?php header_textcolor(); ?>;
	}
	<?php } else { ?>
	#header {
		padding-top: 0;
	}
	.rtl #header h1 {
		direction: ltr;
	}
	#header h1 {
		padding-top: 0;
		text-indent: -9999px;
	}
	#header .description {
		display: none;
	}
	#header h1 a {
		display: block;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		padding-top: 0 !important;
	}
	<?php }
	if ( '' != get_background_color() || '' != get_background_image() ) {
	?>
	#navigation,
	.ornament,
	#footer h2,
	#wp-calendar caption,
	.commentmetadata {
		background-image: none;
	}
	<?php } ?>
	</style><?php
}

// gets included in the admin header
function admin_header_style() {
	?><style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
	#headimg {
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
	#headimg {
		height: 85px;
		padding: 35px 0 40px 20px;
	}
	#headimg h1 {
		margin: 0;
		font: normal 54px Georgia, "Times New Roman", Times, serif;
		letter-spacing: -1px;
	}
	#headimg h1,#headimg h1 a,#headimg h1 a:visited {
		color: #<?php header_textcolor(); ?>;
		text-decoration: none;
		border-bottom: 0;
	}
	#headimg h1 a:hover {
		border-bottom: 2px dotted #<?php header_textcolor(); ?>;
	}
	#headimg #desc {
		margin: 0;
		color: #<?php header_textcolor(); ?>;
		font: italic 15px Georgia, "Times New Roman", Times, serif;
	}
	<?php } else { ?>
	#headimg h1, #header #desc {
		display: none;
	}
	<?php } ?>
	</style><?php
}

add_custom_image_header('header_style', 'admin_header_style');

add_custom_background();

function uti_custom_css() {
	include_once( dirname( __FILE__ ) . '/dynamic-css.php' );
}

function add_new_var_to_wp( $public_query_vars ) {
	$public_query_vars[] = 'css';

	return $public_query_vars;
}
add_filter( 'query_vars', 'add_new_var_to_wp' );

function dynamic_css_display() {
	$css = get_query_var( 'css' );

	if ( $css == 'css' ) {
		include_once( dirname( __FILE__ ) . '/dynamic-css.php' );
		exit;
	} elseif ( get_query_var('preview') ) {
		add_action( 'wp_head', 'uti_custom_css' );
	}
}
add_action( 'template_redirect', 'dynamic_css_display' );

function uti_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar($comment,$size='48' ); ?>
		</div><!--.comment-author vcard-->
		<div id="comment-<?php comment_ID(); ?>" class="commentbox">
			<?php if ($comment->comment_approved == '0') : ?>
				<em><?php _e('Your comment is waiting for approval.','uti_theme') ?></em>
				<br />
			<?php endif; ?>

			<div class="comment-meta commentmetadata">
				<cite class="fn">
					<?php echo get_comment_author_link() ?>
				</cite>
				<br />
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php printf(__('%1$s at %2$s', 'uti_theme'), get_comment_date(), get_comment_time()) ?>
				</a>
				<?php edit_comment_link(__('(Edit)', 'uti_theme'),'  ','') ?>
			</div><!--.comment-meta-->
			<div class="comment_body">
				<?php comment_text() ?>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div><!--.reply-->
			</div><!--.commentbody-->
		</div><!--#comment-<?php comment_ID(); ?>-->
<?php
		}
function uti_init_method() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jmasonry' );
		wp_register_script(   'jmasonry', get_bloginfo('template_directory') . '/js/jquery.masonry.js', '1.3.2');
		wp_deregister_script( 'jinit_mason' );
		wp_register_script(   'jinit_mason' ,  get_bloginfo('template_directory') . '/js/initializer.js');
	}
}
add_action('init', 'uti_init_method');

function custom_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="commentbox">

		  <?php if ($comment->comment_approved == '0') : ?>
			 <em><?php _e('Your comment is waiting for approval.', 'uti_theme') ?></em>
			 <br />
		  <?php endif; ?>

		  <div class="comment-meta commentmetadata"><cite class="fn"><?php echo get_comment_author_link() ?></cite><br /><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s', 'uti_theme'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)', 'uti_theme'),'  ','') ?></div><!--.comment-meta-->
	 </div><!--#comment-<?php comment_ID(); ?>-->
<?php
		}

function uti_admin() {

	global $options;

	if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>Under the Influence options saved.</strong></p></div>';
	if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>Under the Influence options reset.</strong></p></div>';

?>
<div class="wrap">
<h2><?php _e( 'Under the Influence Options', 'uti_theme' ); ?></h2>

<form method="post">
<?php foreach ($options as $value) {

switch ( $value['type'] ) {

case "open":
?>
<table cellspacing="0">

<tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php break;

case "close":
?>

</table><br />

<?php break;

case "title":
?>
<table cellspacing="0"><tr>
	<td colspan="2"><h3><?php echo $value['name']; ?></h3></td>
</tr>

<?php break;

case 'text':
?>

<tr>
	<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
	<td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
	<td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'color':
?>
<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/js/farbtastic.js"></script>
<link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/js/farbtastic.css" type="text/css" />
<tr>
	<td width="20%" rowspan="3" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
</tr>
<tr>
	<td width="40%" id="colorpicker<?php echo $value['id2']; ?>"></td>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#colorpicker<?php echo $value['id2']; ?>').farbtastic('#<?php echo $value['id']; ?>');
	});
</script>
</tr>
<tr>
	<td><input style="width:200px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" /><br><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'textarea':
?>

<tr>
	<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
	<td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:100px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo esc_textarea( get_option( $value['id'] ) ); } else { echo esc_textarea ( $value['std'] ); } ?></textarea></td>

</tr>

<tr>
	<td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'select':
?>
<tr>
	<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
	<td width="80%"><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select></td>
</tr>

<tr>
	<td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>



<?php
break;

case 'multiselect':
?>
<tr valign="middle">
		<th scope="top" style="text-align:left;"><?php echo $value['name']; ?>:</th>
	<?php if(isset($value['desc'])){?>
	</tr>
	<tr valign="middle">
		<td style="width:40%;"><?php echo $value['desc']?></td>
	<?php } ?>
		<td>
			<select  multiple="multiple" size="3" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" style="height:50px;">
				<?php $ch_values=explode(',',get_option( $value['id'] ));
				$pages = get_pages();
				foreach ($pages as $pagg) { ?>
				<option<?php if ( in_array($option,$ch_values)) { echo ' selected="selected"'; }?> value="<?php echo $option; ?>"><?php echo $option; ?></option>
				<?php } ?>
			</select>		<select multiple="multiple" size="10" style="height:250px;" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>"
 >
 <option value="<?php echo $option; ?>">
<?php _e('Select page'); ?></option>
 <?php
  $pages = get_pages();
  foreach ($pages as $pagg) {
  	$option = '<option value="'.get_page_link($pagg->ID).'">';
	$option .= $pagg->post_title;
	 if ( in_array($option,$ch_values)) { echo ' selected="selected"'; }
	$option .= '</option>';
	echo $option;
  }
 ?>

</select>
		</td>
	</tr>
<tr><td colspan=2><hr /></td></tr>

<?php
break;

case "checkbox":
?>
	<tr>
	<td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
		<td width="80%"><?php if ( get_option($value['id']))
		{ $checked = 'checked="checked"'; }
			else{ $checked = ""; } ?>
				<input type="checkbox" value="1" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $checked; ?> />
				</td>
	</tr>

	<tr>
		<td><small><?php echo $value['desc']; ?></small></td>
   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ccc;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
<div class="rm_input rm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

<?php if(get_option($value['id'])){ $checked = 'checked="checked"'; }else{ $checked = 'checked="checked"';} ?>
<input value="1" type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"<?php echo $checked; ?> />

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php		 break;

}
}
?><p class="submit">
<input name="save" type="submit" value="<?php esc_attr_e( 'Save Options', 'uti_theme' ); ?>" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="<?php esc_attr_e( 'Reset', 'uti_theme' ); ?>" />
<label>Reset default values for all Theme Options.</label>
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}

add_action('admin_menu', 'uti_add_admin');

add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
register_sidebar(array(
		'name' => 'footercell-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="divider"></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2><div class="line"></div>',
	));
register_sidebar(array(
		'name' => 'footercell-mid-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="divider"></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2><div class="line"></div>',
	));
register_sidebar(array(
		'name' => 'footercell-mid-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="divider"></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2><div class="line"></div>',
	));
register_sidebar(array(
		'name' => 'footercell-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="divider"></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2><div class="line"></div>',
	));

// Enable nav menu for Primary Navigation in header
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'uti_theme' ),
) );

function uti_page_menu() { // fallback for primary navigation ?>
	<ul class="menu">
		<?php wp_list_pages( '&title_li=' ); ?>
	</ul>
<?php }
