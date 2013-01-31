<?php 

get_header();
is_tag();

?>

<div id="maincontent">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div <?php post_class(); ?>>
    <h2><a href="<?php the_permalink() ?>" rel="bookmark">
      <?php the_title(); ?>
      </a></h2>
    <div style="clear: both"></div>
    <div class="pb"><b class="postbitmed"> <b class="postbitmed1"><b></b></b> <b class="postbitmed2"><b></b></b> <b class="postbitmed3"></b> <b class="postbitmed4"></b> <b class="postbitmed5"></b> </b>
      <div class="postbitmed_content">
        <p><img src="<?php bloginfo('template_directory'); ?>/imgs/calen.gif" alt="Posted On" />
          <?php the_time( get_option( 'date_format' ) ); ?>
        </p>
	<p><?php _e( 'Filed under', 'iceburgg' ); ?> <?php the_category(', ') ?>
	<?php the_tags( '<br />' . __( 'Tags', 'iceburgg' ) . ': ', ', ', ''); ?>
	<?php edit_post_link( __( 'Edit', 'iceburgg' ), '<br />', ''); ?></p>
        <p><img src="<?php bloginfo( 'template_directory' ); ?>/imgs/comments.gif" alt="<?php esc_attr_e( 'Comments Dropped', 'iceburgg' ); ?>" /> <a href="<?php comments_link(); ?>">
          <?php comments_number( __( 'leave a response', 'iceburgg' ), __( 'one response', 'iceburgg' ), __( '% responses', 'iceburgg' ) ); ?>
          </a></p>
      </div>
      <b class="postbitmed"> <b class="postbitmed5"></b> <b class="postbitmed4"></b> <b class="postbitmed3"></b> <b class="postbitmed2"><b></b></b> <b class="postbitmed1"><b></b></b> </b></div>
    <div class="words">
      <?php the_content( __( '(Read More)', 'iceburgg' ) ); ?>
    </div>
    <div style="clear: both"></div>
  </div>
  <?php endwhile; else: ?>
  <p>
    <?php _e( 'Sorry, no posts matched your criteria.', 'iceburgg' ); ?>
  </p>
  <?php endif; ?>
  <?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?>
  <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
<?php get_sidebar() ?>

<?php get_footer(); ?>
