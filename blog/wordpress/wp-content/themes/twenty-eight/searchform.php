<?php if (!is_search()) {$search_text = "search here";} else {$search_text = "$s";} ?><form method="get" id="searchform" action="/"><p><input type="text" value="<?php echo esc_attr( $search_text ); ?>" name="s" id="s" onfocus="if (this.value == 'search here') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search here';}" /><input type="submit" id="searchsubmit" value="Go" /></p></form>

	



