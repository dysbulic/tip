<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
   
	<!--[if IE 6]>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie6.css" />
    <![endif]-->	
	
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" />
	<![endif]-->
   
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="container">

	<div id="navigation">
	
		<div class="col-full">
	
	        <div id="description" class="fl"><?php bloginfo( 'description' ); ?></div>
	        	        
	        <div id="topsearch" class="fr">
				<?php get_search_form(); ?>
 		  	</div><!-- /#topsearch -->
        
        </div><!-- /.col-full -->
        
	</div><!-- /#navigation -->

	<?php do_action( 'before' ); ?>
	<div id="header" class="col-full">
   
		<div id="logo" class="fl">
	       
	      	<?php if(is_single() || is_page()) : ?>
	      		<span class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
	      	<?php else: ?>
	      		<h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
	      	<?php endif; ?>	      	
	      	
		</div><!-- /#logo -->
	       
	   	<div id="pagenav" class="nav fr">	   	

		    <?php wp_nav_menu( 'fallback_cb=bueno_page_menu&theme_location=primary' ); ?>    	    	
	    	
	    </div><!-- /#pagenav -->	

		<?php
			// Check for a header image
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) :
		?>
	    <div id="header-image">
		    <img src="<?php echo $header_image; ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />	    
	    </div>
   	    <?php endif; ?>
       
	</div><!-- /#header -->