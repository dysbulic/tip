<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<?php
	global $hemingway;
	if ($hemingway->style and $hemingway->style != 'none') :
?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/<?php echo $hemingway->style; ?>" type="text/css" media="screen" />
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>
<body>
	<div id="header">
		<div class="inside">
			<div id="search">
				<form method="get" id="sform" action="<?php bloginfo('url'); ?>/">
 					<div class="searchimg"></div>
					<input type="text" id="q" value="<?php the_search_query(); ?>" name="s" size="15" />
				</form>
			</div>
			
			<h2><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('name'); ?></a></h2>
			<p class="description"><?php bloginfo('description'); ?></p>
		</div>
	</div>
	<!-- [END] #header -->
