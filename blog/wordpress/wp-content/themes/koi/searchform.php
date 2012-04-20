<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
?>	<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
		<input type="text" value="<?php _e( 'Search...', 'ndesignthemes' ); ?>" name="s" id="s" onblur="if (this.value == '') {this.value = '<?php _e( 'Search...', 'ndesignthemes' ); ?>';}" onfocus="if (this.value == '<?php _e( 'Search...', 'ndesignthemes' ); ?>') { this.value = ''; }" />
		<input type="submit" value="<?php _e( 'Search', 'ndesignthemes' ); ?>" id="searchsubmit" />
	</form>