<?php
/**
 * The template for displaying Category archive pages.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title"><?php
					printf( __( 'Category Archives: %s', 'matala' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></h1>
			</header><!-- .archive-header -->

			<?php
				$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo '<div class="archive-meta">' . $category_description . '</div>';
				get_template_part( 'loop', 'category' );
			?>

		</div><!-- #content -->

		<?php
			/* Two columns of sidebars.
			 */
			get_sidebar( 'supplementary' );
		?>
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>