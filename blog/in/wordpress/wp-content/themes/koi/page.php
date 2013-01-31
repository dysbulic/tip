<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
get_header();
$content_width = 642;
?>

	<div id="content">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div <?php post_class(); ?>>
			<h2 class="post-title"><?php the_title(); ?></h2>
			<?php the_content( __( 'More', 'ndesignthemes' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:' , 'ndesignthemes' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
			<?php edit_post_link( __( '[Edit]', 'ndesignthemes' ), '<p>', '</p>' ); ?>
		</div>
		<!-- /post -->

	<?php if ( comments_open() ) comments_template(); ?>

	<?php endwhile; endif; ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>