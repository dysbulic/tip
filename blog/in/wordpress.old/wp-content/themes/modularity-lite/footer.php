<?php
/**
 * @package WordPress
 * @subpackage Modularity
 */
?>
<div class="clear"></div>
</div>
</div>
<div id="footer-wrap">
<div id="footer">
<div class="span-6 small">
	<?php dynamic_sidebar( 'footer-1' ); ?>
</div>
<div class="column span-6 small">
	<?php dynamic_sidebar( 'footer-2' ); ?>
</div>
<div class="column span-6 small">
	<?php dynamic_sidebar( 'footer-3' ); ?>
</div>
<div class="column span-6 small last">
	<?php dynamic_sidebar( 'footer-4' ); ?>
</div>
<div class="clear"></div>
<p class="small quiet"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Modularity Lite', '<a href="http://graphpaperpress.com/" rel="designer">Graph Paper Press</a>' ); ?></p>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>