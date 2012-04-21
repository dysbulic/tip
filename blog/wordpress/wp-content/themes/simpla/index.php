<?php get_header(); ?>
<div id="content">
<?php is_tag(); ?>
	<?php if (have_posts()) :?>
		<?php $postCount=0; ?>
		<?php while (have_posts()) : the_post();?>
			<?php $postCount++;?>
	<div <?php post_class('entry entry-' . $postCount); ?> id="post-<?php the_ID(); ?>">
		<div class="entrytitle">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2> 
			<h3><?php the_time(get_option('date_format')) ?></h3>
		</div>
		<div class="entrybody">
			<?php the_content('Read the rest of this entry &raquo;'); ?>
			<?php wp_link_pages(); ?>
		</div>
		
		<div class="entrymeta">
		<div class="postinfo">
			<span class="postedby">Posted by <?php the_author() ?></span><br />
			<?php if ( !is_page() ) { ?>
				<span class="filedto">Filed in <?php the_category(', ') ?></span><br />
				<?php the_tags('<span class="filedto">Tags: ', ', ', '</span><br />'); ?>
			<?php } ?>
			<span class="filedto"><?php edit_post_link('Edit', '', ''); ?></span>
		</div>
		<?php comments_popup_link('Leave a Comment &#187;', '1 Comment &#187;', '% Comments &#187;', 'commentslink'); ?>
		</div>
		
	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
	<?php endwhile; ?>

	<?php if ( is_home() || is_archive() || is_search() ) : ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	<?php elseif ( is_single() ) : ?>
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;', 'Previous post link', 'simpla' ) . '</span> %title' ); ?></div>
			<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;', 'Next post link', 'simpla' ) . '</span>' ); ?></div>
		</div>
	<?php endif; ?>
		
	<?php else : ?>

		<h2>Not Found</h2>
		<div class="entrybody">Sorry, but you are looking for something that isn't here.</div>

	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
