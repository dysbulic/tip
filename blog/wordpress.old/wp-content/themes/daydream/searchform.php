<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">

	<div style="text-align: center;">
	
		<input type="text" value="<?php esc_attr_e( 'Type and Press Enter to Search...', 'daydream' ); ?>" onfocus="this.value=''; this.style.color='#000';" onblur="this.value='<?php esc_attr_e( 'Type and Press Enter to Search...', 'daydream' ); ?>'; this.style.color='#ccc';"  name="s" id="s" />
	
	</div>

</form>
