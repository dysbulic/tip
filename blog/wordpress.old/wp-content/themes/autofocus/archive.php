<?php
/**
 * @package WordPress
 * @subpackage AutoFocus
 */
get_header(); ?>

	<div id="content">

		<?php if ( have_posts() ) : $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

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
			<?php /* If this is a category archive */ if ( is_category() ) { ?>
			<h2 class="archive-title">
				<?php printf( __( 'Category Archive: <em>%s</em>' , 'autofocus' ), single_cat_title( '', false) ); ?>
			</h2>
			<?php /* If this is a category archive */ } elseif ( is_tag() ) { ?>
			<h2 class="archive-title">
				<?php printf( __( 'Tag Archive: <em>%s</em>' , 'autofocus' ), single_tag_title( '', false) ); ?>
			</h2>
			<?php /* If this is a monthly archive */ } elseif ( is_year() ) { ?>
			<h2 class="archive-title">
				<?php printf( __( 'Yearly Archive: <em>%s</em>', 'autofocus' ), get_the_time( 'Y' ) ); ?>
			</h2>
			<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
			<h2 class="archive-title">
				<?php printf( __( 'Monthly Archive: <em>%s</em>', 'autofocus' ), get_the_time( 'F, Y' ) ); ?>
			</h2>
			<?php /* If this is a daily archive*/ } elseif ( is_day() ) { ?>
			<h2 class="archive-title">
				<?php printf( __( 'Daily Archive: <em>%s</em>', 'autofocus' ),get_the_time( 'F j, Y' ) ); ?>
			</h2>
			<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
			<h2 class="archive-title">
				<?php _e( 'Author Archive', 'autofocus' ); ?>
			</h2>
			<?php /* If this is a paged archive */ } elseif ( isset( $_GET[ 'paged' ] ) && ! empty( $_GET[ 'paged' ] ) ) { ?>
			<h2 class="archive-title">
				<?php _e( 'Blog Archive', 'autofocus' ); ?>
			</h2>
			<?php } ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class( 'featured featured-position-' . $wp_query->current_post ); ?>>
					<a class="featured-trigger" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to <em>%s</em>', 'autofocus' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
						<?php $has_featured_image = true;
							if ( '' != get_the_post_thumbnail() ) { ?>
								<div class="featured-thumbnail">
									<?php the_post_thumbnail( 'autofocus-600x1200' ); ?>
								</div><!-- .featured-thumbnail -->
						<?php } else {
							$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
							if ( $images ) {
								$image = array_shift( $images ); ?>
								<div class="featured-thumbnail">
									<?php echo wp_get_attachment_image( $image->ID, 'autofocus-600x1200' ); ?>
								</div><!-- .featured-thumbnail -->
							<?php } else {
								$has_featured_image = false; // titles and excerpts will be visible if a post has no featured image or image attachment
								echo '<div class="hide-trigger-background"></div>';
						} } ?>
						<div class="featured-date <?php if ( false === $has_featured_image ) echo 'no-featured-image'; ?>">
							<?php the_time( 'd M' ); ?>
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
				<div class="entry-meta">
					<?php
						if ( is_multi_author() ) {
							printf( __( '%1$s at %2$s <span class="archive-byline">By %3$s</span>', 'autofocus' ),
							get_the_time( get_option( 'date_format' ) ),
							get_the_time( get_option( 'time_format' ) ),
							esc_html( get_the_author() ) );
						} else {
							printf( __( '%1$s at %2$s', 'autofocus' ),
							get_the_time( get_option( 'date_format' ) ),
							get_the_time( get_option( 'time_format' ) ) );
						}
					?>
					<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
						<span class="comments-popup-link">
						<?php comments_popup_link( __( 'Leave a Reply', 'autofocus' ), _x( '1 Reply', 'comments number', 'autofocus' ), _x( '% Replies', 'comments number', 'autofocus' ), 'comments-link', __( 'Comments closed', 'autofocus' ) ); ?>
						</span>
					<?php endif; ?>
				</div><!-- .entry-meta -->

			<?php endwhile; ?>

		<?php else : ?>

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