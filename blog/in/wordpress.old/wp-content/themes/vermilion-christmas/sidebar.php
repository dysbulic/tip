
<!-- BEGIN SIDEBAR.PHP -->

<div id="sidebar">
<ul>
<?php if ( !function_exists( 'dynamic_sidebar' )
        || !dynamic_sidebar() ) : ?>

<!-- begin pages -->
<li><h3><?php _e( 'Pages', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<li><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'name' ); ?>"><?php _e( 'Blog Home', 'vermilionchristmas' ); ?></a></li>
<?php wp_list_pages( 'title_li=' ); ?>
</ul>
</li>
<!-- end pages -->

<!-- begin categories -->
<li><h3><?php _e( 'Categories', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<?php wp_list_cats( 'sort_column=name&optioncount=0&hierarchical=1' ); ?>
</ul>
</li>
<!-- end categories -->

<!-- begin search -->
<li><h3><?php _e( 'Search', 'vermilionchristmas' ); ?></h3>
<div class="space">
<?php get_search_form(); ?>
</div>
</li>
<!-- end search -->

<!-- begin calendar -->
<li id="calendar">
<?php get_calendar(); ?>

</li>
<!-- end calendar -->

<!-- begin recent posts -->
<li><h3><?php _e( 'Recent Posts', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<?php get_archives( 'postbypost','7','custom','<li>','</li>' ); ?>
</ul>
</li>
<!-- end recent posts -->

<!-- begin archives -->
<li><h3><?php _e( 'Archives', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<?php wp_get_archives( 'type=monthly' ); ?>
</ul>
</li>
<!-- end archives -->

<!-- begin links -->
<li><h3><?php _e( 'Links', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<?php get_links( '-1', '<li>', '</li>', '', 0, 'name', 0, 0, -1, 0); ?>
</ul>
</li>
<!-- end links -->

<!-- begin feeds -->
<li id="feeds"><h3><?php _e( 'Feeds', 'vermilionchristmas' ); ?></h3>
<ul class="space">
<li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS 2 Posts', 'vermilionchristmas' ); ?></a></li>
<li><a href="<?php bloginfo( 'comments_rss2_url' ); ?>"><?php _e( 'RSS 2 Comments', 'vermilionchristmas' ); ?></a></li>
</ul>
</li>
<!-- end feeds -->

<?php endif; ?>
</ul>
</div><!-- end id="sidebar" -->