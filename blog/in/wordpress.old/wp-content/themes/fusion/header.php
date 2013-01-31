<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />

<!--[if IE]>
<style type="text/css"> 
	#page-wrap1 {
		background: url(<?php bloginfo( 'template_url' ); ?>/images/header-bg.png) repeat-x;
	}
</style>
<![endif]-->

<!--[if lte IE 6]>
<script type="text/javascript">
/* <![CDATA[ */
   blankimgpath = '<?php bloginfo( 'template_url' ); ?>/images/blank.gif';
 /* ]]> */
</script>
<style type="text/css" media="screen">
  @import "<?php bloginfo( 'template_url' ); ?>/ie6.css";
</style>
<![endif]-->

<?php if ( is_singular() && get_option( 'thread_comments' ) )
       wp_enqueue_script( 'comment-reply' );
?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page-wrap1">

<div id="page-wrap2">

	<!-- page (actual site content, custom width) -->
	<div id="page">

	<div id="main-wrap">

    	<div id="mid-wrap">

		<div id="side-wrap">

			<!-- mid column -->
			<div id="mid">

				<div id="header">

				<div id="topnav" class="description"> <?php bloginfo( 'description' ); ?></div>

				<h1 id="title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>

					<div id="tabs">
						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					</div>

				</div>