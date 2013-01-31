<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />

<?php if ( strtoupper( get_locale() ) == 'JA' ) ://to fix the font-size for japanese font ?>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/japanese.css" type="text/css" media="screen" />
<?php endif; ?>
<!--[if lt IE 7]>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/ie6.css" type="text/css" media="screen" />
<![endif]--> 
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>

</head>

<body <?php body_class(); ?>>
<div id="wrapper">

 <div id="header" class="clearfix">

  <div id="header_top"> 
   <div id="logo">
    <a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
    <h1><?php bloginfo( 'description' ); ?></h1>
   </div>
   <div id="header_menu">
	<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_id' => 'menu', 'fallback_cb' => 'monochrome_page_menu' ) ); ?>
   </div>
  </div>

  </div><!-- #header end -->