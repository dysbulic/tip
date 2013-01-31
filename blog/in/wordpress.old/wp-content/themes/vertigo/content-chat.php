<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<header class="entry-header">
			<h1 class="entry-title hitchcock">
			<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
			</h1>
		</header><!-- .entry-header -->

		<div class="entry-content clear-fix">
			<?php the_content( __( 'Read more', 'vertigo' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->