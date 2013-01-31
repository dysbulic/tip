<?php
/**
 * @package WordPress
 * @subpackage Modularity
 */
?>
<div id="search">
	<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<div>
	        <input type="text" class="field" name="s" id="s"  value="<?php esc_attr_e( 'Search', 'modularity' ); ?>" onfocus="if (this.value == '<?php esc_attr_e( 'Search', 'modularity' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'Search', 'modularity' ); ?>';}" />
		</div>
	</form>
</div>
