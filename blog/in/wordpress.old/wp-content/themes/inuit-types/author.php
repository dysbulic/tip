<?php
/**
 * @package Inuit Types
 */
?>
<?php get_header(); ?>

		<?php $curauth = get_userdata(intval($author)); ?>

		<?php if (have_posts()) : ?>

		<div class="browsing-archive">

		    <div id="header-about">

	            <h2><?php _e( 'Browsing Archives of Author', 'it' ); ?> &raquo;<?php echo $curauth->first_name . ' ' . $curauth->last_name; ?>&laquo;</h2>

			</div>

	    </div>


	<div class="blog">

		<?php while (have_posts()) : the_post(); ?>

            <div class="post">

			    <h2><a title="<?php esc_attr_e( 'Permanent Link to ', 'it' ); ?><?php the_title_attribute(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			    <div class="date-comments">

				    <p class="fl"><em><?php the_time( get_option( 'date_format' ) ); ?></em></p>

				    <p class="fr"><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></p>

			    </div>

			    <div class="fix"></div>

			    <p><?php echo inuit_types_excerpt( get_the_excerpt(), get_permalink() ); ?></p>

            </div>

		<?php endwhile; ?>

		<div class="fix"></div>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&larr;</span> Older posts', 'theme' )); ?></div>
				<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'theme' )); ?></div>
			</div><!-- #nav-below -->

    </div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>