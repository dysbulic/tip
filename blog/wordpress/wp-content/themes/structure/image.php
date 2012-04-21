<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
?>

<?php get_header(); ?>

<div id="content">

	<div id="contentwide">
    
        <div class="postarea">
			         
          	<div class="posttitle">
            	<h3><?php the_title(); ?></h3>
            </div>

            <div class="postauthor">            
                <p><a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php printf( esc_attr__( 'Return to %s', 'structuretheme' ), get_the_title( $post->post_parent ) ); ?>" rel="gallery">&larr; <?php echo get_the_title($post->post_parent); ?></a> &middot; <?php printf(__( 'Posted on %1$s', 'structuretheme' ), get_the_time( 'F j, Y' ) ); ?> &middot; <a href="<?php the_permalink(); ?>#comments"><?php comments_number('Leave a Comment', '1 Comment', '% Comments'); ?></a> &middot; <a title="<?php _e( 'Permalink to full-size image', 'structuretheme' ); ?>" href="<?php echo wp_get_attachment_url(); ?>"><?php $size = getimagesize( wp_get_attachment_url() ); echo $size[0] . ' &times; ' . $size[1]; ?></a>
                
                	<?php edit_post_link( __( 'Edit', 'structuretheme' ), '&middot; ', '' ); ?>
                </p>
            </div>
            
			<div class="entry-attachment">
				<p class="attachment">
					<a href="<?php echo theme_get_next_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
						<?php echo wp_get_attachment_image( $post->ID, array( 960, 960 ) );?>
					</a>
				</p>

				<?php if ( !empty($post->post_excerpt) ) : ?>
				<?php the_excerpt(); ?>
				<?php endif; ?>
				
				<?php echo apply_filters('the_content', $post->post_content); ?>	
				
				<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'structuretheme' ) . '&after=</div>' ); ?>

				<div id="nav-below" class="navigation attachment-navigation">
					<div class="nav-previous"><?php previous_image_link( false ); ?></div> 
					<div class="nav-next"><?php next_image_link( false ); ?></div>
				</div><!-- #nav-below -->
				
			</div><!-- .entry-attachment -->
			
			
			<div style="clear: both;"></div>
            
        </div>

		<div class="postcomments">
			<?php comments_template('',true); ?>
		</div>        
		
	</div>
			
</div>

<!-- The main column ends  -->

<?php get_footer(); ?>