<?php
/**
 * @package Vertigo
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">
		
		<div class="entry-content clear-fix">
			<?php
				if ( is_search() ) :
					the_excerpt();
				else :
					the_content( __( 'Read more', 'vertigo' ) );
				endif;
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
		</div>

		<div class="source">
			<div class="hand"></div>
			<?php the_title(); ?>
		</div>

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->