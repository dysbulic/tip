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
					
					<div id="header-about">
	
	                <h1><?php the_title(); ?> <?php edit_post_link('<span class="edit-entry">' . __( 'Edit this entry', 'it' ) . '</span>'); ?></h1>	                
	
	                </div>
										
					<div class="date-comments">
                    
				    <p class="fl">
						
						
					    <em><?php _e( 'Posted on ', 'it' ); ?><?php the_time( get_option( "date_format" ) ); ?></em> 			    
				    
					    <?php _e( 'by ', 'it' ); ?><em><?php the_author_posts_link(); ?></em>
					
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
					
	                	<?php if( get_option('inuitypes_single_post_image') && has_post_thumbnail() ): ?>									                           
							                           
							<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>"><?php the_post_thumbnail( 'one-column-feature' ); ?></a>
							
							<div class="fix"></div><br/>
						   
						<?php endif; ?> 

						<?php the_content('<span class="read-on">Read On...</span>'); ?>
						
						<?php wp_link_pages( 'before=<p class="page-link">' . __( 'Pages:', 'it' ) . '&after=</p>' ); ?>						

                        <?php the_tags('<div class="tags">' . __( 'Tagged: ', 'it' ) . '<em>', ', ', '</em></div>'); ?> 	
                        
                        <div class="categories"><?php _e( 'Posted in: ', 'it' ); ?><em><?php the_category(', ') ?></em></div>
										
					</div>

					<div id="nav-below" class="navigation">
					
						<div class="nav-previous"><?php previous_post_link( '%link',  _x( '&larr;', 'Previous post link', 'it' ). ' %title' ); ?></div>
						
						<div class="nav-next"><?php next_post_link( '%link', '%title ' . _x( '&rarr;', 'Next post link', 'it' ) ); ?></div>
						
					</div><!-- #nav-below -->					
					
					<div class="fix"></div>
																			
				</div>						
		
	            <div class="fix"></div>

				<div id="comments">
				
					<?php comments_template(); ?>
					
				</div>

		<?php endwhile; ?>
		
	    <?php endif; ?>		

</div><!-- Content -->		

<?php get_sidebar(); ?>

<?php get_footer(); ?>