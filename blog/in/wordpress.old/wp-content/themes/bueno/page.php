<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
get_header();
?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">		
           
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
		<div class="post">

                    <h1 class="title"><?php the_title(); ?></h1>
                    
                    <div class="entry">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<p class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</p>' ) ); ?>
						<?php edit_post_link( __( 'Edit This', 'woothemes' ), '<p>', '</p>' ); ?>
                    </div>

                </div><!-- /.post -->
                
				<?php comments_template('', true); ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>
