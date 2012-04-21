	</div>

<div id="footer">
<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a><br />
<?php printf( __( 'Theme: %1$s by %2$s.' ), 'Benevolence', '<a href="http://thoughtmechanics.com/blog/2005/01/03/benevolence/" rel="designer">Theron Parlin</a>' ); ?>
<br /><?php _e('Syndicate entries using', 'benevolence'); ?> <a class="footerLink" href="<?php bloginfo('rss2_url'); ?>" title="<?php esc_attr_e( 'Syndicate this site using RSS', 'benevolence' ); ?>">
   <abbr title="Really Simple Syndication">RSS</abbr></a> <?php _e('and', 'benevolence'); ?> <a class="footerLink" href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments (RSS)', 'benevolence'); ?></a>.<br /><br />
</div>

</div>

<?php wp_footer(); ?>

</body>
</html>