<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */
?>

<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
	<div id="searchfield">
		<label for="s" class="screen-reader-text"><?php _e( 'Search for:', 'mystique' ); ?></label>
		<input type="text" name="s" id="s" class="searchtext" />
		<input type="submit" value="<?php esc_attr_e( 'Search', 'mystique' ); ?>" class="searchbutton" />
	</div>
</form>