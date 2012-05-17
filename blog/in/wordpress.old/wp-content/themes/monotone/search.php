<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
get_header(); rewind_posts(); ?>

<div class="search">
	<?php if ( have_posts() ) : ?>
		<h2><?php printf( __( 'Search Results for: %s', 'monotone' ), '<span>' . get_search_query() . '</span>'); ?></h2>
		<div class="nav clearfix">
			<div class="prev"><?php next_posts_link( __( '&laquo; Older Entries', 'monotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'monotone' ) ); ?></div>
		</div>
		<ul class="thumbnails clearfix">
		<?php while ( have_posts() ) : the_post(); ?>
			<li id="post-<?php the_ID(); ?>">
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'monotone' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php monotone_the_thumbnail(); ?></a>
			</li>
		<?php endwhile; ?>
		</ul>
		<div class="nav clearfix">
			<div class="prev"><?php next_posts_link( __( '&laquo; Older Entries', 'monotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'monotone' ) ); ?></div>
		</div>
	<?php else : ?>
		<h2><?php _e( 'Not Found', 'monotone' ); ?></h2>
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'monotone' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>

<?php get_footer(); ?>