<?php
/**
 * @package Spectrum
 */

?>

<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" >
	<p>
		<label for="s" class="accesible"><?php _e( 'Search:', 'spectrum' ); ?></label>
		<input type="text" value="" name="s" id="s" />
		<button type="submit"><?php _e( 'Go!', 'spectrum' ); ?></button>
	</p>
</form>