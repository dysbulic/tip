<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
<?php get_header(); ?>

	<div id="content" class="col-full">
		<div id="main" class="col-left">

		<?php if ( have_posts() ) : ?>
			<?php if ( is_category() ) : ?>
				<span class="archive_header"><span class="cat"><?php _e( 'Archive', 'woothemes' ); ?> | <?php echo single_cat_title(); ?></span>
					<span class="fr catrss">
						<?php
							$cat_obj = $wp_query->get_queried_object();
							$cat_id = $cat_obj->cat_ID;
							echo '<a href="' . get_category_feed_link( $cat_id, '' ) . '">';
							_e( 'RSS for this section', 'woothemes' );
							echo '</a>';
						?>
					</span>
				</span>
			<?php elseif ( is_day() ) : ?>
				<span class="archive_header"><?php printf( __( 'Archive | %s', 'woothemes' ), get_the_date() ); ?>
			<?php elseif ( is_month() ) : ?>
				<span class="archive_header"><?php printf( __( 'Archive | %s', 'woothemes' ), get_the_date( 'F Y' ) ); ?></span>
			<?php elseif ( is_year() ) : ?>
				<span class="archive_header"><?php printf( __( 'Archive | %s', 'woothemes' ), get_the_date( 'Y' ) ); ?></span>
			<?php elseif ( is_author() ) : ?>
				<span class="archive_header"><?php printf( __( 'Archive by Author | %s', 'woothemes' ), get_the_author() ); ?></span>
			<?php elseif ( is_tag() ) : ?>
				<span class="archive_header"><?php printf( __( 'Tag Archive | %s', 'woothemes' ), single_tag_title( '', false ) ); ?></span>
			<?php elseif ( is_search() ) : ?>
				<span class="archive_header"><?php printf( __( 'Search results for %s', 'woothemes' ), get_search_query() ); ?></span>
			<?php endif; ?>
			</span>
			<div class="fix"></div>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

			<?php endwhile; ?>

		<?php else : ?>

			<div class="post not-found">
				<h2 class="title"><?php _e( 'Error 404 - Page not found!', 'woothemes' ); ?></h2>
				<p><?php _e( 'The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'woothemes' ); ?></p>
			</div><!-- /.post -->

		<?php endif; ?>

		<?php skeptical_pagenav(); ?>

		</div><!-- /#main -->

		<?php get_sidebar(); ?>

	</div><!-- /#content -->

<?php get_footer(); ?>