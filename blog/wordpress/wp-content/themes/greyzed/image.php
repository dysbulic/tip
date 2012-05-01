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

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="posttitle">
				<h2 class="pagetitle"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>
			
				<small>
					<?php printf( __( 'Posted: %1$s by <strong>%2$s</strong>', 'greyzed' ),
						get_the_date( get_option( 'date_format' ) ),
						get_the_author()
						); ?>
					<span class="meta-sep">|</span>
					<?php $metadata = wp_get_attachment_metadata();
					printf( __( 'Full size is %s pixels', 'greyzed'),
						sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
							wp_get_attachment_url(),
							esc_attr( __('Link to full-size image', 'greyzed') ),
							$metadata['width'],
							$metadata['height']
						)
					); ?>
					<?php edit_post_link( 'Edit this entry.', ' <span class="meta-sep">|</span> ', '' ); ?>
					<br/>
				</small>
			</div>
			<?php if ( ( comments_open() ) && ( ! post_password_required() ) ) : ?>
				<div class="postcomments"><?php comments_popup_link( '0', '1', '%' ); ?></div>
			<?php endif; ?>
			<div class="entry">
<?php
$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
foreach ( $attachments as $k => $attachment ) {
	if ( $attachment->ID == $post->ID )
		break;
}
$k++;
if ( isset( $attachments[ $k ] ) )
	$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
else
	$next_attachment_url = get_permalink( $post->post_parent );
?>
				<p class="attachment">
					<a href="<?php echo $next_attachment_url; ?>">
						<?php echo wp_get_attachment_image( $post->ID, array( 648, 9999 ) ); ?>
					</a>
				</p>
				
				<div class="caption">
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?>
				</div>
				
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
				
				<div class="navigation">
					<div class="alignleft leftnav"><?php previous_image_link( false, __( 'Previous image', 'greyzed' ) ) ?></div>
					<div class="alignright rightnav"><?php next_image_link( false, __( 'Next image', 'greyzed' ) ) ?></div>
				</div>
				
				<br class="clear" />
				
				<p class="postmetadata alt">
					<small>
						<?php if ( comments_open() && pings_open() ) {
							// Both Comments and Pings are open ?>
							You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif ( !comments_open() && pings_open() ) {
							// Only Pings are Open ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif ( !comments_open() && !pings_open() ) {
							// Neither Comments, nor Pings are open ?>
							Both comments and pings are currently closed.

						<?php } ?>
					</small>
				</p>
			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e( 'Sorry, no images matched your criteria.', 'greyzed' ); ?></p>

	<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>