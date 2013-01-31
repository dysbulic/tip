<?php /*
	Template Name: Archives
*/ ?>

<?php $numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");if (0 < $numposts) $numposts = number_format($numposts);$numcomms = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");if (0 < $numcomms) $numcomms = number_format($numcomms);$numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy='category'");if (0 < $numcats) $numcats = number_format($numcats);?><?php get_header(); ?>

<div class="content"><div class="primary"><?php if (have_posts()) : while (have_posts()) : the_post(); ?><div class="item"><div class="pagetitle"><h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php edit_post_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />', '<span class="editlink">', '</span>'); ?></div><div class="itemtext">

<div class="ar-categories">
	<h2><?php _e( 'Browse by Category', 'twenty-eight' ); ?></h2>
	<ul><?php list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0,'','','','',''); ?></ul>
	<br clear="all" />
	<h2><?php _e( 'Browse by Month', 'twenty-eight' ); ?></h2>
	<ul><?php wp_get_archives('type=monthly');?></ul>
</div>
<br clear="all"/>			

<h2><?php _e( 'Stats', 'twenty-eight' ); ?></h2>
<p style="font-size: 1.3em;line-height: 1.3em;"><strong><?php echo $numposts; ?></strong> <?php _e( 'Entries', 'twenty-eight' ); ?><br/><strong><?php echo $numcomms; ?></strong> <?php _e( 'Comments', 'twenty-eight' ); ?><br/><strong><?php echo $numcats; ?></strong> <?php _e( 'Categories', 'twenty-eight' ); ?><?php if ( function_exists( 'akismet_counter' ) ) { ?><br/><strong><?php akismet_counter(); ?></strong> <?php _e( 'Spam Killed', 'twenty-eight' ); ?><?php } ?></p></div>
	
</div></div><?php endwhile; endif; ?></div><hr /><?php get_sidebar(); ?></div><?php get_footer(); ?>
