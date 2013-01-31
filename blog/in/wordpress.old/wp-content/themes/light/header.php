<?php include("pagefunctions.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>
</head>

<body id="home" <?php body_class( 'log' ); ?>>
<div id="header">
  <div id="logo">
    <h1 id="blogname"><a href="<?php bloginfo( 'url' ); ?>">
      <?php  bloginfo( 'name' ); ?>
      </a></h1>
    <div class="description">
      <?php bloginfo( 'description' ); ?>
    </div>
  </div>
  <div>
    <?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_class' => 'navigation menu', 'fallback_cb' => 'light_page_menu' ) ); ?>
  </div>
</div>
<div id="wrap">
