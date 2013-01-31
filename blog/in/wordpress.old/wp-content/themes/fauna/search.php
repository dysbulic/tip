<?php get_header(); ?>

	<div id="body">

		<div id="main" class="entry">
			<div class="box">
				<h2><?php _e('Search') ?></h2>
				<form method="get" action="<?php echo home_url( '/' ); ?>">
				<input name="s" type="text" class="searchbox" value="<?php the_search_query(); ?>" />
				<input type="submit" value="<?php esc_attr_e( 'Search' ); ?>" class="pushbutton" />
				</form>
								
			</div>

			<div class="box">
				<h2><?php _e('Search Results') ?></h2>
				<p><?php _e('These are the search results for your search on') ?> <strong>"<?php the_search_query(); ?>"</strong>:</p>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div <?php post_class(); ?>>

				<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
				<div class="search-results"><?php the_excerpt() ?></div>
				<div class="highlight">
					<?php the_permalink() ?><?php edit_post_link('Edit This', ' &#8212; '); ?>
				</div>

				</div>
			
			<?php endwhile; ?>
			</div>

			<?php include("nav.php"); ?>

			<?php else : ?>
	
				<h2>Not Found</h2>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
				
			</div>
			
			<?php endif; ?>

			<hr />
			
		</div><!--//#main-->

		<?php get_sidebar(); ?>
		
	</div>

	<?php get_footer(); ?>
