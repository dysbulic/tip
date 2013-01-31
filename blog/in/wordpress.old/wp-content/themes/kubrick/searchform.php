
    <div>
    <form id="searchform" name="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
		<label style="display: none;" for="livesearch">Search:</label>
		<input type="text" id="livesearch" name="s" value="<?php esc_attr_e( 'search this site', 'kubrick' ); ?>" onblur="this.value=(this.value=='') ? '<?php _e('search this site', 'kubrick'); ?>' : this.value;" onfocus="this.value=(this.value=='<?php _e('search this site', 'kubrick'); ?>') ? '' : this.value;" />
		<input type="submit" id="searchsubmit" style="display: none;" value="<?php esc_attr_e( 'Search', 'kubrick' ); ?>" />
    </form>
    </div>
