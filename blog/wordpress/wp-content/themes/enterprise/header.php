<?php
/**
 * @package Enterprise
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php do_action( 'before' ); ?>

<div id="header">
    <div class="header-left">
        <?php if (is_home()) { ?>
            <h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('title'); ?></a></h1>
        <?php } else { ?>
            <h4><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo('title'); ?></a></h4>
        <?php } ?>
        <p id="description"><?php bloginfo('description'); ?></p>
    </div>
    <div class="header-right">
        <form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" >
            <div><label class="hidden" for="s"><?php _e("Search:", 'enterprise'); ?></label>
            <input type="text" value="" name="s" id="s" />
            <input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Go', 'enterprise' ); ?>" /></div>
        </form>
    </div>
</div>
<div id="access">
	<div id="nav">
	    <div id="supernav" class="navleft nav">
			<?php wp_nav_menu( 'theme_location=primary' ); ?>
	    </div>
	    <div class="navright">
	        <a class="rsslink" rel="nofollow" href="<?php bloginfo('rss2_url'); ?>"><?php _e("Posts", 'enterprise'); ?></a>
	        <a class="rsslink" rel="nofollow" href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e("Comments", 'enterprise'); ?></a>
	    </div>
	</div>

	<div id="subnav" class="subnav nav">
		<?php wp_nav_menu( 'theme_location=secondary&fallback_cb=enterprise_secondary_cat_menu' ); ?>
	</div>
</div>

<div id="wrap">