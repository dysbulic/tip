<?php
/**
 * The template for displaying Author archives.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					 *
					 * We reset this later so we can run the loop
					 * properly with a call to rewind_posts().
					 */
					the_post();
				?>

				<header class="archive-header">
					<h1 class="archive-title"><?php
						printf( __( 'Author Archives: %s', 'matala' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
					?></h1>
				</header><!-- .archive-header -->

			<?php
				/* Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
				rewind_posts();
			?>

			<?php // If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'matala' ), get_the_author() ) ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description -->
					</div><!-- # author-info -->
				<?php endif; ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Determine how to display posts depending on post format */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php matala_content_nav( 'nav-search' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Nothing Found', 'matala' ); ?></h1>
					</header><!-- .page-header -->

					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'matala' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

		</div><!-- #content -->

		<?php
			/* Two columns of sidebars.
			 */
			get_sidebar( 'supplementary' );
		?>
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>