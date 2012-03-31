<?php
/**
 * @package WordPress
 * @subpackage Koi
 */
?>	<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<input type="text" value="<?php esc_attr_e( 'Search...', 'ndesignthemes' ); ?>" name="s" id="s" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'Search...', 'ndesignthemes' ); ?>';}" onfocus="if (this.value == '<?php esc_attr_e( 'Search...', 'ndesignthemes' ); ?>') { this.value = ''; }" />
		<input type="submit" value="<?php esc_attr_e( 'Search', 'ndesignthemes' ); ?>" id="searchsubmit" />
	</form>