<?php
/**
 * The template for displaying search results
 *
 * @package WordPress
 * @subpackage Selecta
 */
get_header(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<?php if ( have_posts() ) : ?>
			<h1 class="single-title"><?php printf( __( 'Search Results for &quot;%s&quot;', 'selecta' ), '<span class="alt-text">' . get_search_query() . '</span>' ); ?></h1>
		<?php else: ?>
			<h1 class="single-title"><?php _e( 'Nothing Found', 'selecta' ); ?></h1>
		<?php endif; ?>
	</div><!-- .single-title-wrap -->
</div><!-- #single-header -->

	<div id="main" class="clearfix">
		<div id="content" role="main">

			<?php if( have_posts() ) : ?>

			<ul class="archive-posts clearfix">
			<?php $count = 0; // Set up a variable to count the number of posts so that we can break them up into rows ?>

				<?php while ( have_posts() ) : the_post(); $count++; ?>

				<li <?php post_class( 'archive-post' ); ?>>
					<?php if( has_post_thumbnail() ) : ?>
						<div class="archive-post-wrapper">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>">
								<?php the_post_thumbnail( 'normal', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
							</a>
						</div><!-- .archive-post-wrapper -->
					<?php else : ?>
						<div class="archive-post-wrapper">
							<span><?php echo strip_shortcodes( strip_tags( selecta_short_excerpt( '150' ), '<a>' ) ); ?></span>
						</div><!-- .archive-post-wrapper -->
					<?php endif; ?>
					<h2 class="archive-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
					<?php selecta_entry_date(); ?>
				</li>
				<?php if( $count % 4 == 0 ) : // After every 4th post, end the row and start a new one. ?>
					</ul><!-- .archive-posts -->
					<ul class="archive-posts clearfix">
				<?php endif; ?>

			<?php endwhile; // end of the loop. ?>

			</ul><!-- .archive-posts -->

			<?php else : ?>
				<div id="post-0" class="post-wrapper clearfix error404 not-found">

					<div class="entry-wrapper clearfix">
						<div class="entry">
							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'selecta' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry -->
					</div><!-- .entry-wrapper -->

				</div><!-- #post-0 -->

			<?php endif; ?>

			<?php selecta_content_nav( 'nav-below' ); ?>

		</div><!-- #content -->

	</div><!-- #main -->

<?php get_footer(); ?>