<?php
/**
 * @package WordPress
 * @subpackage ChaosTheory
 */
?>
<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

			<?php the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-meta">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s', 'chaostheory' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<ul>
						<li class="entry-date"><?php printf( __( '%1$s &#8211; %2$s', 'chaostheory' ), the_date( '', '', '', false ), get_the_time() ); ?></li>
						<li class="entry-category"><?php printf( __( 'Posted in %s', 'chaostheory' ), get_the_category_list( ', ' ) ); ?></li>
						<?php the_tags( '<li class="entry-tags">' . __( 'Tagged' ) . ' ', ", ", "</li>"); ?>
						<?php edit_post_link( __( 'Edit', 'chaostheory' ), '<li class="entry-editlink">', '</li>'); ?>
					</ul>
				</div>
				<div class="entry-content">
					<?php the_content( '<span class="more-link">' . __( 'Read More &raquo;', 'chaostheory' ) . '</span>' ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages: ', 'chaostheory' ), 'after' => '</div>' ) ); ?>
				</div>
			</div><!-- .post -->

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '&laquo; %link' ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link &raquo;' ); ?></div>
			</div>

			<?php comments_template(); ?>

		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>