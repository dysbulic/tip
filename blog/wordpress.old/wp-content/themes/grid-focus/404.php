<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
get_header();
?>
<div id="filler" class="fix">
	<div id="mainColumn" class="fix"><a name="main"></a>
		<div class="post">
			<div class="postMeta fix">
				<p class="container">
					&nbsp;
				</p>
			</div>
			<h2><?php _e( '404', 'grid-focus' ); ?></h2>
			<div class="entry">
				<p><?php _e( 'Oops. Something obviously isn&rsquo;t right if you&rsquo;re reading this. The URL you entered or followed no longer seems to exist, has been removed, or has been replaced. If you feel that this an error that needs to be addressed, feel free to contact the administrator of this website.', 'grid-focus' ); ?></p>
			</div>
		</div>
	</div>
	<?php include (TEMPLATEPATH . '/second.column.index.php'); ?>
	<?php include (TEMPLATEPATH . '/third.column.shared.php'); ?>
</div>
<?php get_footer(); ?>