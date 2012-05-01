<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
get_header();
?>
       
    <div id="content" class="col-full">
		<div id="main" class="fullwidth">
		
			<?php if ( get_option( 'woo_breadcrumbs' ) == 'true') { yoast_breadcrumb('<div id="breadcrumb"><p>','</p></div>'); } ?>
            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
            
                <div class="post">

                    <h1 class="title"><?php the_title(); ?></h1>
                    
                    <p class="date">
                    	<span class="day"><?php the_time('j'); ?></span>
                    	<span class="month"><?php the_time('M'); ?></span>
                    </p>
                    
                    <div class="entry">
                    	<p class="attachment"><a href="<?php echo theme_get_next_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
                    		echo wp_get_attachment_image( $post->ID, array( 810, 9999 ) ); // max $content_width wide or high.
                    	?></a></p>
                    	<?php if ( ! empty( $post->post_excerpt ) ) : ?>
                    	<p class="entry-caption"><?php the_excerpt(); ?></p>
                    	<?php endif; ?>                    	
						<?php the_content( __( 'Continue&nbsp;reading&nbsp;<span class="meta-nav">&rarr;</span>', 'woothemes' ) ); ?>
						<?php wp_link_pages( array( 'before' => '<p class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</p>' ) ); ?>
						<?php edit_post_link( __( 'Edit This', 'woothemes' ), '<p>', '</p>' ); ?>
                    </div>
                    
                    <div class="post-meta">
                    
                    	<ul>
                    		<li class="comments">
                    			<span class="head"><?php _e('Comments', 'woothemes') ?></span>
                    			<span class="body"><?php comments_popup_link(__('Leave a Comment', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                    		</li>
                    		<li class="author">
                    			<span class="head"><?php _e('Author', 'woothemes') ?></span>
                    			<span class="body"><?php the_author_posts_link(); ?></span>
                    		</li>
							<li class="parent">
								<span class="head"><?php _e('Posted in', 'woothemes') ?></span>
								<span class="body"><a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php printf( esc_attr__( 'Return to %s', 'twentyten' ), esc_html( get_the_title( $post->post_parent ), 1 ) ); ?>" rel="gallery"><?php echo get_the_title( $post->post_parent ); ?></a>
								</span>
							</li>                    									
							<li class="imagelink">
								<span class="head"><?php _e('Full-size', 'woothemes') ?></span>
								<span class="body"><a title="<?php _e( 'Permalink to full-size image', 'woothemes' ); ?>" href="<?php echo wp_get_attachment_url(); ?>"><?php $size = getimagesize( wp_get_attachment_url() ); echo $size[0] . ' &times; ' . $size[1]; ?></a>
								</span>
							</li>                    		
                    	</ul>              	                    	
                    	
                    	<div class="fix"></div>                   	
                   
                    </div><!-- /.post-meta -->
                    
					<div id="nav-below" class="navigation">
						<div class="nav-previous"><?php previous_image_link( false ); ?></div>
						<div class="nav-next"><?php next_image_link( false ); ?></div>
					</div><!-- #nav-below -->                                    	                    

                </div><!-- /.post -->                
                
				<?php comments_template('', true); ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
  				</div><!-- /.post -->             
           	<?php endif; ?>  
        
		</div><!-- /#main -->

    </div><!-- /#content -->
		
<?php get_footer(); ?>