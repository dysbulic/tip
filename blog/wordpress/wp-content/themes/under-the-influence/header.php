<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */
?>
<?php
	global $options;
	foreach ($options as $value) {
		if (array_key_exists('id',$value)) {
			if (get_option( $value['id'] ) === FALSE) {
				$$value['id'] = $value['std'];
			} else {
				$$value['id'] = get_option( $value['id'] );
			}
		}
	}
	load_theme_textdomain('uti_theme');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta http-equiv="description" content="<?php bloginfo('description'); ?>" />
		<title>
		<?php
			if ( is_single() )
				{ single_post_title(); echo ' | '; bloginfo('uti_theme'); }
			elseif ( is_home() || is_front_page() )
				{ bloginfo('name'); echo ' | '; bloginfo('description'); }
			elseif ( is_page() )
				{ single_post_title(''); echo ' | '; bloginfo('uti_theme'); }
			elseif ( is_search() )
				{ bloginfo('name'); echo ' | '; _e('Search results for ', 'uti_theme') . get_search_query(); }
			elseif ( is_404() )
				{ bloginfo('name'); echo ' | '; _e('Not Found', 'uti_theme'); }
			else
				{ bloginfo('name'); wp_title( ' | ' ); }
		?>
		</title>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('url') ?>/?css=css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/print.css" type="text/css" media="print"/>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" />
		<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/images/iphone.png" />
		<!--[if lte IE 7]>
		<style type="text/css">
			/* these had been applied using CSS hacks in the main stylesheet. I moved them
			to this conditional comment - kjacobson */
			.alignleft img, .alignright img {
				min-width: 150px;
			}
			.gallery-item {
				min-width: 150px;
			}
		</style>
		<![endif]-->
		<!--[if lte IE 6]>
		<style type="text/css">
			/* box-model fix. side bar is floated right and this was falling below it, not beside it, in IE6 */
			.singlepage .page {
				width: 768px;
			}
			/* IE6 can't handle the transparency in these PNGs (and filter: wasn't effective), so load GIFs instead and suffer
			slight jaggies */
			.corner_tr { right: -7px; }
			.corner_tl, .corner_tr {
				background-image: url(<?php bloginfo('template_directory'); ?>/images/kreis_voll.gif);
			}
			.corner_br { right: -1px; }
			.corner_br, .corner_bl {
				background-image: url(<?php bloginfo('template_directory'); ?>/images/kreis.gif);
				bottom: -2px;
			}
			/* trigger hasLayout on decorative lines */
			#footer .line {
				height: 1%;
			}
		</style>
		<![endif]-->
		<?php
			if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

			$headerimage = get_header_image();
			if ($headerimage == ""){
				$headerstyle = 1;
			} else {
				$headerstyle = 2;
			}
			global $author;
			$author = $uti_show_author;
		?>
		<?php
			global $post;	 // if outside the loop
			if ( !is_singular() && !is_page() && !is_404() && $uti_column == "on") {
				wp_enqueue_script('jquery');
				wp_enqueue_script('jmasonry');
				wp_enqueue_script('jinit_mason');
			}

			wp_head();
			?>
	</head>
	<body <?php body_class(); ?><?php if (is_single() || is_page()) {} else if ($uti_column == "on") {echo ' id="three_column"';} ?>>
		<div class="ornament"></div>
		<div id="page">
			<div id="header">
				<?php
					if ($headerimage == ""){ // Checks if custom header image is set, continues if nothing is set
						if ($uti_header_design == 2){ // Executes if header design 2 is chosen
				?>
				<div id="title_box">
					<b class="corner_tl"></b><!-- presentational tag name for presentational tags - kjacobson -->
					<b class="corner_tr"></b>
					<h1>
						<a href="<?php echo home_url( '/' ); ?>">
							<?php bloginfo('name'); ?>
						</a>
					</h1>
		   			<?php if ($uti_description == "on"){?>
						<p class="description">
							<?php bloginfo('description'); ?>
						</p>
					<?php
						}
					?>
					<b class="corner_bl"></b>
					<b class="corner_br"></b>
				</div><!--#title_box-->
				<?php
					} else {
						// If custom header image is set
				?>
				<h1>
					<a href="<?php echo home_url( '/' ); ?>">
						<?php bloginfo('name'); ?>
					</a>
				</h1>
				<?php
					}
				} else {
					// Executes when no custom header is set and header design 1 is activated
				?>
				<h1>
					<a href="<?php echo home_url( '/' ); ?>">
						<?php bloginfo('name'); ?>
					</a>
				</h1>
			<?php
				}
			?>
			<?php
				if ($uti_description == "on"){
					// Checks if description is shown
			?>
			<p class="description">
				<?php bloginfo('description'); ?>
			</p>
			<?php
				}
			?>
		</div><!--#header-->

		<div id="navigation">
			<?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'uti_page_menu' ) ); ?>
		</div>