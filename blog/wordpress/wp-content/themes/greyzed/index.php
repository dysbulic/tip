<?php
/**
 * @package Greyzed
 */
get_header(); ?>
<div id="container">

<?php get_sidebar(); ?>

	<div id="content" role="main">
		<div class="column">

		<?php $postcount = "0"; // reset post counter ?>

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php $postcount++; // post counter ?>

				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					<div class="posttitle">
						<h2 class="pagetitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to ', 'greyzed' ); the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<small>
							<?php
								if ( is_multi_author() ) {
									printf( __( 'Posted: %1$s by <strong>%2$s</strong> in %3$s', 'greyzed' ),
										get_the_date( get_option( 'date_format' ) ),
										get_the_author(),
										get_the_category_list( ', ' )
									);
								} else {
									printf( __( 'Posted: %1$s in %2$s', 'greyzed' ),
										get_the_date( get_option( 'date_format' ) ),
										get_the_category_list( ', ' )
									);
								}
							?>
							<br />
							<?php the_tags( __( 'Tags: ', 'greyzed' ), ', ', ''); ?>
						</small>
					</div>
					<?php if ( ( comments_open() ) && ( ! post_password_required() ) ) : ?>
					<div class="postcomments"><?php comments_popup_link( '0', '1', '%' ); ?></div>
					<?php endif; ?>
					<div class="entry">
						<?php the_content( __( 'Read the rest of this entry &raquo;', 'greyzed') ); ?>
					</div>
						<?php wp_link_pages(array('before' => '<p><strong>'. __( 'Pages:', 'greyzed') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<?php edit_post_link( __( 'Edit this entry.', 'greyzed' ), '<p>', '</p>' ); ?>
				</div>

			<?php endwhile; ?>

		<?php
		// Find page with last post
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$postsppage= get_option('posts_per_page');
		$total = $paged * $postsppage;
		$remainder = $total - $wp_query->found_posts;
		$endvar =  $postsppage - $remainder;
		?>

	</div>

	<div id="nav-post">
		<div class="navigation-bott">
			<?php if ( $endvar == 0 || $postcount == $endvar ) { } else { ?>
			<div class="leftnav"><?php next_posts_link( __( 'Older Entries', 'greyzed' ) ); ?></div>
			<?php } if ( $paged > 1 ) { ?>
			<div class="rightnav"><?php previous_posts_link( __( 'Newer Entries', 'greyzed' ) ); ?></div>
			<?php } ?>
		</div>
	</div>
</div>

<?php endif; ?>
<?php get_footer(); ?>