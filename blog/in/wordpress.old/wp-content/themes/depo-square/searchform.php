
    <div>
    <form id="searchform" name="searchform" method="get" action="/?s=">
	<input type="text" id="livesearch" name="s" value="<?php esc_attr_e( 'search this site', 'kubrick' ); ?>" onblur="this.value=(this.value=='') ? '<?php esc_attr_e( 'search this site', 'kubrick' ); ?>' : this.value;" onfocus="this.value=(this.value=='<?php esc_attr_e( 'search this site', 'kubrick' ); ?>') ? '' : this.value;" />
	<input type="submit" id="searchsubmit" style="display: none;" value="<?php esc_attr_e( 'Search', 'kubrick' ); ?>" />
    </form>
    </div>
