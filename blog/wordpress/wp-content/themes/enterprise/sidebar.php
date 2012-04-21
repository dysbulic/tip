<?php
/**
 * @package WordPress
 * @subpackage Enterprise
 */
?>
<div id="sidebar">
    	
	<?php if ( !dynamic_sidebar(1) ) : ?>

        <div class="widget widget_categories">
            <h4><?php _e("Categories", 'enterprise'); ?></h4>
            <?php wp_dropdown_categories('show_option_none='.__('Select category', 'enterprise').'&hierarchical=true&orderby=name'); ?>
            <script type="text/javascript">
			/* <![CDATA[ */
                var dropdown = document.getElementById("cat");
                function onCatChange() {
                    if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
                        location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
                    }
                }
                dropdown.onchange = onCatChange;
            /* ]]> */            
            </script> 
        </div>
                    
		<div class="widget widget_recent_entries">
            <h4><?php _e("Recent Posts", 'enterprise'); ?></h4>
                <ul>
                    <?php wp_get_archives('type=postbypost&limit=5'); ?> 
                </ul>
		</div>	
        
		<div class="widget widget_archive">
            <h4><?php _e("Archives", 'enterprise'); ?></h4>
                <ul>
                    <?php wp_get_archives('type=monthly'); ?>
                </ul>
		</div>
        
		<div class="widget widget_links">
            <h4><?php _e("Blogroll", 'enterprise'); ?></h4>
                <ul>
                    <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
                </ul>
		</div>
	
		<div class="widget widget_meta">
            <h4><?php _e("Meta", 'enterprise'); ?></h4>
                <ul>
                    <?php wp_register(); ?>
                    <li><?php wp_loginout(); ?></li>
                    <li><a href="http://www.wordpress.org/">WordPress</a></li>
                    <?php wp_meta(); ?>
                    <li><a href="http://validator.w3.org/check?uri=referer">XHTML</a></li>
                </ul>
		</div>
                        		
	<?php endif; ?>
		
</div>