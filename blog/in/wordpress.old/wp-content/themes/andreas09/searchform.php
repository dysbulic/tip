<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input id="searchbox" type="text" value="<?php the_search_query(); ?>" name="s"/>
<input type="submit" id="searchbutton" value="<?php esc_attr_e( 'Search', 'andreas09' ); ?>"/>
</div>
</form>
