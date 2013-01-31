<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
get_header();
?>

<div id="content">

	<div id="contentleft">

        <div class="postarea">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            	<div class="posttitle">
            		<h3><?php the_title(); ?></h3>
           		</div>

            	<?php the_content(__('Read More'));?><div style="clear:both;"></div><?php edit_post_link('(Edit)', '', ''); ?>
				<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'theme' ) . '&after=</div>'); ?>

           </div>

           <?php endwhile; else: ?>

            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>

        </div>

		<div class="postcomments">
			<?php comments_template('',true); ?>
		</div>

	</div>

<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>