<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<header class="entry-header">
			<h1 class="entry-title hitchcock">
			<?php if ( ! is_singular() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
			</h1>
		</header><!-- .entry-header -->

		<div class="entry-content clear-fix">
			<?php
				if ( is_search() ) :
					the_excerpt();
				else :
					the_content( __( 'Read more', 'vertigo' ) );
				endif;
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
		</div><!-- .entry-content -->

		<div class="entry-meta">
			<?php edit_post_link( __( '(Edit)', 'vertigo' ), '<span class="edit-link">', '</span><br /><br />' ); ?>
			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'vertigo' ), __( '<b>1</b> Comment', 'vertigo' ), __( '<b>%</b> Comments', 'vertigo' ) ); ?></span>
			<?php endif; ?>
		</div><!-- .entry-meta -->

		<footer class="entry-info">
			<p class="permalink"><a href="<?php the_permalink(); ?>">*</a></p>
		</footer><!-- #entry-info -->

	</div><!-- .container -->
</article><!-- #post-## -->