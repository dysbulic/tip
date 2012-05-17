<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */
?>

<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php
		$attachments = get_children(
			array(
				'post_parent' => get_the_ID(),
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'orderby' => 'menu_order'
			)
		);
		if ( ! is_array($attachments) )
			continue;
		
		$count = count($attachments);
	
		$first_attachment = array_shift($attachments);
	?>
	<?php if ( is_sticky() ) : ?>
		<h5 class="post-date"><abbr class="published"><?php _e( 'Featured', 'manifest' ); ?></abbr></h5>
	<?php else : ?>
		<h5 class="post-date"><abbr class="published"><a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></abbr></h5>
	<?php endif; ?>
	<div class="post-content">
		<?php if ( is_multi_author() ) : ?>
    	<h4 class="vcard author"><?php printf( __( 'by <span class="fn">%s</span>', 'manifest' ), get_the_author() ); ?></h4>
		<?php endif; ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'manifest' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><img class="entry-image" src="<?php echo $first_attachment->guid; ?>?w=500" alt="<?php esc_attr_e( $first_attachment->post_title ); ?>" /></a>

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
	</div>
	<div class="post-meta">
		<div class="comments">
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<?php comments_popup_link( __( 'Leave a comment', 'manifest' ), __( '1 comment', 'manifest' ), __( '% comments', 'manifest' ), '', __( 'Comments closed', 'manifest' ) ); ?>
		<?php endif; ?>
		</div>
	</div>
</div>