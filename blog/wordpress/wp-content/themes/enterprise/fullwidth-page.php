<?php
/**
 * Template Name: Full-width, no sidebar
 *
 * @package Enterprise
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="content-left" class="full-width">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div <?php post_class(); ?>>

                <div class="entry">
                	<h1><?php the_title(); ?></h1>
                	<?php the_content(__('Read more', 'enterprise'));?><div class="clear"></div>
                	<?php edit_post_link(__('(Edit)', 'enterprise'), '', ''); ?><div class="clear"></div>
                </div>

            </div>

        <?php endwhile; else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.', 'enterprise'); ?></p><?php endif; ?>

        <?php if ( comments_open() ) : // If comments are open, but there are no comments ?>
         <div id="comments">
            <?php comments_template('',true); ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php get_footer(); ?>