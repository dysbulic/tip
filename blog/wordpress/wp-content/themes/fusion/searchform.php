<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>

<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
	<input type="text" name="s" size="40" id="searchbox" class="searchfield" />
	<input type="submit" value="<?php _e( 'Search', 'fusion' ); ?>" class="searchbutton" />
</form>