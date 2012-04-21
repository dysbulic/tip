<?php
$content_width = 315;
define(MIN_WIDTH, 560);
define(MAX_WIDTH, 840);

add_theme_support( 'automatic-feed-links' );

if (function_exists('register_sidebar') )
    register_sidebar(array('name' => 'Sidebar'));

include 'inc/duotonelibrary.php';

function partial($file) { include $file.'.php'; }
if( is_admin() ) include 'functions-admin.php';

if ( !defined('IS_WPCOM') ) {
	add_filter( 'duotone_image_html', 'fake_rewrites' );
	function fake_rewrites($tag) {
		$thumby = get_bloginfo('template_url').'/inc/thumb.php';
		$width = (is_vertical()) ? MIN_WIDTH : MAX_WIDTH;
		if( is_archive() ) 
			$width = '75&h=75&f=1';
		return str_replace(get_bloginfo('wpurl'), '', preg_replace('/src=("|\')([^\1]+)\?w=[0-9]+(\1)/','src="'.$thumby.'?image='.addslashes( "$2").'&w='.$width.'"',$tag) );
	}
}

// filters and actions
add_action( 'wp_head', 'header_function' );

function header_function() {
	global $vertical;
	if( !is_single() && is_home() && !is_archive() ) query_posts( "what_to_show=posts&posts_per_page=1" );
	if(!is_archive() && !is_search()) : ?>
		<style type="text/css" media="screen">
		<?php
		while ( have_posts() ) : the_post();
			// ececute the specific stylesheet
			print_stylesheet();
			// determine if an image is vertical or not
			if( is_vertical(image_url(true)) ) { $vertical = true; }
 		endwhile; 
		rewind_posts(); ?>
		</style>
	<?php endif;
}

// force 24 posts per page for archives
function duotone_posts_per_page() {
	return 24;
}
add_filter( 'pre_option_posts_per_page', 'duotone_posts_per_page' );

function print_stylesheet() {
	global $post;
	$color = get_post_colors($post);
	?>
	body {
		background-color: <?php if(get_option('background_color') == '') { ?> #<?php echo $color->bg['+2']; } else { 
			$customcolor =  get_option('background_color');
			if(is_int($customcolor) ) echo '#';
			echo $customcolor;
			}?>;
	}
	#page {
	  	background-color:#<?php echo $color->bg['-2']; ?>;
		color:#<?php echo $color->fg['-2']; ?>;
	}
	#menu a, #menu a:link, #menu a:visited {
		color: #<?php echo $color->bg['-3']; ?>;
	}
	#menu a:hover, #menu a:active {
		color: #<?php echo $color->fg['-3']; ?>;	
	}
	a,a:link, a:visited {
		color: #<?php echo $color->fg['-3']; ?>;
	}
  	a:hover, a:active {
		color: #<?php echo $color->bg['+2']; ?>;
	}	
	h1, h1 a, h1 a:link, h1 a:visited, h1 a:active {
		color: #<?php echo $color->bg['+3']; ?>;
	}
	h1 a:hover {
		color:#<?php echo $color->bg['+2']; ?>;
	}
	.navigation a, .navigation a:link, 
	.navigation a:visited, .navigation a:active {
	  	color: #<?php echo $color->fg['-1']; ?>;
	}
	h1:hover, h2:hover, h3:hover, h4:hover, h5:hover h6:hover,
	.navigation a:hover {
		color:#<?php echo $color->fg['-2']; ?>;
	}
	.description,
	h3#respond,
	#comments,
	#content #sidebar h2,
	h2, h2 a, h2 a:link, h2 a:visited, h2 a:active,
	h3, h3 a, h3 a:link, h3 a:visited, h3 a:active,
	h4, h4 a, h4 a:link, h4 a:visited, h4 a:active,
	h5, h5 a, h5 a:link, h5 a:visited, h5 a:active,
	h6, h6 a, h6 a:link, h6 a:visited, h6 a:active {
	  	/* Use the corresponding foreground color */
	  	color: #<?php echo $color->bg['+3']; ?>;
		border-color: #<?php echo $color->bg['+2']; ?>;
		border-bottom: #<?php echo $color->bg['+2']; ?>;
	}
	#content #sidebar {
		border-top: 1px solid #<?php echo $color->bg['+3']; ?>;
	}

	#postmetadata, #commentform p, .commentlist li, #post, #postmetadata .sleeve, #post .sleeve,
	#content {
		color: #<?php echo $color->bg['+3']; ?>;
		border-color: #<?php echo $color->bg['+3']; ?>;
	} <?php
}

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'duotone' )
) );

// Fallback for primary navigation
function duotone_page_menu() {
	global $wpdb;
	$post_datetimes = $wpdb->get_results( "SELECT YEAR(max(post_date)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970 AND post_type = 'post' AND post_status = 'publish'" ); ?>
	<ul>
		<li><a href="<?php echo get_year_link( $post_datetimes[0]->lastyear ); ?>">archive</a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php } ?>