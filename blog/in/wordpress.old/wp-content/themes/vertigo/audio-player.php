<?php
/**
 * @package Vertigo
 */

	$audio_file = vertigo_get_audio_file();

	if ( $audio_file ) : ?>
	<audio controls autobuffer id="audio-player-<?php echo $post->ID; ?>" src="<?php echo $audio_file; ?>">
		<source src="<?php echo $audio_file; ?>" type="audio/mp3" />
	</audio>
	<script type="text/javascript">
		//<![CDATA[
		var audioTag = document.createElement( 'audio' );
		if (!(!!(audioTag.canPlayType) && ("no" != audioTag.canPlayType("audio/mpeg")) && ("" != audioTag.canPlayType("audio/mpeg")))) {
			AudioPlayer.embed(
			"audio-player-<?php echo $post->ID; ?>",
				{
					soundFile: "<?php echo $audio_file; ?>"
				}
			);
		}
	//]]>
	</script>
<?php endif; ?>