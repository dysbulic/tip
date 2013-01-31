<?php // H1 is hidden, but exists for semantic value and spacing. ?>
<h1><?php _e('sidebar'); ?></h1>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

<?php // Comment this out if you will not be using Pages. ?>
<h2><?php _e('Other Stuff'); ?></h2>
<ul>
<?php wp_list_pages('title_li='); ?>
</ul>

<h2><?php _e('Friends'); ?></h2>
<ul>
<?php get_links(-1, "<li>", "</li>"); ?>
</ul>

<h2><?php _e('Archives'); ?></h2>
<ul>
<?php get_archives(); ?>
</ul>
<h2><?php _e('Categories'); ?></h2>
<ul>
<?php wp_list_cats('optionall=0&all=%20&sort_column=name'); ?>
</ul>

<?php if (function_exists('wp_theme_switcher')) { ?>
<h2><?php _e('Themes'); ?></h2>
<?php wp_theme_switcher(); } ?>

<?php wp_register('<p>', '</p>'); ?>
<p><?php wp_loginout(); ?></p>

<?php endif; ?>
