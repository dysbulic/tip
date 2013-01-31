<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="entry-content">

		<div class="entry">
			<span class="error"><img src="<?php bloginfo( 'template_directory' ); ?>/images/mal.png" alt="error duck" /></span>
			<p><?php _e( 'Hmmm, seems like what you were looking for isn&rsquo;t here. You might want to give it another try.', 'oulipo' ); ?></p>
		</div>

	</div> <!-- close entry-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>