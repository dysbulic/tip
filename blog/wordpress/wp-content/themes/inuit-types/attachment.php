<?php
/**
 * @package WordPress
 * @subpackage Inuit Types
 */
?>
<?php get_header(); ?>

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" class="single-post">

					<p class="page-title"><a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php printf( esc_attr__( 'Return to %s', 'twentyten' ), esc_html( get_the_title( $post->post_parent ), 1 ) ); ?>" rel="gallery">&larr; <?php echo get_the_title( $post->post_parent ); ?></a></p>


					<div id="header-about">

	                <h1><?php the_title(); ?> <?php edit_post_link('<span class="edit-entry">Edit this entry</span>'); ?></h1>

	                </div>

					<div class="date-comments">

				    <p class="fl">

					    <em><?php _e( 'Posted on ', 'it' ); ?> <?php the_time( get_option( "date_format" ) ); ?></em>

					    <?php _e( 'by ', 'it' ); ?><em><?php the_author_posts_link(); ?></em>

						<?php
						if ( wp_attachment_is_image() ) :
							$metadata = wp_get_attachment_metadata(); 
						?>
						&rarr; 
						
						<a title="<?php _e( 'Permalink to full-size image', 'it' ); ?>" href="<?php echo wp_get_attachment_url(); ?>">
							<?php echo $metadata['width'] . ' &times; ' . $metadata['height']; ?>
						</a>
						<?php endif; ?>
					</p>

				    <p class="fr"><span class="comments">

					    <?php if ( comments_open() ) : ?>

		                      <a href="#comments"><?php comments_number('0', '1', '%'); ?></a>

						<?php endif; ?>

					</span></p>

			        </div>

					<div class="clear"></div>

					<br/>

					<div class="entry">

<?php if ( wp_attachment_is_image() ) : ?>
						<p class="attachment"><a href="<?php echo theme_get_next_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							echo wp_get_attachment_image( $post->ID, array( 918, 918 ) ); // max $content_width wide or high.
						?></a></p>

						<?php if ( ! empty( $post->post_excerpt ) ) : ?>

						<p class="entry-caption"><?php the_excerpt(); ?></p>

						<?php endif; ?>

<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
<?php endif; ?>

						<?php the_content('<span class="read-on">Read On...</span>'); ?>

						<?php wp_link_pages( 'before=<p class="page-link">' . __( 'Pages:', 'it' ) . '&after=</p>' ); ?>

					</div>

<?php if ( wp_attachment_is_image() ) : ?>

						<div class="navigation attachment-navigation">

							<div class="nav-previous"><?php previous_image_link( false ); ?></div>

							<div class="nav-next"><?php next_image_link( false ); ?></div>

						</div><!-- .navigation -->

<?php endif; ?>

					<div class="fix"></div>

				</div>

	            <div class="fix"></div>

				<div id="comments">

					<?php comments_template(); ?>

				</div>

		<?php endwhile; ?>

	    <?php endif; ?>

</div><!-- Content -->

<?php get_footer(); ?>