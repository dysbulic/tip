<?php
/**
 * @package Inuit Types
 */

add_theme_support( 'automatic-feed-links' );

$content_width = 594; // pixels

$stylesheet = get_option('inuitypes_alt_stylesheet');

switch ( $stylesheet ) {
	case '2-dark-black':
		$themecolors = array(
			'bg' => '0a0a0a',
			'text' => 'd8d8cd',
			'link' => 'd8d8cd',
			'border' => '333333',
			'url' => '346BA4',
		);
		break;

	default:
		$themecolors = array(
			'bg' => 'ffffff',
			'text' => '333333',
			'link' => '222222',
			'border' => 'dddddd',
			'url' => '346ba4',
		);
		break;
}

// gets the page number of the current page
function it_the_page_number() {
	global $paged; // Contains page number.
	if ( $paged >= 2 )
		echo ' | ' . sprintf( __( 'Page %s' , 'inuittypes' ), $paged );
}

// loads the intro widget area
function inuit_types_intro() { ?>
	<?php if ( is_active_sidebar(2) && is_front_page() && ! is_paged() ) : ?>

	<div id="header-about">

	<?php dynamic_sidebar(2); ?>

	</div>

	<?php endif; ?>
<?php }

// Get the URL of the next image in the gallery
if ( ! function_exists( 'theme_get_next_attachment_url' ) ) :
function theme_get_next_attachment_url() {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') ));

	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

		$k = $k + 1;

		if ( isset($attachments[$k]) ) {
			return get_attachment_link($attachments[$k]->ID);
		} else {
			return get_permalink($post->post_parent);
		}
}
endif;

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails', array( 'post' ) );
set_post_thumbnail_size( 600, 250, true );
add_image_size( 'it-thumbnail', 90, 75, true );
add_image_size( 'one-column-feature', 594, 250, true );
add_image_size( 'two-column-feature', 278, 150, true );

// Add scripts
function theme_scripts() {
	$template_directory = get_bloginfo( 'template_directory' );
	wp_enqueue_script( 'suckerfish', "$template_directory/library/js/suckerfish.js" );
}
//add_action( 'init', 'theme_scripts' );

// This theme uses wp_nav_menu()
// Register nav menu locations
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'inuittypes' ),
) );


// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'it', TEMPLATEPATH . '/languages' );

$themename = "Inuit Types";
$shortname = "inuitypes";
$template_path = get_bloginfo('template_directory');

$alt_stylesheet_path = TEMPLATEPATH . '/skins/';
$alt_stylesheets = array();
$frame_path = TEMPLATEPATH . '/frames/';
$frames = array();
$pn_categories_obj = get_categories('hide_empty=0');
$pn_categories = array();
$pne_categories_obj = get_categories('hide_empty=0');
$pne_categories = array();
$pne_pages_obj = get_pages('sort_order=ASC');
$pne_pages = array();

// Alternative Stylesheet Load
if ( is_dir($alt_stylesheet_path) ) {
	if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
		while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
			if(stristr($alt_stylesheet_file, ".css") !== false) {
				$alt_stylesheet_file = str_replace('.css', '', $alt_stylesheet_file);
				$alt_stylesheets[] = $alt_stylesheet_file;
			}
		}
	}
}

// Categories Name Load
foreach ($pn_categories_obj as $pn_cat) {
	$pn_categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
}
$categories_tmp = array_unshift($pn_categories, "Select a category:");

// Pages Exclude Load
/*foreach ($pne_pages_obj as $pne_pag) {
	$pne_pages[$pne_pag->ID] = $pne_pag->post_title;
}*/

// Exclude Pages by Name
/*function pages_exclude($options) {
	$options[] = array(	"type" => "wraptop");
	$pags = get_pages('sort_order=ASC');
	foreach ($pags as $pag) {
			$options[] = array(	"name" => "",
						"desc" => "",
						"label" => $pag->post_title,
						"id" => "pag_exclude_".$pag->ID,
						"std" => "",
						"type" => "checkbox");
	}
	$options[] = array(	"type" => "wrapbottom");
	return $options;
}*/

// Custom Page List
function get_inc_pages($label) {
	$include = '';
	$counter = 0;
	$pagsx = get_pages('sort_order=ASC');
	foreach ($pagsx as $pag) {
		$counter++;
		if ( get_option( $label.$pag->ID ) ) {
			if ( $counter <> 1 ) { $include .= ','; }
			$include .= $pag->ID;
			}
	}
	return $include;
}

$other_entries = array("Select a Number:","0","1","2","3","4","5","6","7","8","9","10");

				$options[] = array(	"name" => __( 'Color scheme', 'it' ),
						"desc" => "Please select the CSS skin of your blog here.",
					    "id" => $shortname."_alt_stylesheet",
					    "std" => __( 'Select a CSS skin:', 'it' ),
					    "type" => "select",
					    "options" => $alt_stylesheets);

				$options[] = array(	"name" => __( 'Sidebar on the left or right?', 'it' ),
					    "desc" => __( 'Show sidebar content on the left.', 'it' ),
					    "id" => $shortname."_right_sidebar",
					    "std" => "true",
					    "type" => "checkbox");

				$options[] = array(	"name" => __( 'Featured Post Entries', 'it' ),
						"desc" => "Select max number of featured entries you wish to appear on homepage. Featured entries are the latest highlighted posts.",
			    		"id" => $shortname."_featured_entries",
			    		"std" => __( 'Select a Number:' , 'it' ),
			    		"type" => "select",
			    		"options" => $other_entries);

				$options[] = array(	"name" => __( 'One Column Featured Posts', 'it' ),
					"desc" => "Show featured posts in one column instead of default two columns",
					"id" => $shortname."_one_column_featposts",
					"std" => "false",
					"type" => "checkbox");

				$options[] = array(	"name" => __( 'One Column Normal Posts', 'it' ),
					"desc" => "Show normal posts in one column instead of default two columns",
					"id" => $shortname."_one_column_posts",
					"std" => "false",
					"type" => "checkbox");

				$options[] = array(	"name" => __( 'Single Post Featured Image', 'it' ),
					"desc" => "Show a featured image on single posts",
					"id" => $shortname."_single_post_image",
					"std" => "false",
					"type" => "checkbox");


function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {

        if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
						if ( isset( $_REQUEST[ $value['id'] ] ) )
                    		update_option( $value['id'], $_REQUEST[ $value['id'] ] );
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							if ( isset( $_REQUEST[$up_opt] ) )
								update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); }
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); }
						}
					}
				}
                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if ( isset( $_REQUEST['action'] ) && 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
				if($value['type'] != 'multicheck'){
                	delete_option( $value['id'] );
				}else{
					foreach($value['options'] as $mc_key => $mc_value){
						$del_opt = $value['id'].'_'.$mc_key;
						delete_option($del_opt);
					}
				}
			}
            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page("Theme Options", "Theme Options", 'edit_theme_options', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( isset( $_REQUEST['saved'] ) && $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';

?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo $themename; ?> <?php _e( 'Options', 'it' ); ?></h2>


<form method="post">
<table class="form-table">
<tbody>

<?php foreach ($options as $value) {
	?>
	<tr valign="top">
	<?php

	switch ( $value['type'] ) {
		case 'select':
		if ( $value['id'] == 'inuitypes_alt_stylesheet' ) {
			sort($value['options']);
		}
		?>
				<th scope="row"><?php echo $value['name']; ?></th>
				<td>
					<select class="select_input" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
		                <?php foreach ($value['options'] as $option) { ?>
		                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
		                <?php } ?>
		            </select>
	            </td>
		<?php
		break;

		case "checkbox":
						if(get_settings($value['id'])){
							$checked = "checked=\"checked\"";
						}else{
							$checked = "";
						}
					?>
					<th scope="row"><?php echo $value['name']; ?></th>
					<td>
			            <input class="input_checkbox" type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />&nbsp;<label for="<?php echo $value['id']; ?>"><?php echo $value['desc']; ?></label><br />
			        </td>
		<?php
		break;
	}
	?>
	</tr>
	<?php
}
?>

</tbody>
</table>
<p></p>
<p class="submit" style="float:left;">
<input class="button-primary" name="save" type="submit" value="<?php esc_attr_e( 'Save changes', 'it' ); ?>" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit" style="float:right;">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}

function mytheme_wp_head() {
	$stylesheet = get_option('inuitypes_alt_stylesheet');
	if($stylesheet != ''){
	?>
		<link href="<?php bloginfo('template_directory'); ?>/skins/<?php echo $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<?php
	} else { ?>
		<link href="<?php bloginfo('template_directory'); ?>/skins/1-default.css" rel="stylesheet" type="text/css" />
	<?php }
}

add_action('wp_head', 'mytheme_wp_head');
add_action('admin_menu', 'mytheme_add_admin');

register_sidebar( array (
	'name' => 'Sidebar',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div><!--/widget-->',
    'before_title' => '<h3 class="hl">',
    'after_title' => '</h3>',
) );

register_sidebar( array (
	'name' => 'Front Page Intro',
	'description' => __('Use a Text Widget in this widget area to add an introduction to your front page' , 'it'),
    'before_widget' => '<div class="header-widget">',
    'after_widget' => '</div>',
    'before_title' => '',
    'after_title' => '',
) );

// Comments
require_once( dirname( __FILE__ ) . '/library/functions/comments_functions.php');

// Create nice excerpt link for index, search, and archive pages
function inuit_types_excerpt( $excerpt, $permalink ) {
	// remove anchor and strong elements
	strip_tags( $excerpt, '<a><strong>');

	// add permalink
	$link = '&hellip; <a class="read-on" href="' . $permalink . '"><em>[' . __( 'Read more&hellip;', 'it' ) . ']</em></a>';
	$excerpt = str_replace( ' [...]', $link, $excerpt );

	return $excerpt;
}