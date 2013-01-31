<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php esc_attr_e( 'search archives' ); ?>" name="s" id="s" onblur="if (this.value == '') {this.value = '<?php echo esc_js(__('search archives')); ?>';}" onfocus="if (this.value == '<?php echo esc_js(__('search archives')); ?>') {this.value = '';}" />
<input type="submit" id="searchsubmit" />
</div>
</form>
