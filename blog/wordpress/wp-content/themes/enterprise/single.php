<?php get_header(); ?>

<div id="content">

	<div id="content-left">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div id="nav-above">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'enterprise' ) . '</span> %title' ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'enterprise' ) . '</span>' ); ?></div>
			</div>

            <div <?php post_class(); ?>>

           		<div class="entry">

                    <h1><?php the_title(); ?></h1>

                    <div class="post-info">
                        <p><span class="time"><?php the_time( get_option( 'date_format' ) ); ?></span> <span class="author"><?php _e("by", 'enterprise'); ?> <?php the_author_posts_link(); ?></span> <span class="post-comments"><a href="<?php the_permalink(); ?>#respond"><?php comments_number(__('Leave a Comment', 'enterprise'), __('1 Comment', 'enterprise'), __('% Comments', 'enterprise')); ?></a></span> <?php edit_post_link(__('(Edit)', 'enterprise'), '', ''); ?></p>
                    </div>

                    <?php the_content(__('Read more', 'enterprise'));?><div class="clear"></div>
                    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

                    <!--
                    <?php trackback_rdf(); ?>
                    -->

                </div>

                <div class="post-meta">
                    <p>
                    	<span class="categories"><?php _e("Filed under", 'enterprise'); ?> <?php the_category(', ') ?></span>
                    	<?php the_tags( '<span class="tags">' . __( 'Tagged with', 'enterprise' ) . ' ', ', ', '</span>' ) ?>
                    </p>
                </div>

            </div>

			<?php if ( get_the_author_meta( 'description' ) != '' ) : ?>
            <div class="author-box">
                <p><?php echo get_avatar( get_the_author_email(), '70' ); ?><strong><?php _e("About", 'enterprise'); ?> <?php the_author(); ?></strong><br /><?php the_author_meta( 'description' ); ?></p>
            </div>
            <?php endif; ?>

            <?php endwhile; else: ?>

            <p><?php _e('Sorry, no posts matched your criteria.', 'enterprise'); ?></p><?php endif; ?>
            <p><?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'enterprise'), __('Next Page &raquo;', 'enterprise')); ?></p>

            <?php comments_template('',true); ?>

    </div>

<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>