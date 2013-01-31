<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><label for="s" class="search-label">Search</label><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'contempt' ); ?>" />
</div>
</form>