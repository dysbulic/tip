<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */
get_header(); ?>
	<div id="container">
<?php get_sidebar(); ?>
	<div id="content" role="main">
	<div class="column">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="posttitle">
				<h2 class="pagetitle"><?php the_title(); ?></h2>
				<small><?php echo sprintf( __( 'By %s' ), '<strong>' . get_the_author() . '</strong>' ) ; ?></small>
			</div>
			<?php if ( ( comments_open() ) && ( ! post_password_required() ) ) : ?>
			<div class="postcomments"><?php comments_popup_link( '0', '1', '%' ); ?></div>
			<?php endif; ?>
			<div class="entry">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<p><strong>'. __( 'Pages:', 'greyzed') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
				<?php edit_post_link( __( 'Edit this entry.', 'greyzed' ), '<p>', '</p>' ); ?>
			</div>
	<?php if ( comments_open() ) { comments_template(); } ?>
		</div>
		<?php endwhile; endif; ?>	
	</div>
	</div>
<?php get_footer(); ?>
<?php get_footer(); ?>
