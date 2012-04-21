<?php
/**
 * The portion of the loop that shows the "standard" post format.
 *
 * @package WordPress
 * @subpackage Matala
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper' ); ?>>

	<?php matala_post_date(); ?>

	<header class="entry-header">
		<?php if ( ! is_single() && get_the_title() != '' ) : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>

		<div class="entry-info">
			<?php matala_posted_on(); ?>
		</div><!-- .entry-info -->

	</header><!-- .entry-header -->
		<div class="post-format-icon"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'matala' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></div>

	<div class="entry-content">
		<?php if( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'matala' ), the_title_attribute( 'echo=0' ) ) ); ?>">
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
			</a>
		<?php endif; ?>
		<?php the_content( __( 'Read the rest of this entry <span class="meta-nav">&rarr;</span>', 'matala' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'matala' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

	<footer class="entry-footer">

		<div class="entry-meta">
			<?php matala_posted_in(); ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'matala' ), __( '1 Comment', 'matala' ), __( '% Comments', 'matala' ) ); ?></span>
			<?php edit_post_link( __( 'Edit', 'matala' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

		<?php if ( get_the_author_meta( 'description' ) && is_single() && '' == get_post_format() ) : // If a user has filled out their description, show a bio on their entries  ?>
			<div id="author-info">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
				</div><!-- #author-avatar -->
				<div id="author-description">
					<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'matala' ), get_the_author() ) ); ?></h2>
					<?php the_author_meta( 'description' ); ?>
				</div><!-- #author-description -->
				<div id="author-link">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'matala' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link -->
			</div><!-- # author-info -->
		<?php endif; ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> .post-wrapper -->