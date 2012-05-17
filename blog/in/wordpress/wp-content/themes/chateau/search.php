<?php
/**
 * @package WordPress
 * @subpackage Chateau
 */
get_header();
?>
<div id="primary">
	<div id="content" class="clear-fix" role="main">
		<div class="more-posts">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php 
							printf( __( 'Search results for: <em>%s</em>', 'chateau' ), get_search_query() );
						?>
					</h1>
				</header>

				<?php
					$options = chateau_get_theme_options();
					if ( 'detail' == $options['archive_style'] ) {

						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;

					} else { // list style
				?>
						<div id="more-posts-inner">
							<?php
								// set a column number by checking the chosen layout
								$options = chateau_get_theme_options();
								$current_layout = $options['theme_layout'];
								$two_columns = array( 'content-sidebar', 'sidebar-content' );
								$no_columns = array( 'content' );
								if ( in_array( $current_layout, $two_columns ) )
									$col_size = 2; //2 items in a row
								elseif ( in_array( $current_layout, $no_columns ) )
									$col_size = 3; //3 items in a row

								$count = 0; //init item counter
								$column = 0; //init column counter
								$the_query = new WP_Query( $query_string );// set a new wp_query object
								$items_total = $the_query->post_count; //determine total number of items

								while ( $the_query->have_posts() ) : $the_query->the_post(); //loop!
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

								endwhile;
										print( '</div><!-- end clear-fix -->' );
							?>
						</div><!-- end more-posts-inner -->
				<?php } ?>

				<?php chateau_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<header class="page-header">
					<h1 class="page-title"><?php _e( 'No articles found.', 'chateau' ); ?></h1>
				</header>

			<?php endif; ?>

		</div><!-- end #more-posts -->
	</div><!-- end #content -->
</div><!-- end #primary -->
<?php get_sidebar(); ?>

<?php get_footer(); ?>