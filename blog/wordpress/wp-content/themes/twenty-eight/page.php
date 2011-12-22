<?php get_header(); ?>
<div class="content">
	<div class="primary">
		<?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>
			<div class="item">
				<div class="pagetitle">
					<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a></h2>
					<?php edit_post_link('<img src="' . get_bloginfo( template_directory ) . '/images/pencil.png" alt="Edit Link" />', '<span class="editlink">', '</span>'); ?>
				</div>
				<div class="itemtext">
					<?php the_content( '<p class="serif">' . __( 'Read the rest of this page &raquo;', 'twenty-eight' ) . '</p>'); ?>
					<?php link_pages( '<p><strong>' . __( 'Pages:', 'twenty-eight' ) . '</strong>', '</p>', 'number'); ?>
				</div>
			</div>
		<?php } ?>
		<?php } else { $notfound = '1'; ?>
		<h2><?php _e( 'Not Found', 'twenty-eight' ); ?></h2>
		<div class="item">
			<div class="itemtext">
				<p><?php _e( "Oh no! You're looking for something which just isn't here.", 'twenty-eight' ); ?></p>
			</div>
		</div>
		<?php } ?>
		<?php comments_template(); ?>
	</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>