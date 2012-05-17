<?php
/**
 * The Template for displaying Home page.
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header(); ?>
<div id="primary">
	<div id="content" class="clear-fix" role="main">
	
	<?php if ( have_posts() ) : ?>
	
		<?php 
			$options = chateau_get_theme_options();
			$sticky = get_option( 'sticky_posts' );
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$post_count = 0;
		?>
		
		<?php
			//let's show only sticky posts in detail format on the front page
			if ( ! is_paged() && ! empty ( $sticky ) ) :
				$args = array(
					'post__in' => $sticky
				);
				$the_query = new WP_Query( $args );
				while ( $the_query->have_posts() ) : $the_query->the_post();
					$post_count++;
					get_template_part( 'content', get_post_format() );
				endwhile; wp_reset_query();
			endif;
		?>
				
		<?php
			//let's show the latest post in detail format on the front page
			if ( ! is_paged() ) :
				$args = array(
					'post__not_in' => $sticky,
					'posts_per_page' => 1
				);
				$the_query = new WP_Query( $args );
				while ( $the_query->have_posts() ) : $the_query->the_post();
					$do_not_duplicate = $post->ID;
					$post_count++;
					get_template_part( 'content', get_post_format() );
				endwhile; wp_reset_query();
			endif;
		?>
		
		<?php
			//let's show the remainings
			if ( 'detail' == $options['archive_style'] ) :
				// Detail format without the latest post and sticky posts
				$args = array(
					'post__not_in' => $sticky,
					'paged'=> $paged
				);
				$the_query = new WP_Query( $args );
				while ( $the_query->have_posts() ) : $the_query->the_post();
					if( $post->ID == $do_not_duplicate ) continue;
					update_post_caches( $posts );
					get_template_part( 'content', get_post_format() );
				endwhile;
			
			else : // Concise format without the latest post and sticky posts 
		?>
		
				<!-- previous posts -->
				<div class="more-posts">
					<?php $count_posts = wp_count_posts();
						// check if there is more article to show here.
						if ( $count_posts->publish > $post_count ) {
					?>
						<h2 class="notice"><?php _e( 'Previous articles','chateau' );?></h2>
						<div id="more-posts-inner">
							<?php
								// set a column number by checking the chosen layout
								$current_layout = $options['theme_layout'];
								$two_columns = array( 'content-sidebar', 'sidebar-content' );
								$no_columns = array( 'content' );
								if ( in_array( $current_layout, $two_columns ) )
									$col_size = 2; //2 items in a row
								elseif ( in_array( $current_layout, $no_columns ) )
									$col_size = 3; //3 items in a row
								$count = 0; //init item counter
								$column = 0; //init column counter
		
								$args = array(
									'post__not_in' => $sticky,
									'paged'=> $paged
								);
								$the_query = new WP_Query( $args );
								$items_total = $the_query->post_count; //determine total number of items
		
								while ( $the_query->have_posts() ) : $the_query->the_post(); //loop!
									if( $post->ID == $do_not_duplicate ) continue;
									update_post_caches( $posts );
									//set $is_start_of_new_colum true when post count is dividable by the number of items
									$is_start_of_new_column = 0 === ( $count % $col_size );
									//set $is_end_of_column when both $count and $is_start_of_new_column are true
									$is_end_of_column = ( $count && $is_start_of_new_column );
									$count++; //update count counter
									$is_start_of_new_column && $column++; //update column counter
									if ( $is_end_of_column )
										print( '</div><!-- end clear-fix -->' );
									if ( $is_start_of_new_column )
										print( '<div class="clear-fix">' );
		
										get_template_part( 'content', 'list' );
		
								endwhile; wp_reset_query();
										print( '</div><!-- end clear-fix -->' );
							?>
						</div><!-- more-posts-inner -->
					<?php }else{ ?>
							<h2 class="notice"><?php _e( 'No previous posts yet','chateau' );?></h2>
					<?php } ; ?>
				</div><!-- more-posts -->
		<?php endif; ?>

	<?php else: ?>

		<article id="post-0">
			<header class="post-title">
				<h1><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'chateau' ); ?>.</h1>
			</header><!-- end .post-title -->
			<div class="post-content">
				<div class="post-extras">
				</div><!-- end .post-extras -->
				<div class="post-entry">
					<?php get_search_form(); ?>
				</div><!-- end .post-entry -->
			</div><!-- end .post-content -->
		</article><!-- #post-0 -->	
		
	<?php endif; ?>
	
	<?php chateau_content_nav( 'nav-below' ); ?>
		
	</div><!-- end #content -->
</div><!-- end #primary -->
<?php get_sidebar(); ?>

<?php get_footer(); ?>