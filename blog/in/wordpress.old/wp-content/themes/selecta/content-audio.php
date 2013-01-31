<?php
/**
 * The portion of the loop that shows the "audio" post format.
 *
 * @package WordPress
 * @subpackage Selecta
 */
 $audio_file = wpcom_themes_audio_grabber( $post->ID );
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-wrapper clearfix' ); ?>>

	<div class="entry-header">
		<?php selecta_entry_date(); ?>

		<?php if ( ! is_single() && get_the_title() != '' ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
	</div><!-- .entry-header -->

	<div class="entry-wrapper clearfix">

		<div class="entry">
			<?php if ( ! empty( $audio_file ) ) : ?>
				<div class="player">
					<audio controls autobuffer id="audio-player-<?php echo $post->ID; ?>" src="<?php echo $audio_file; ?>">
						<source src="<?php echo $audio_file; ?>" type="audio/mp3" />
					</audio>
					<script type="text/javascript">
					    var audioTag = document.createElement( 'audio' );
					    if ( ! ( !! ( audioTag.canPlayType ) && ( "no" != audioTag.canPlayType( "audio/mpeg" ) ) && ( '' != audioTag.canPlayType( 'audio/mpeg' ) ) ) ) {
						AudioPlayer.embed(
								"audio-player-<?php echo $post->ID; ?>", {
									soundFile: "<?php echo $audio_file; ?>",
									animation: 'no',
									width: '400'
								}
							);
					    }
					</script>
				</div>
			<?php endif; ?>
			<?php the_content( __( 'Continue Reading <span class="meta-nav">&rarr;</span>', 'selecta' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "selecta" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
		</div><!-- .entry -->

		<div class="post-info clearfix">
			<p class="post-meta">
				<?php if( get_post_format() == 'aside' ) : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php _e( '#', 'selecta' ); ?></a>
			    <?php endif; ?>
				<?php selecta_post_meta(); ?>
				<?php edit_post_link( __( 'Edit this Entry', 'selecta' ), '<span class="edit-link">', '</span>' ); ?>
			</p>
			<p class="comment-link"><?php comments_popup_link( __( 'Leave a Comment', 'selecta' ), __( '1 Comment', 'selecta' ), __( '% Comments', 'selecta' ) ); ?></p>
		</div><!-- .post-info -->

	</div><!-- .entry-wrapper -->
</div><!-- .post-wrapper -->