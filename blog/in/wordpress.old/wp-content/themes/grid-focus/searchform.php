<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
?>
<div>
	<form method="get" id="searchForm" action="<?php bloginfo('url'); ?>/">
	<span><input type="text" value="<?php esc_attr_e( 'Search the archives...', 'grid-focus' ); ?>" onfocus="if (this.value == '<?php esc_attr_e( 'Search the archives...', 'grid-focus' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'Search the archives...', 'grid-focus' ); ?>';}" name="s" id="s" /></span>
	</form>
</div>