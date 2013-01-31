<?php get_header(); ?>
<div id="content">
<?php is_tag(); ?>
	<?php if ( have_posts() ) : ?>
		<?php $postCount=0; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $postCount++; ?>
	<div <?php post_class( 'entry entry-' . $postCount ); ?> id="post-<?php the_ID(); ?>">
		<div class="entrytitle">
			<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2> 
		</div>
		<div class="entrybody">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'simpla' ) ); ?>
			<?php wp_link_pages(); ?>
		</div>
		
		<div class="entrymeta">
		<div class="postinfo">
			<?php edit_post_link( __( 'Edit', 'simpla' ), '<span class="filedto">', '</span>' ); ?>
		</div>
		</div>
		
	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
	<?php endwhile; ?>
		
	<?php else : ?>

		<h2><?php _e( 'Not Found', 'simpla' ); ?></h2>
		<div class="entrybody"><?php _e( "Sorry, but you are looking for something that isn't here.", 'simpla' ); ?></div>

	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>