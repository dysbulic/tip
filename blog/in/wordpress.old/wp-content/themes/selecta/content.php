<?php
/**
 * The portion of the loop that shows the "standard" post format.
 *
 * @package WordPress
 * @subpackage Selecta
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper clearfix' ); ?>>

	<?php if ( ! is_single() ) : ?>
		<div class="entry-header">
			<?php selecta_entry_date(); ?>
			<?php if ( get_the_title() != '' ) : ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
			<?php else: ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif; ?>
		</div><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-wrapper clearfix">
		<div class="entry">
			<?php the_content( __( 'Continue Reading <span class="meta-nav">&rarr;</span>', 'selecta' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "selecta" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
		</div><!-- .entry -->

		<div class="post-info clearfix">
			<p class="post-meta">
				<?php if( get_post_format() == 'aside' ) : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php _e( '#', 'selecta' ); ?></a>
			    <?php endif; ?>
				<?php selecta_post_meta(); ?>
				<?php edit_post_link( __( 'Edit this Entry', 'selecta' ), '<span class="edit-link">', '</span>' ); ?>
			</p>
			<p class="comment-link"><?php comments_popup_link( __( 'Leave a Comment', 'selecta' ), __( '1 Comment', 'selecta' ), __( '% Comments', 'selecta' ) ); ?></p>
		</div><!-- .post-info -->

		<?php if ( get_the_author_meta( 'description' ) && is_single() && get_post_format() == '' ) : // If a user has filled out their description, show a bio on their entries ?>
			<div id="author-info-box">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
				</div><!-- #author-avatar -->
				<div id="author-description">
					<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'selecta' ), get_the_author() ) ); ?></h2>
					<?php the_author_meta( 'description' ); ?>
				</div><!-- #author-description -->
				<div id="author-link">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'selecta' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link -->
			</div><!-- # author-info-box -->
		<?php endif; ?>

	</div><!-- .entry-wrapper -->
</div><!-- .post-wrapper -->