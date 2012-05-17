<?php
/**
 * @package Enterprise
 */
?>
<?php if ( is_active_sidebar(2) || is_active_sidebar(3) || is_active_sidebar(4) ) : ?>
<div id="footer-widgeted">
    <div class="footer-widgeted-1">
        <?php dynamic_sidebar(2); ?>
    </div>
    <div class="footer-widgeted-2">
        <?php dynamic_sidebar(3); ?>
    </div>
    <div class="footer-widgeted-3">
        <?php dynamic_sidebar(4); ?>
    </div>
</div>
<?php endif; ?>

<div id="footer">
	<div class="footerleft">
		<p><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a></p>
    </div>
    <div class="footerright">
	    <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Enterprise', '<a href="http://www.studiopress.com/" rel="designer">StudioPress</a>' ); ?>
    </div>
</div>

</div>

<?php wp_footer(); ?>

</body>
</html>