<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content" class="searchresults">
	<div class="searchpanel">
		<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
			<div id="search">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'manifest' ); ?>" />
			</div>
		</form>
	</div>

	<h2><?php _e( 'Search Results', 'manifest' ); ?></h2>

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

	<div class="post hentry">
		<div class="post-content">
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div>
		</div>
		<div class="post-meta">
			<?php
				$arc_year = get_the_time( 'Y' );
				$arc_month = get_the_time( 'm' );
				$arc_day = get_the_time( 'd' );
			?>

			<div class="post-date"><span><?php _e( 'Published:', 'manifest' ); ?></span> <abbr class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><a href="<?php echo get_day_link("$arc_year", "$arc_month", "$arc_day"); ?>"><?php the_time( 'F j, Y' ); ?></a></abbr></div>
				<div class="categories"><span><?php _e( 'Filed Under:', 'manifest' ); ?></span> <?php the_category( ', ' ); ?></div>
					<?php the_tags( '<span>' . __( 'Tags:', 'manifest' ) . '</span> ', ' : ', '' ); ?>
				</div>
		</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="prev"><?php next_posts_link( __( '&laquo; Older', 'manifest' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer &raquo;', 'manifest' ) ); ?></div>
		</div>

	<?php else : ?>

	<h2><?php _e( 'Not Found', 'manifest' ); ?></h2>
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'manifest' ); ?></p>

	<?php endif; ?>

</div><!-- #core-content -->

<?php get_footer(); ?>