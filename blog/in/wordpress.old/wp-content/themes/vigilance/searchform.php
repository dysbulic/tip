<?php
/**
 * @package Vigilance
 */
?>
<form method="get" id="search_form" action="<?php echo home_url( '/' ); ?>">
	<div>
		<input type="text" value="<?php esc_attr_e( 'type and press enter', 'vigilance' ); ?>" name="s" id="s" onfocus="if (this.value == '<?php esc_attr_e( 'type and press enter', 'vigilance' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'type and press enter', 'vigilance' ); ?>';}" />
		<input type="hidden" value="<?php esc_attr_e( 'Search', 'vigilance' ); ?>" />
	</div>
</form>