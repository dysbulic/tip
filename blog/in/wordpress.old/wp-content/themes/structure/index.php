<?php get_header(); ?>

<div id="content">

	<div id="contentleft">

		<div class="postarea">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            	<div class="posttitle">

					<h3><?php the_title(); ?></h3>

                	<div class="postauthor">
                  	  <p><?php printf(__( 'Posted by <a href="%1$s">%2$s</a> on %3$s', 'structuretheme' ), get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author(), get_the_date() ); ?> &middot; <?php comments_popup_link( __( 'Leave a Comment' ), __( '1 Comment' ), __( '% Comments' ) ); ?>&nbsp;<?php edit_post_link( __( '(Edit)' ), '', '' ); ?></p>
               		 </div>

					<?php if( st_option('single_feature') && is_single() && has_post_thumbnail() && ( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'structure-large') ) && $image[1] >= '640' ): ?>
					<div class="feature-image">
						<?php the_post_thumbnail( 'structure-large' ); ?>
					</div>
					<?php endif; ?>

            	</div>

				<?php the_content(__('Read More'));?><div style="clear:both;"></div>
				<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'theme' ) . '&after=</div>'); ?>

				<div class="postmeta">
					<p><?php _e( 'Filed under', 'structuretheme' ); ?> <?php the_category( ', ' ); ?> <?php the_tags( '&middot; ' . __( 'Tagged with', 'structuretheme' ) . ' ' ); ?></p>
				</div>

			</div>

			<?php if ( is_single() ) : ?>
			<div id="nav-below" class="navigation single-navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> %title' ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&rarr;</span>' ); ?></div>
			</div><!-- #nav-below -->
			<?php endif; ?>

       	 	<div class="postcomments">
				<?php comments_template('',true); ?>
        	</div>

        </div>

		<?php endwhile; else: ?>
		<p><?php _e("Sorry, no posts matched your criteria.", 'structuretheme'); ?></p>
		<?php endif; ?>

	</div>

<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>