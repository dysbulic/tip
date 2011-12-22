<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content">

	<?php while ( have_posts() ) : the_post(); ?>

	<div class="post hentry single" id="post-<?php the_ID(); ?>">
		<div class="post-content">
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<div class="entry-content">
				<?php the_content( '<p class="serif">' . __( 'Read the rest of this entry &raquo;', 'manifest' ) . '</p>' ); ?>
			</div>
		</div>
 	</div>

 	<?php comments_template(); ?>

	<?php endwhile; ?>

	<?php edit_post_link( __( 'Edit this entry.', 'manifest' ), '<p>', '</p>' ); ?>

</div><!-- #core-content -->

<?php get_footer(); ?>