<?php if (!is_search()) {
		$search_text = __( 'Search...', 'unsleepable' );
	} else {
		$search_text = "$s";
	}
?>


		<form method="get" id="searchform" action="/">
			<input type="text" value="<?php echo esc_attr( $search_text ); ?>" name="s" id="s" onfocus="if (this.value == 'type and wait to search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'type and wait to search';}" />
			<input type="submit" id="searchsubmit" value="go" />
		</form>
