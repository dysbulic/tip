<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><h2 class="widgettitle"><?php _e( 'Search', 'paperpunch' ); ?></h2>
<form method="get" id="search_form" action="<?php echo home_url( '/' ); ?>">
	<div>
		<input type="text" value="<?php esc_attr_e( 'Type and press enter', 'paperpunch' ); ?>" name="s" id="s" onfocus="if (this.value == '<?php esc_attr_e( 'Type and press enter', 'paperpunch' ); ?>' ) {this.value = '';}" onblur="if (this.value == '' ) {this.value = '<?php esc_attr_e( 'Type and press enter', 'paperpunch' ); ?>';}" />
		<input type="hidden" value="<?php esc_attr_e( 'Search', 'paperpunch' ); ?>" />
	</div>
</form>
