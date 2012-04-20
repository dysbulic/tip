<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
	<!-- Stylesheet -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" title="Fauna" href="<?php bloginfo('stylesheet_directory'); ?>/fauna-default.css" />
	<?php wp_head(); ?>
	
	<!-- JavaScripts -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/meta/scripts.js"></script>

	<?php /* Custom Fauna Code */
	function noteworthy_link($id, $link = FALSE, $separator = '/', $nicename = FALSE){
    $chain = '';
		$parent = &get_category($id);
    if ($nicename) {
        $name = $parent->slug;
    } else {
        $name = $parent->name;
    }
    if ($parent->parent) $chain .= get_category_parents($parent->parent, $link, $separator, $nicename);
    if ($link) {
        $chain .= '<a href="' . get_category_link($parent->term_id) . '" title="' . sprintf(__("View all posts in %s"), $parent->name) . '">'."&hearts;".'</a>' . $separator;
    } else {
        $chain .= $name.$separator;
    }
    return $chain;
	}
	?>
</head>

<?php // Sections ?>
<?php if (is_home()) { ?>
<body class="bg" id="index">
<?php } else { ?>
<body class="bg">
<?php } ?>

<a id="top"></a>

<div id="wrapper">
	<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?> <?php _e('Home'); ?>"><?php bloginfo('name'); ?></a></h1>
	<div id="menu">
		<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'fauna_page_menu' ) ); ?>
	</div>
	
	<div id="searchbox">
		<fieldset>
			<legend><label for="s"><?php _e('Search') ?></label></legend>
			<form id="searchform" method="get" action="<?php bloginfo('url'); ?>">
				<input name="s" type="text" class="inputbox" id="s" value="<?php the_search_query(); ?>" />
				<input type="submit" value="<?php esc_attr_e( 'Search' ); ?>" class="pushbutton" />
			</form>
		</fieldset>
	</div>

	<div id="header">&nbsp;</div>

<hr />
