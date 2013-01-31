<?php
/**
 * The template for displaying Category archive pages.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
		<h1 class="archive-title">
			<?php
				printf( __( 'Category Archives: %s', 'liquorice' ), '<span>' . single_cat_title( '', false ) . '</span>' );
		?></h1>
		<?php
			$category_description = category_description();
			if ( ! empty( $category_description ) )
				echo '<div class="archive-meta">' . $category_description . '</div>';
			get_template_part( 'loop', 'category' );
		?>
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>