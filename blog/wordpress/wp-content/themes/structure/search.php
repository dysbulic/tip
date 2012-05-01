<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
get_header();
?>

<div id="content">

	<div id="contentleft">
    
		<div class="postarea">
	
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
            
                <div class="postauthor">
            
            		<p><?php printf(__( 'Posted by <a href="%1$s">%2$s</a> on %3$s', 'structuretheme' ), get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author(), get_the_time( 'F j, Y' ) ); ?> &middot; <?php comments_popup_link( __( 'Leave a Comment' ), __( '1 Comment' ), __( '% Comments' ) ); ?>&nbsp;<?php edit_post_link( __( '(Edit)' ), '', '' ); ?></p>
                    
                </div>
           
            <?php the_excerpt(); ?><div style="clear:both;"></div>
            
			<div class="postmeta">
				<p><?php _e( 'Filed under', 'structuretheme' ); ?> <?php the_category( ', ' ); ?> <?php the_tags( '&middot; ' . __( 'Tagged with', 'structuretheme' ) . ' ' ); ?></p>
			</div>
            		
			<?php endwhile; else: ?>
                    
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>

<?php /* Display navigation to next/previous pages when applicable  */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
        
        </div>
	
	</div>
			
	<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>