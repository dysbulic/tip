<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head>

<body class="<?php notesil_body_class(); ?>">

<div id="wrapper" class="hfeed">

	<div id="header">
		<h1 id="blog-title">
			<?php if (  function_exists( 'blavatar_exists' ) && blavatar_exists( blavatar_current_domain() ) ): ?>
				<img src="<?php echo esc_url( blavatar_url( blavatar_current_domain(), 'img', 50, false, 'invalidate-cache' ) ); ?>" alt="icon" class="blog-icon" />
			<?php else: ?>
				<img src="<?php echo get_template_directory_uri(); ?>/i/logo.gif" alt="icon" class="blog-icon" />
			<?php endif; ?>
			<span><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
		<div id="blog-description"><?php bloginfo( 'description' ); ?></div>
	</div><!--  #header -->