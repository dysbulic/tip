<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" value="<?php esc_attr_e( 'Search...', 'notepad-theme' ); ?>" name="s" id="s"  />
	<input type="hidden" id="searchsubmit" />
</form>