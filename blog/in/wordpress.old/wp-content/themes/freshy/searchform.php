<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<p>
<input size="12" type="text" value="<?php the_search_query(); ?>" name="s" id="s" /><input class="btn" type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', TEMPLATE_DOMAIN ); ?>" />
</p>
</form>
