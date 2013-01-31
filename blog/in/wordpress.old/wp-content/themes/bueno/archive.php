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
            
				<?php if (is_category()) { ?>
                <span class="archive_header"><span class="fl cat"><?php _e('Archive', 'woothemes'); ?> | <?php single_cat_title(); ?></span> <span class="fr catrss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">RSS feed for this section</a>'; ?></span></span>        
            
                <?php } elseif (is_day()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time($GLOBALS['woodate']); ?></span>
    
                <?php } elseif (is_month()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('F, Y'); ?></span>
    
                <?php } elseif (is_year()) { ?>
                <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('Y'); ?></span>
    
                <?php } elseif (is_author()) { ?>
                <span class="archive_header"><?php _e('Archive by Author', 'woothemes'); ?></span>
    
                <?php } elseif (is_tag()) { ?>
                <span class="archive_header"><?php _e('Tag Archives:', 'woothemes'); ?> <?php single_tag_title(); ?></span>
                
                <?php } ?>
				
				<div class="fix"></div>
            
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <!-- Post Starts -->
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    
                    <a class="date" href="<?php the_permalink(); ?>">
                    	<span class="day"><?php the_time('j'); ?></span>
                    	<span class="month"><?php the_time('M'); ?></span>
                    </a>
                    
					<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
					<?php endif; ?>
                    
                    <div class="entry">
                    	<?php the_content(); ?>
                    </div>
                    
                    <div class="post-meta">
                    
                    	<ul <?php if ( ! is_multi_author() ) echo 'class="single-author-meta"';?>>
							<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                    		<li class="comments">
                    			<span class="head"><?php _e('Comments', 'woothemes') ?></span>
                    			<span class="body"><?php comments_popup_link(__('Leave a Comment', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                    		</li>
							<?php endif; ?>
                    		<li class="categories">
                    			<span class="head"><?php _e('Categories', 'woothemes') ?></span>
                    			<span class="body"><?php the_category(', ') ?></span>
                    		</li>
                    		<?php if ( is_multi_author() ) { ?>
	                    		<li class="author">
	                    			<span class="head"><?php _e('Author', 'woothemes') ?></span>
	                    			<span class="body"><?php the_author_posts_link(); ?></span>
	                    		</li>
                    		<?php } ?>
                    	</ul>
                    	
                    	<div class="fix"></div>
                    
                    </div><!-- /.post-meta -->

                </div><!-- /.post -->
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  
        
			<?php if (  $wp_query->max_num_pages > 1 ) : ?>        
			<div class="more_entries">
			    <div class="fl"><?php next_posts_link(__('&larr; Older Entries', 'woothemes')) ?></div>
				<div class="fr"><?php previous_posts_link(__('Newer Entries &rarr;', 'woothemes')) ?></div>
			    <br class="fix" />
			</div>		
			<?php endif; ?>
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>