<?php
/**
 * The portion of the loop that shows the "standard" post format.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>
<div <?php post_class( 'post-wrapper' ); ?>>

	<?php if ( ! is_single() && get_the_title() != '' ) : ?>
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'liquorice' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<?php else: ?>
		<h1 class="single-title"><?php the_title(); ?></h1>
	<?php endif; ?>

	<div class="date">
		<small><?php liquorice_posted_on(); ?></small>
	</div><!-- .date -->

	<div class="entry">
		<?php if( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'liquorice' ), the_title_attribute( 'echo=0' ) ) ); ?>">
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
			</a>
		<?php endif; ?>
		<?php the_content( __( 'Read the rest of this entry <span class="meta-nav">&rarr;</span>', 'liquorice' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "liquorice" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
		<?php edit_post_link( __( '(Edit)', 'liquorice' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry -->

	<div class="post-meta">
		<p class="comments-num"><?php comments_popup_link( __( 'Leave a comment', 'liquorice' ), __( '1 Comment', 'liquorice' ), __( '% Comments', 'liquorice' ) ); ?></p>
		<?php liquorice_posted_in(); ?>
	</div><!-- .meta -->

	<?php if ( get_the_author_meta( 'description' ) && is_single() && get_post_format() == '' ) : // If a user has filled out their description, show a bio on their entries  ?>
		<div id="author-info-box">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'liquorice' ), get_the_author() ) ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
			</div><!-- #author-description -->
			<div id="author-link">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'liquorice' ), get_the_author() ); ?>
				</a>
			</div><!-- #author-link -->
		</div><!-- # author-info-box -->
	<?php endif; ?>

</div><!-- .post-wrapper -->