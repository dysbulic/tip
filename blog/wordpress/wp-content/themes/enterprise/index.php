<?php
/**
 * @package WordPress
 * @subpackage Enterprise
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="content-left">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div <?php post_class(); ?>>

           		<div class="entry">

                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

                    <div class="post-info">
                        <p><span class="time"><?php the_time( get_option( 'date_format' ) ); ?></span> <span class="author"><?php _e("by", 'enterprise'); ?> <?php the_author_posts_link(); ?></span> <span class="post-comments"><a href="<?php the_permalink(); ?>#respond"><?php comments_number(__('Leave a Comment', 'enterprise'), __('1 Comment', 'enterprise'), __('% Comments', 'enterprise')); ?></a></span> <?php edit_post_link(__('(Edit)', 'enterprise'), '', ''); ?></p>
                    </div>

                    <?php the_content(__('Read more of this post', 'enterprise'));?><div class="clear"></div>

                </div>

                <div class="post-meta">
					<p>
						<span class="categories"><?php _e("Filed under", 'enterprise'); ?> <?php the_category(', ') ?></span>
						<?php the_tags( '<span class="tags">' . __( 'Tagged with', 'enterprise' ) . ' ', ', ', '</span>' ) ?>
					</p>
                </div>

            </div>

        <?php endwhile; else: ?>

        <p><?php _e('Sorry, no posts matched your criteria.', 'enterprise'); ?></p><?php endif; ?>

        <div class="navlink">
        	<div class="nav-previous"><p><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'enterprise' ) ); ?></p></div>
        	<div class="nav-next"><p><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'enterprise' ) ); ?></p></div>
        </div>


	</div>

<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>