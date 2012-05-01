<?php
/**
 * The Template for displaying an attachment.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="post_content" class="column full-width first">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div class="column">
		
			<h1 class="post_name" id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
				
			<div class="post_meta">
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( 'Published on %1$s in <a href="%5$s" title="Return to %6$s" rel="gallery">%6$s</a> <span class="dot">&sdot;</span> Full size is <a href="%2$s" title="Link to full-size image">%3$s &times; %4$s</a> Pixels', 'woothemes' ),
						get_the_date(),
						wp_get_attachment_url(),
						$metadata['width'],
						$metadata['height'],
						get_permalink( $post->post_parent ),
						get_the_title( $post->post_parent )
					);
				?>				

					<?php edit_post_link( __( 'Edit', 'woothemes' ), ' <span class="dot">&sdot;</span> <span class="edit-link">', '</span>' );
				
				echo ' <span class="dot">&sdot;</span> '; ?>
				 
				<?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?>
			
			</div><!-- end .post_meta -->

			<div class="entry-content-wrapper">

				<div class="entry entry-content">
					
					<div class="entry-attachment">
						<?php
							$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
							foreach ( $attachments as $k => $attachment ) {
								if ( $attachment->ID == $post->ID )
									break;
							}
							$k++;
							// If there is more than 1 image attachment in a gallery
							if ( count( $attachments ) > 1 ) {
								if ( isset( $attachments[ $k ] ) )
									// get the URL of the next image attachment
									$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
								else
									// or get the URL of the first image attachment
									$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
							} else {
								// or, if there's only 1 image attachment, get the URL of the image
								$next_attachment_url = wp_get_attachment_url();
							}
						?>

						<p class="attachment">
							<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
								<?php echo wp_get_attachment_image( $post->ID, array( 750, 750 ) ); // max 750px wide or high. ?>
							</a>
						</p>

					</div><!-- end .entry-attachment -->
					
					<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>

					<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); ?>
					
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>

				</div><!-- end .entry-content -->
				
				<div id="nav-below" class="post-navigation clear-fix">
					<span class="nav-previous"><?php previous_image_link( false, __( '&laquo; Previous' , 'woothemes' ) ); ?></span>
					<span class="nav-next"><?php next_image_link( false, __( 'Next &raquo;' , 'woothemes' ) ); ?></span>
				</div>
				
				<div class="clear"></div>

			</div><!-- end .entry-content-wrapper -->
				
			<?php comments_template( '', true); ?>
		
		</div><!-- end .column -->
	
	<?php endwhile; else: ?>
		
		<?php
			printf( __( '<p>Lost? Go back to the <a href="%s">home page</a></p>', 'woothemes' ),
				get_home_url()
			);
		?>
	
	<?php endif; ?>

</div><!-- end #post_content -->
		
<?php get_footer(); ?>