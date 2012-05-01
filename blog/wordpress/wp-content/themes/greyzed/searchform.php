<div class="search-box">
	<form method="get" action="<?php bloginfo('url'); ?>/">
	<input type="text" size="15" class="search-field" name="s" id="s" value="<?php _e( 'search this site', 'greyzed' ); ?>" onfocus="if(this.value == '<?php _e( 'search this site', 'greyzed' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'search this site', 'greyzed' ); ?>';}"/><input type="submit"  value="" class="search-go" />
	</form>
</div>