<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>

<?php get_header(); ?>

<div id="content" role="main">
	<?php if ( have_posts() ) : ?>

		<header class="archive-title clear">
			<hgroup>
				<div class="archive-title-meta"><h2><?php _e( 'Archive', 'blogum' ); ?></h2></div>
				<div class="archive-title-name">
					<h1>
					<?php
						if ( is_category() ) :
							printf( __( '%s', 'blogum' ), single_cat_title( '', false ) );
						elseif ( is_tag() ) :
							printf( __( 'Tag Archives: %s', 'blogum' ), single_tag_title( '', false ) );
						elseif ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'blogum' ), get_the_date() );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'blogum' ), get_the_date( 'F Y' ) );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'blogum' ), get_the_date( 'Y' ) );
						elseif ( is_author() ) :
							printf( __( 'Author Archives: %s', 'blogum' ), get_the_author() );
						else :
							_e( 'Blog Archives', 'blogum' );
						endif;
					?>
					</h1>
				</div>
			</hgroup>
		</header>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php blogum_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="post-meta"><h1><?php _e( 'Nothing Found', 'blogum' ); ?></h1></header>
			<div class="post-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'blogum' ); ?></p>
			</div>
		</archive>

	<?php endif; ?>

</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>