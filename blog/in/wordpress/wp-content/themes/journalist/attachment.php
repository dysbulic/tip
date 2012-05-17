<?php get_header(); ?>

<div id="content" class="group">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div <?php post_class(); ?>>
<h2 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment"><?php echo get_the_title( $post->post_parent ); ?></a> &raquo; <?php the_title(); ?></h2>

<?php if ( comments_open() || have_comments() ) : ?>
	<p class="comments"><a href="<?php comments_link(); ?>"><?php comments_number( __( 'leave a comment &raquo;', 'journalist' ), __( 'with one comment', 'journalist' ), __( 'with % comments', 'journalist' ) ); ?></a></p>
<?php endif; ?>

<div class="main">
	<p class="attachment"><?php echo wp_get_attachment_link( $post->ID, 'auto', false, true ); ?></p>
	<div class="caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
	<div class="attachment-description"><?php if ( !empty( $post->post_content ) ) the_content(); ?></div>
</div>

<div class="meta group">
	<?php edit_post_link( __( 'Edit', 'journalist' ), '<div class="signature"><p><span class="edit">', '</span></p></div>' ); ?>
</div>
<?php comments_template(); ?>
</div>

<?php endwhile; else: ?>
<div class="warning">
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'journalist' ); ?></p>
</div>
<?php endif; ?>

</div> 

<?php get_sidebar(); ?>

<?php get_footer(); ?>
