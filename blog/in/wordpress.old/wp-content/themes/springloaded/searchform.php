<form method="get" action="<?php echo home_url( '/' ); ?>">
<p><input type="text" name="s" onblur="this.value=(this.value=='') ? '<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>' : this.value;" onfocus="this.value=(this.value=='<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>') ? '' : this.value;" value="<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>" id="s" />
<input type="submit" name="submit" value="<?php esc_attr_e( 'Search', 'springloaded' ); ?>" id="some_name"></p>
</form>
