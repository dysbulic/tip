<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<header class="entry-header">
			<div class="hand"></div>
			<h1 class="entry-title hitchcock">
				<?php
					$link_url = vertigo_url_grabber();
					if ( empty( $link_url ) )
						$link_url = get_permalink();
				?>
				<span>
					<a href="<?php echo esc_url( $link_url ); ?>" title="<?php printf( esc_attr__( 'Link to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</span>
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

		<?php vertigo_entry_meta(); ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->