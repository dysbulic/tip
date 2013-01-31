<?php // This template is used when no article was found. ?>
<?php get_header(); ?>

<h1><?php _e("Nothing was Found", 'fresh-bananas'); ?></h1>

<p><?php _e("I'm sorry to report that no article was found under that address. Perhaps you would like to try searching now?", 'fresh-bananas'); ?></p>

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" length="20" />
<input type="submit" id="searchsubmit" name="Submit" value="<?php esc_attr_e('Search', 'fresh-bananas' ); ?>" />
</form>

<?php get_footer(); ?>
