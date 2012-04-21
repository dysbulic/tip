<?php
/**
 * The template for displaying Tag archive pages.
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title"><?php
					printf( __( 'Tag Archives: %s', 'matala' ), '<span>' . single_tag_title( '', false ) . '</span>' );
					?></h1>
			</header><!-- .archive-header -->

			<?php
				$tag_description = tag_description();
				if ( ! empty( $tag_description ) )
					echo '<div class="archive-meta">' . $tag_description . '</div>';
				get_template_part( 'loop', 'tag' );
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