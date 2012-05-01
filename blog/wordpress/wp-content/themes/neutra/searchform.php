<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
?>
<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
	<p><input type="text" size="12" value="<?php _e( 'Search this Blog' , 'neutra' ) ?>" name="s" id="s" class="ipt-keywords" onfocus="if (this.value == this.defaultValue) this.value = ''" /></p>
</form>