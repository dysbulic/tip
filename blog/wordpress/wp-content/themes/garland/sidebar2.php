<div id="sidebar-right" class="sidebar">

<ul class="menu">

<?php /* WordPress Widget Support */ if (function_exists('dynamic_sidebar') and dynamic_sidebar('right-sidebar')) { } else { ?>

<li class="widget widget_search">
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search' ); ?>" />
</div>
</form>
</li>

<?php wp_list_bookmarks( array( 'title_before' => '<h3>', 'title_after' => '</h3>', 'class' => 'widget widget_bookmarks linkcat' ) ); ?>

<li class="widget widget_archives">
<h3>Archives</h3>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>
</li>

<li class="widget widget_meta">
<h3>Misc</h3>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<li><a href="http://wordpress.org/">WordPress.org</a></li>
<li><a href="http://wordpress.com/">WordPress.com</a></li>
<?php wp_meta(); ?>
</ul>
</li>

<?php } ?>
</ul>
</div>
