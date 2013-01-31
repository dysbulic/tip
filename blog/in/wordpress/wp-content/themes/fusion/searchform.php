<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>

<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" name="s" size="40" id="searchbox" class="searchfield" />
	<input type="submit" value="<?php esc_attr_e( 'Search', 'fusion' ); ?>" class="searchbutton" />
</form>