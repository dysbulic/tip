<?php
/**
 * The template for displaying Category archive pages.
 *
 * @package WordPress
 * @subpackage Mystique
 */

get_header(); ?>

 			<div id="content-container">
	 			<div id="content">
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'mystique' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo '<div class="category-description">' . $category_description . '</div>';
						get_template_part( 'loop', 'category' ); ?>
				</div><!-- #content -->
			</div><!-- #content-container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>