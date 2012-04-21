<?php
/**
 * @package Vertigo
 */
?>
<?php
	// Access global variable directly to set content_width
	if ( isset( $GLOBALS['content_width'] ) )
		$GLOBALS['content_width'] = 400;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<div class="entry-content clear-fix">
			<div class="video-holder">
				<div class="projector hidden"></div>
				<?php the_content( __( 'Read more', 'vertigo' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
			</div>
		</div><!-- .entry-content -->

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->