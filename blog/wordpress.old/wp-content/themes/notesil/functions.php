<?php
/**
 * @package WordPress
 * @subpackage NotesIL
 */

/*
This file is part of SANDBOX.

SANDBOX is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

SANDBOX is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with SANDBOX. If not, see http://www.gnu.org/licenses/.
*/

$content_width = 530;

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'bc0404',
	'text' => '000000',
	'link' => 'bc0404',
	'url' => '00447c',
);

function notes_comments( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );
	global $notes_comments_alt;
	?>
						<li id="comment-<?php comment_ID(); ?>" class="<?php notesil_comment_class(); ?>">
							<div class="singlecomment" id="div-comment-<?php comment_ID(); ?>">
								<div class="comment-meta"><?php notesil_commenter_link(); ?>
									<span class="comment-date">&nbsp;<?php printf( __( 'On %1$s at %2$s', 'notesil' ), get_comment_date(), get_comment_time() ); ?></span>
									<div class="comment-actions"><?php printf( __( '<a href="%s" title="Permalink to this comment">Permalink</a>', 'notesil' ), '#comment-' . get_comment_ID() );
												echo comment_reply_link(array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ' ) );
												edit_comment_link( __( 'Edit', 'notesil' ), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
									</div>
								 </div>

<?php if ( $comment->comment_approved == '0' ) _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'notesil' ); ?>
							<?php comment_text(); ?>
							</div>
<?php }

function notes_trackbacks( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );
	global $notes_comments_alt;
	?>
    <li id="comment-<?php comment_ID(); ?>" class="<?php notesil_comment_class(); ?>">
		<div class="singlecomment">
	    	<div class="comment-author"><?php printf( __( 'By %1$s on %2$s at %3$s', 'notesil' ),
	    			get_comment_author_link(),
	    			get_comment_date(),
	    			get_comment_time() );
	    			edit_comment_link( __( 'Edit', 'notesil' ), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?></div>
	    	<?php if ( $comment->comment_approved == '0' ) _e( '\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'notesil' ); ?>
	    	<?php comment_text(); ?>
		</div>
<?php }

function notesil_globalnav() {
	if ( $menu = str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( 'title_li=&sort_column=menu_order&echo=0' ) ) )
		$menu = '<ul>' . $menu . '</ul>';
	$menu = '<div id="menu">' . $menu . "</div>\n";
	echo apply_filters( 'globalnav_menu', $menu ); // Filter to override default globalnav: globalnav_menu
}

// Generates semantic classes for BODY element
function notesil_body_class( $print = true ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$c = array( 'wordpress' );

	// Applies the time- and date-based classes (below) to BODY element
	notesil_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			notesil_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta( 'login' )) );
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta( 'login' )) );
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 ) {
		// Thanks to Prentiss Riddle, twitter.com/pzriddle, for the security fix below.
		$page = intval( $page ); // Ensures that an integer (not some dangerous script) is passed for the variable
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print( $c ) : $c;
}

// Generates semantic classes for each post DIV element
function notesil_post_class( $print = true ) {
	global $post, $notesil_post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array( 'hentry', "p$notesil_post_alt", $post->post_type, $post->post_status );

	// Author for the post queried
	$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta( 'login' )) );

	// Category for the post queried
	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	// Tags for the post queried; if not tagged, use .untagged
	if ( get_the_tags() == null ) {
		$c[] = 'untagged';
	} else {
		foreach ( (array) get_the_tags() as $tag )
			$c[] = 'tag-' . $tag->slug;
	}

	// For password-protected posts
	if ( $post->post_password )
		$c[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	notesil_date_classes( mysql2date( 'U', $post->post_date ), $c );

	// If it's the other to the every, then add 'alt' class
	if ( ++$notesil_post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join( ' ', apply_filters( 'post_class', $c ) ); // Available filter: post_class

	// And tada!
	return $print ? print( $c ) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$notesil_post_alt = 1;

// Generates semantic classes for each comment LI element
function notesil_comment_class( $print = true ) {
	global $comment, $post, $notesil_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array( $comment->comment_type );

	// Counts trackbacks (t[n]) or comments (c[n])
	if ( $comment->comment_type == 'comment' ) {
		$c[] = "c$notesil_comment_alt";
	} else {
		$c[] = "t$notesil_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata( $comment->user_id );
		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = 'byuser comment-author-' . sanitize_title_with_dashes(strtolower( $user->user_login ) );
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	notesil_date_classes( mysql2date( 'U', $comment->comment_date ), $c, 'c-' );
	if ( ++$notesil_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join( ' ', apply_filters( 'comment_class', $c ) ); // Available filter: comment_class

	// Tada again!
	return $print ? print( $c ) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function notesil_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option( 'gmt_offset' ) * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function notesil_cats_meow( $glue ) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list( $separator ) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset( $cats[$i] );
			break;
		}
	}
	if ( empty( $cats ) )
		return false;

	return trim( join( $glue, $cats ) );
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function notesil_tag_ur_it( $glue ) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset( $tags[$i] );
			break;
		}
	}
	if ( empty( $tags ) )
		return false;

	return trim( join( $glue, $tags ) );
}

// Produces an avatar image with the hCard-compliant photo class
function notesil_commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_size = apply_filters( 'avatar_size', '32' ); // Available filter: avatar_size
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $GLOBALS['comment'], $avatar_size ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}


// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function notes_sidebar_init() {
	// Formats the Sandbox widgets, adding readability-improving whitespace
	register_sidebar( array(
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s">',
		'after_widget'   =>   "\n\t\t\t</li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<h3 class="widgettitle">',
		'after_title'    =>   "</h3>\n"
	) );
}
add_action( 'init', 'notes_sidebar_init' );

// Translate, if applicable
load_theme_textdomain( 'notesil', get_template_directory() . '/languages' );


// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

//Notes color customizer, inspired by Toni's

function get_notes_colors() {
	return array(
		'default' => 'BC0404',
		'black' => '000',
		'darkblue' => '213970',
		'darkgreen' => '3D750B',
		'darkpurple' => '660066',
		'grey' => '6B6B6B',
		'lightblue' => 'BAC5D2',
		'lightgreen' => '749881',
		'lightorange' => 'DC6700',
		'lightpurple' => '9999CC',
		'olive' => '660',
		'strongbrown' => '950'
		);
}

function get_notes_color() {
	$notes_color = get_option( 'notes_color' );
	if ( $notes_color == '' )
		$notes_color = 'default';
	return clean_notes_color( $notes_color );
}

function notes_color_select() {
	echo "<select id='notes_color' name='notes_color' size='4'>";
	$colors = get_notes_colors();
	$current_color = get_notes_color();
	foreach ( $colors as $name => $value ) {
		$selected = ( $current_color == $value ) ? " selected='selected'" : '';
		echo "<option value='$value'$selected>$name</option>";
	}
	echo "</select>\n";
}

function notes_color_radios() {
	$colors = get_notes_colors();
	$current_color = get_notes_color();
	$theme_uri = get_template_directory_uri();
	$i = 0;
	foreach ( $colors as $name => $value ) {
		$checked = ( $current_color == $name ) ? " checked='checked'" : '';
		echo "<tr valign='middle'><td align='right'><input type='radio' name='notes_color' id='notes_color_{$i}' value='{$name}'$checked /></td><td><label for='notes_color_{$i}'><span style='height:30px; width:80px;background-color:#{$value}; display:block;'>&nbsp;</span></label></td>
				<th style='height:20px; width:50px;'>&nbsp;</th>
		</tr>\n";
		++$i;
	}
}

function clean_notes_color( $notes_color ) {
	$notes_colors = get_notes_colors();
	if ( array_key_exists( $notes_color, $notes_colors ) )
		return $notes_color;
	else
		return '';
}

function notes_color() {
	echo get_notes_color();
}

function notes_css( $body_class ) {
	$notes_color = get_notes_color();
	if ( !empty( $notes_color ) )
		$body_class[]= $notes_color;
	return( $body_class );
}
add_filter( 'body_class', 'notes_css' );

add_action( 'admin_menu', 'notes_add_theme_page' );

function notes_add_theme_page() {
	global $notes_message;
	if ( isset( $_POST['action']) && 'notes_update' == $_POST['action'] ) {
		$color = clean_notes_color( $_POST['notes_color'] );
		update_option( 'notes_color', $color);
		$message = __("Color changed.", 'notesil' );
		$notes_message = "<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p><strong>$message</strong></p></div>";
	}

	add_theme_page("Theme Options", __("Theme Options", 'notesil' ), 'edit_theme_options', basename(__FILE__), 'notes_theme_page' );
}

function notes_theme_page() {
	global $notes_message;
	echo $notes_message;
?>
<div class="wrap">
 <h2><?php _e( 'Color selection', 'notesil' ); ?></h2>
 <p><?php _e( 'Please select the primary color of your blog theme', 'notesil' ); ?></p>
  <form method="post">
  <table class="optiontable"><tbody>
<?php notes_color_radios(); ?>
  <tr><td> </td><td align="left"><p class="submit left"><input type="submit" name="Submit" value="<?php esc_attr_e( 'Apply Color &raquo;', 'notesil' ); ?>" /></p></td></tr>
  </tbody></table>
  <input type="hidden" name="action" value="notes_update" />
  </form>

</div>
<?php
}


class Notes_Author_Widget extends WP_Widget {

	function Notes_Author_Widget() {
		$widget_ops = array( 'classname' => 'widget_notes_author', 'description' => __( 'The author Name, Gravatar, and description', 'notesil' ) );
		$this->WP_Widget( 'notes_author', __( 'Notes Author', 'notesil' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = '';
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
					echo get_avatar(get_the_author_meta( 'user_email' ), $size = '60', $default = get_template_directory_uri() . '/i/default-avatar.gif' );
				 	echo '<h3>' . get_the_author_meta( 'display_name' ) . '</h3>';
					echo '<p>' . get_the_author_meta( 'description' ) . '</p>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags( $instance['title'] );
?>
		<p><?php _e( 'Display your Gravatar, and your name and description from <a href="profile.php">your user profile</a>', 'notesil' ); ?>.</p>
		<p><?php _e( 'Don\'t have a Gravatar? <a href="http://gravatar.com">Get one now.</a>', 'notesil' ); ?></p>
<?php
	}
}

if ( ! defined( 'IS_WPCOM' ) && ! 'IS_WPCOM' ) {
	//only activate the authors widget on when not on WPCOM.
	add_action( 'widgets_init', create_function( '', 'return register_widget("Notes_Author_Widget");' ) );
}

add_theme_support( 'automatic-feed-links' );

add_custom_background();
