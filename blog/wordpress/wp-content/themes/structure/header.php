<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php if ( st_option( 'dark_scheme' ) ) : ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/dark.css" type="text/css" media="screen" />
<?php endif; ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie6.css" type="text/css" media="screen" />
<!--[if IE 6]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie6.css" type="text/css" media="screen" />
<![endif]-->

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

<script type="text/javascript"> 
/* <![CDATA[ */ 
	var $j = jQuery.noConflict();
	
	$j(document).ready(function() { 
		$j('ul.ot-menu').superfish(); 
	});
/* ]]> */ 
</script>

</head>

<body>

<div id="wrap">
    <div id="header">
    
        <div class="headerleft">
            <h1 id="title"><a href="<?php echo home_url( '/' ); ?>" title="Home"><span id="sitename"><?php bloginfo('name'); ?></span></a></h1>
        </div>
        
        <div class="headerright">
            <form id="searchformheader" method="get" action="<?php echo home_url(); ?>">           
	            <input type="text" value="<?php _e( 'Type here and press enter to search', 'structuretheme' ); ?>" name="s" id="searchbox" onfocus="if (this.value == '<?php _e( 'Type here and press enter to search', 'structuretheme' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Type here and press enter to search', 'structuretheme' ); ?>';}" />
	            <input type="hidden" id="searchbutton" value="Go" />
			</form>
            
            <div id="navicons">
            	
        	<?php if ( st_option( 'dark_scheme' ) ) : ?>
        	
				<?php if ( st_option('twitter_url') ) : ?>            	            	
				<a href="<?php echo esc_url( st_option('twitter_url') ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/twitter_icon_black.png" title="Twitter" alt="Twitter" />
				</a>
				<?php endif; ?>
				
				<?php if ( st_option('facebook_url') ) : ?>            	
				<a href="<?php echo esc_url( st_option('facebook_url') ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/facebook_icon_black.png" title="Facebook" alt="Facebook" />
				</a>            	
				<?php endif; ?>
				
				<a href="<?php bloginfo( 'rss2_url' ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/rss_icon_black.png" title="<?php _e( 'RSS feed', 'structuretheme' ); ?>" alt="RSS" />
				</a>					
        	
        	<?php else : ?>
        	
				<?php if ( st_option('twitter_url') ) : ?>            	            	
				<a href="<?php echo esc_attr( st_option('twitter_url') ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/twitter_icon.png" title="Twitter" alt="Twitter" />
				</a>
				<?php endif; ?>
				
				<?php if ( st_option('facebook_url') ) : ?>            	
				<a href="<?php echo esc_attr( st_option('facebook_url') ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/facebook_icon.png" title="Facebook" alt="Facebook" />
				</a>            	
				<?php endif; ?>
				
				<a href="<?php bloginfo( 'rss2_url' ); ?>">
					<img class="navicon" src="<?php bloginfo('template_url'); ?>/images/rss_icon.png" title="<?php _e( 'RSS feed', 'structuretheme' ); ?>" alt="RSS" />
				</a>
				
        	<?php endif; ?>
            </div>
            
        </div>
    
    </div>    
    
    <?php
    if ( function_exists('wp_nav_menu') ) {
	    wp_nav_menu( array( 'container_class' => 'navbar', 'menu_class' => 'ot-menu', 'theme_location' => 'primary' ) );
    } else {
	    wp_page_menu();
    }
    ?>
    
    <div style="clear:both;"></div>
