<?php add_filter( "the_content", "hem_about_block"); // resize images to fit this block ?>

<?php query_posts('pagename=about'); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<h2><?php _e('About','hemingway'); ?> <?php edit_post_link('Edit', '<small>(', ')</small>'); ?></h2>
<div class="about-content"><?php the_content(); ?></div>
<?php endwhile; ?>
<?php endif; ?>