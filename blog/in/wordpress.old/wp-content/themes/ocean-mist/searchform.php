<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>/">
<div><input type="text" value="<?php esc_attr_e( 'Search', 'ocean-mist' ); ?>" name="s" id="s" onfocus="if (this.value == '<?php echo esc_js(__('Search','ocean-mist')); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_js(__('Search','ocean-mist')); ?>';}" />
<input type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/button-search<?php if ( is_rtl() ) echo '-rtl';?>.gif" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'ocean-mist' ); ?>" />
</div>
</form>
