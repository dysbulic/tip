<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
?>
<!-- begin footer -->

<div style="clear:both;"></div>

<div id="footertopbg">

    <div id="footertop">
        
            <div class="footertopleft widget-area">
            	<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Left') ) : ?>
                <?php endif; ?>
                </ul>
            </div>
            
            <div class="footertopmidleft widget-area">
            	<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Mid Left') ) : ?>
                <?php endif; ?>
                </ul>
            </div>
            
            <div class="footertopmidright widget-area">
            	<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Mid Right') ) : ?>
                <?php endif; ?>
                </ul>
            </div>
            
            <div class="footertopright widget-area">
            	<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Right') ) : ?>
                <?php endif; ?>
                </ul>
            </div>
            
    </div>
        

</div>

<div id="footerbg">

	<div id="footer">
    
    	<div class="footerleft">
            <div class="footertop">
                <p><a href="<?php echo home_url( '/' ); ?>" title="Home"><?php bloginfo('name'); ?></a> &middot; <?php bloginfo('description'); ?></p>
            </div>
            
            <div class="footerbottom">
                <p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> <?php printf( __( 'Theme: %1$s by %2$s.', 'structure-theme' ), 'Structure', '<a href="http://www.organicthemes.com/" rel="designer">Organic Themes</a>' ); ?></p>
            </div>
        </div>
        
        <div class="footerright">
    	</div>
		
	</div>
	
</div>

</div>

<?php wp_footer(); ?>

</body>
</html>