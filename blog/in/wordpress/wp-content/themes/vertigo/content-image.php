<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<div class="photo-wrap">
			<?php
				$first_image = vertigo_image_grabber();
				if ( ! empty( $first_image ) )
					echo $first_image;
			?>
			<div class="frame"></div>
		</div>
		<div class="entry-content">
			<?php
				$content_wo_first_image = vertigo_content_wo_first_image();
				if ( ! empty( $content_wo_first_image ) )
					echo $content_wo_first_image;
			?>
		</div>

		<div class="entry-content clear-fix">
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->