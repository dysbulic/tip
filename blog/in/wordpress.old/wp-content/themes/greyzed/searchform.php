<div class="search-box">
	<form method="get" action="<?php echo home_url( '/' ); ?>">
	<input type="text" size="15" class="search-field" name="s" id="s" value="<?php esc_attr_e( 'search this site', 'greyzed' ); ?>" onfocus="if(this.value == '<?php esc_attr_e( 'search this site', 'greyzed' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'search this site', 'greyzed' ); ?>';}"/><input type="submit"  value="" class="search-go" />
	</form>
</div>