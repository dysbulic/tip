<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
get_header();
?>

<div id="content">

	<?php if ( ! is_paged() ) : rewind_posts(); ?>
	<div id="homepagetop">
        
        <div class="textbanner">
			<?php $recent = new WP_Query("cat=" . st_option('hp_top_cat') . "&showposts=1"); while($recent->have_posts()) : $recent->the_post();?>
            <h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
        </div>
        
        <div class="featuredimage">
			<?php if( has_post_thumbnail() && ( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'structure-medium') ) && $image[1] >= '620' ): ?>
        	<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail( 'structure-medium' ); ?></a>
            <?php endif; ?>
            <?php endwhile; ?>
        </div>
        
        <div class="homewidgets">
        
            <ul>
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage Top Right') ) : ?>
            	<li class="widget"><p><?php _e( 'This is the home page widget area. You can use this space to add an introductory message to your blog with a Text Widget or add any other widget here.', 'structuretheme' ); ?></p></li>
            <?php endif; ?>
            </ul>
        
        </div>
        
    </div>
    <?php wp_reset_query(); endif; ?>

    <div id="homepage">
    
    	<?php get_sidebar( 'left' ); ?>
        
        <div class="homepagemid">

			<?php /* Display navigation to next/previous pages when applicable  */ ?>
			<?php if (  $wp_query->max_num_pages > 1 && is_paged() ) : ?>
							<div id="nav-above" class="navigation">
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
								<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
							</div><!-- #nav-below -->
			<?php endif; ?>                    
		
			<?php while(have_posts()) : the_post(); ?>
				
            	<div class="homepagethumb">
                	<?php if( has_post_thumbnail() && ( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'structure-small') ) && $image[1] >= '440' ): ?>
                	<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail( 'structure-small' ); ?></a>
                    <?php endif; ?>
                </div>               
                
            	<div class="homepagecontent">
                    
                    <h4><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                	<?php the_excerpt(); ?>
                	
                	<div class="postmeta">
                		<p><?php _e( 'Filed under', 'structuretheme' ); ?> <?php the_category( ', ' ); ?> <?php the_tags( '&middot; ' . __( 'Tagged with', 'structuretheme' ) . ' ' ); ?></p>
                	</div>                	
                
                </div>
                
            <?php endwhile; ?>
            
            <?php /* Display navigation to next/previous pages when applicable  */ ?>
            <?php if (  $wp_query->max_num_pages > 1 ) : ?>
            				<div id="nav-below" class="navigation">
            					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
            					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
            				</div><!-- #nav-below -->
            <?php endif; ?>            

        </div>
        
        <?php get_sidebar( 'right' ); ?>

	</div>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>