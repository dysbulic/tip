<?php
/**
 * The template for displaying search forms in Chateau
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>

<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>/" >
	<div>
		<label for="s" class="assistive-text"><?php _e( 'Search:', 'chateau' ); ?></label>
		<input type="text" value="<?php esc_attr_e( 'Search&hellip;', 'chateau' ); ?>" name="s" id="s" onfocus="this.value=''" />
		<input type="submit" name="search" value="<?php esc_attr_e( 'Go', 'chateau' ); ?>" />
	</div>
</form>