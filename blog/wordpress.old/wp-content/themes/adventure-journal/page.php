<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<?php
			//Start the Loop
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'adventurejournal' ), ' ', '' ); ?>
					</div><!-- .entry-meta -->

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php if ( comments_open() || have_comments() || wp_link_pages() ) : ?>
					<div class="entry-utility">
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'adventurejournal' ), 'after' => '</div>' ) ); ?>
						<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'adventurejournal' ), __( '1 Comment', 'adventurejournal' ), __( '% Comments', 'adventurejournal' ) ); ?></span>
					</div>
					<?php endif; ?>
				</div>

				<?php comments_template( '', true ); ?>

			<?php endwhile; else: ?>
	        	<p><?php _e( 'Sorry, no posts matched your criteria.','adventurejournal' ); ?></p>
	        <?php endif; ?>

		</div><!-- #main-content -->

		<?php
		// For template-onecol.php which uses page.php
		if ( ! is_page_template('template-onecol.php' ) ) {
			get_sidebar();
		}
		?>
	</div><!-- #content -->

<?php get_footer(); ?>