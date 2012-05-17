
<div id="footer">
<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> 
<?php printf( __( 'Theme: %1$s by %2$s.', 'whiteasmilk' ), 'White as Milk', '<a href="http://www.azeemazeez.com/stuff/themes/" rel="designer">azeemazeez</a>' ); ?>
<br />

<a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries','whiteasmilk'); ?></a> <?php _e('and','whiteasmilk'); ?> <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('comments','whiteasmilk'); ?></a><?php _e(' feeds.','whiteasmilk'); ?> 
<?php _e('Valid','whiteasmilk'); ?> <a href="http://validator.w3.org/check/referer">XHTML</a> <?php _e('and','whiteasmilk'); ?> <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>.

</div>
</div>

		<?php wp_footer(); ?>

</body>
</html>
