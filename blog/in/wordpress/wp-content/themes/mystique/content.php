<?php
/**
 * The portion of the loop that shows the "standard" post format.
 *
 * @package WordPress
 * @subpackage Mystique
 */
?>
<div <?php post_class( 'post-wrapper clear-block' ); ?>>

	<?php if ( has_post_thumbnail() && ! is_single() ) : ?>
	<div class="thumbnail-container">
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>">
			<?php the_post_thumbnail( 'normal', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
		</a>
	</div><!-- .thumbnail-container -->
	<?php endif; ?>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="post-date">
		<p class="day"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'mystique' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_time( __( 'M j', 'mystique' ) ); ?></a></p>
	</div><!-- .post-date -->

	<div class="post-info clear-block">
		<p class="author alignleft"><?php _e( 'Posted by', 'mystique' ); ?> <?php the_author_posts_link(); ?></p>
	</div><!-- .post-info clear-block" -->

	<div class="entry clear-block">
		<?php the_content( __( 'Read the rest of this entry <span class="meta-nav">&rarr;</span>', 'mystique' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "mystique" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
	</div><!-- .entry -->

	<?php if ( ! is_single() ) : ?>
		<div class="post-meta">
			<p class="post-categories">
				<?php printf( __( 'Posted in %s', 'mystique' ), get_the_category_list( ', ' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'mystique' ), '<span class="edit-link"> &#124; ', '</span>' ); ?>
			</p>
			<p class="comment-link alignright"><?php comments_popup_link( __( 'Leave a Comment', 'mystique' ), __( '1 Comment', 'mystique' ), __( '% Comments', 'mystique' ) ); ?></p>
			<?php the_tags( '<p class="post-tags">' . __( 'Tags:', 'mystique' ) . ' ', ', ', '</p>' ); ?>
		</div><!-- .post-meta -->
	<?php endif; ?>

	<?php if ( get_the_author_meta( 'description' ) && is_single() && get_post_format() == '' ) : // If a user has filled out their description, show a bio on their entries ?>
		<div id="author-info-box">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'mystique' ), get_the_author() ) ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
			</div><!-- #author-description -->
			<div id="author-link">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'mystique' ), get_the_author() ); ?>
				</a>
			</div><!-- #author-link -->
		</div><!-- # author-info-box -->
	<?php endif; ?>

</div><!-- .post-wrapper -->