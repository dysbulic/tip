<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php require_once get_template_directory()."/BX_functions.php"; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>?1" type="text/css" media="screen, projection" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<div id="container"<?php if ( is_page() && !is_page( "archives" ) ) echo " class=\"singlecol\""; ?>>

<div id="header">
	<h1><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
</div>

<div id="navigation">

	<form action="<?php bloginfo( 'url' ); ?>/" method="get">
		<fieldset>
			<input value="<?php the_search_query(); ?>" name="s" id="s" />
			<input type="submit" value="Go!" id="searchbutton" name="searchbutton" />
		</fieldset>
	</form>

	<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'fallback_cb' => 'blix_page_menu' ) ); ?>

</div><!-- /navigation -->

<hr class="low" />
