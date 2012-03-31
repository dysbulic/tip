<?php
/**
 * The template for displaying search forms in iTheme2
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'itheme2' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'itheme2' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'itheme2' ); ?>" />
	</form>
