<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
?>
<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
	<input type="text" value="<?php _e( 'Search...','notepad-theme' ); ?>" name="s" id="s"  />
	<input type="hidden" id="searchsubmit" />
</form>