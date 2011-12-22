<?php
/**
 * @package Enterprise
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="content-left" class="image-attachment">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div <?php post_class(); ?>>

           		<div class="entry">

                    <div class="post-info">
                        <?php
                        	$size = getimagesize( wp_get_attachment_url() );
                        	printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span>  at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'enterprise'),
                        		/* %1$s */ esc_attr( get_the_time() ),
                        		/* %2$s */ get_the_date(),
                        		/* %3$s */ wp_get_attachment_url(),
                        		/* %4$s */ $size[0],
                        		/* %5$s */ $size[1],
                        		/* %6$s */ get_permalink( $post->post_parent ),
                        		/* %7$s */ get_the_title( $post->post_parent )
                        	);
                        ?>
                        <?php edit_post_link( __( '(Edit)', 'enterprise' ) ); ?>
                        </p>
                    </div>

					<div class="image-navigation">
						<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous' , 'enterprise' ) ); ?></span>
						<span class="next-image"><?php next_image_link( false, __( 'Next &rarr;' , 'enterprise' ) ); ?></span>
					</div><!-- .image-navigation -->

					<div class="entry-attachment">
						<div class="attachment">
							<a href="<?php echo theme_get_next_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							echo wp_get_attachment_image( $post->ID, array( 890, 890 ) );
							?></a>
						</div>
					</div>

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<div class="entry-caption">
						<?php the_excerpt(); ?>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $post->post_content ) ) : ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>

                    <?php the_content(__('Read more', 'enterprise'));?><div class="clear"></div>
                    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                    <?php endif; ?>

                    <!--
                    <?php trackback_rdf(); ?>
                    -->

                </div>

            </div>

            <?php endwhile; else: ?>

            <p><?php _e('Sorry, no posts matched your criteria.', 'enterprise'); ?></p><?php endif; ?>
            <p><?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page', 'enterprise'), __('Next Page &raquo;', 'enterprise')); ?></p>

            <?php comments_template('',true); ?>

    </div>

</div>

<?php get_footer(); ?>