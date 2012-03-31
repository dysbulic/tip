<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */

	get_header();
?>
<div id="content_container">
	<div class="navigation">
			<?php previous_post_link( '&laquo; %link' ) ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;<?php next_post_link( '%link &raquo;' ) ?>
	</div><!--.navigation-->
	<div id="content" class="singlepage image-attachment">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2>
				<a href="<?php echo get_permalink( $post->post_parent ); ?>" rev="attachment">
					<?php echo get_the_title( $post->post_parent ); ?>
				</a> &raquo;
				<?php the_title(); ?>
			</h2>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
<?php
// Get URL of next attachment in gallery OR the URL of the parent post
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
				<div class="centered">
					<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, array( 775, 9999 ) ); ?></a>
					<?php if ( !empty( $post->post_excerpt ) ) : ?>
					<div class="caption">
						 <?php the_excerpt(); ?>
					</div>
					<?php endif; ?>
				</div>

				<div class="post-nav">
					<div class="alignleft previous">
						<?php previous_image_link( false, __( 'Previous image', 'notepad-theme' ) ) ?>
					</div>

					<div class="alignright next">
						<?php next_image_link( false, __( 'Next image', 'notepad-theme' ) ) ?>
					</div>
					<br class="clear" />
				</div>

				<?php the_content(__( '<div class="read_more">read more &raquo;</div>', 'notepad-theme' )); ?>

				<p class="postmetadata">
					<p>
						<small>
							<?php
							$metadata = wp_get_attachment_metadata();
							printf(__( 'Posted on %1$s at %2$s at <a href="%3$s" title="%4$s">%5$s &times; %6$s</a>', 'notepad-theme' ),
								get_the_time( 'l, F jS, Y' ),
								get_the_time(),
								wp_get_attachment_url(),
								esc_attr( __('Link to full-size image', 'notepad-theme') ),
								$metadata['width'],
								$metadata['height']
							);
							?>
						</small>
					</p>
					<p>
						<small>
							<?php if ( comments_open() && pings_open() ) {
								// Both Comments and Pings are open  ?>
								<a href="#respond">
									<?php _e( 'Respond','notepad-theme' ) ?>
								</a>&nbsp;&#124;
								<a href="<?php trackback_url(); ?>" rel="trackback">
									<?php _e( 'Trackback URL','notepad-theme' )?>
								</a>

							<?php } elseif ( !comments_open() && pings_open() ) {
								// Only Pings are Open ?>
								<?php _e( 'You can', 'notepad-theme' )?>
								<a href="<?php trackback_url(); ?> " rel="trackback">
									<?php _e( 'trackback','notepad-theme' )?>
								</a>
								<?php _e( ' from your own site.','notepad-theme' )?>

							<?php } elseif ( comments_open() && !pings_open() ) {
								// Comments are open, Pings are not ?>
								<?php _e( 'You can skip to the end and leave a ', 'notepad-theme' )?>
								<a href="#respond">
									<?php _e( 'response.','notepad-theme' )?>
								</a>

							<?php
								}
								echo "&nbsp;| ";
								edit_post_link(__( ' Edit this entry', 'notepad-theme' ),'','.');
							?>
					</small>
				</p>
			</div><!--.entry-->

			<?php comments_template(); ?>
		</div><!--.post-->
		<?php endwhile; else: ?>
			<p>
				<?php _e( 'Sorry, no attachments matched your criteria.', 'uti_theme' )?>
			</p>
		<?php endif; ?>
	</div><!--#content-->
</div><!--#content_container-->

<?php get_footer(); ?>
