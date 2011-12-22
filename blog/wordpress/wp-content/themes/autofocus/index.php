<?php
/**
* @package WordPress
* @subpackage AutoFocus
*/
get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<div id="nav-above">
			<h1 class="assistive-text">
				<?php _e( 'Post navigation', 'autofocus' ); ?>
			</h1><!-- .assistive-text-->
			<div class="nav-previous">
				<?php next_posts_link( __( '&laquo;', 'autofocus' ) ); ?>
			</div><!-- .nav-previous -->
			<div class="nav-next">
				<?php previous_posts_link( __( '&raquo;', 'autofocus' ) ); ?>
			</div><!-- .nav-next -->
		</div><!-- #nav-above -->

		<?php $position_counter = 0; // featured image widths are determined by their position in the grid ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class( 'featured featured-position-' . $position_counter ); ?>>
			<?php $has_featured_image = true; ?>
			<a class="featured-trigger" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'autofocus' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php if ( '' != get_the_post_thumbnail() ) { ?>
					<div class="featured-thumbnail">
						<?php the_post_thumbnail( 'autofocus-800x1200' ); ?>
					</div><!-- .featured-thumbnail -->
				<?php } else {
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) {
						$image = array_shift( $images ); ?>
						<div class="featured-thumbnail">
							<?php echo wp_get_attachment_image( $image->ID, 'autofocus-800x1200' ); ?>
						</div><!-- .featured-thumbnail -->
					<?php } else {
						$has_featured_image = false; // titles and excerpts will be visible if a post has no featured image or image attachment
						echo '<div class="hide-trigger-background"></div>';
				} } ?>
				<div class="featured-date <?php if ( false === $has_featured_image ) echo 'no-featured-image'; ?>">
					<?php if ( ( ! is_sticky() && ! is_paged() ) || is_paged() ) the_time( 'd M' ); ?>
				</div><!-- .entry-date -->
				<div class="featured-banner">
					<h2 class="featured-title">
						<span><?php the_title(); ?></span>
					</h2><!-- .featured-title -->
					<div class="featured-excerpt">
						<?php the_excerpt(); ?>
					</div><!-- .featured-excerpt-->
				</div><!-- .featured-banner-->
			</a>
		</div><!-- #post-<?php the_ID(); ?> -->

		<?php if ( ( ! is_sticky() && ! is_paged() ) || is_paged() ) $position_counter++; // stickies on the home page should not touch the position counter ?>

	<?php endwhile; else : ?>

		<div id="post-0" class="hentry">
			<h2 class="entry-title">
				<?php _e( 'Not Found', 'autofocus' ); ?>
			</h2><!-- .entry-title -->
			<div id="entry-content">
				<p>
					<?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'autofocus' ); ?>
				</p>
				<?php get_search_form(); ?>
			</div><!-- #entry-content -->
		</div><!-- #post-0 -->

	<?php endif; ?>

</div><!-- #content -->

<?php get_footer(); ?>