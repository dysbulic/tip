<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<h1 class="entry-title"><?php echo '<span>' . single_cat_title( '', false ) . '</span>'; ?></h1>
			<?php
			$category_description = category_description();
			if ( ! empty( $category_description ) )
				echo '<div class="archive-meta">' . $category_description . '</div>';
	
	            get_template_part( 'loop', 'category' );
			?>	
		</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>