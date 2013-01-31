<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<?php get_header(); ?>

<div id="container">

	<div id="content">

		<?php if ( have_posts() ) : ?>

		<?php /* If this is a category archive */ if (is_category()) { ?>
			<h1 class="pagetitle"><?php printf( __( 'Posts from the &#8216;%s&#8217; Category' ), single_cat_title( "", false ) ); ?></h1>
			<?php /* If this is a tag archive */ } elseif ( is_tag() ) { ?>
			<h1 class="pagetitle"><?php printf( __( 'Posts tagged &#8216;%s&#8217;' ), single_tag_title( "", false ) ); ?></h1>
			<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
			<h1 class="pagetitle"><?php _e( 'Archive for' ); ?> <?php the_time( __( 'F jS, Y' ) ); ?></h1>
			<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
			<h1 class="pagetitle"><?php _e( 'Archive for' ); ?> <?php the_time( __( 'F, Y' ) ); ?></h1>
			<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
			<h1 class="pagetitle"><?php _e( 'Archive for' ); ?> <?php the_time( __( 'Y' ) ); ?></h1>
			<?php /* If this is an author archive */ } elseif ( is_author() ) { if ( isset( $_GET['author_name'] ) ) $current_author = get_userdatabylogin( $author_name ); else $current_author = get_userdata( intval( $author ) );?>
			<h1 class="pagetitle"><?php printf( __( 'Posts by %s' ), $current_author->display_name ); ?></h1>
			<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
			<h1 class="pagetitle"><?php _e( 'Blog Archives' ); ?></h1>
		<?php } ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<div class="post">

			<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Permanent Link to' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

			<div class="post-content">
				<?php the_excerpt( __( 'Read more&hellip;' ) ); ?>
			</div>

			<div class="postmeta">
				<span class="date"><img src="<?php bloginfo( 'template_url' ); ?>/images/calendaricon.png" alt="" />&nbsp;<?php the_time( get_option( 'date_format' ) ); ?></span>
				<span class="author"><img src="<?php bloginfo( 'template_url' ); ?>/images/authoricon.png" alt="" />&nbsp;<?php the_author(); ?></span>
				<span class="comment">
					<img src="<?php bloginfo( 'template_url' ); ?>/images/commentsicon.png" alt="" />&nbsp;
					<?php comments_popup_link( __( 'Leave a comment' ), __( '1 Comment', 'theme' ), __( '% Comments' ) ); ?>					
				</span>
				<?php edit_post_link( __( 'Edit this' ), '<span class="edit">', '</span>' ); ?>
				<div class="taxonomy">
					<span class="categories"><?php _e( 'Categories:' ); ?>&nbsp;<?php the_category( ', ' ); ?></span>
					<?php the_tags('<span class="tags">'.__( 'Tags:' ).'&nbsp;',', ','</span>'); ?>
				</div>
			</div><!-- /postmeta -->

		</div><!-- /post -->

		<?php endwhile; ?>

		<?php else : ?>

		<h2><?php _e( '404 - Page not found' ); ?></h2>
		<p><?php _e( 'Oops! I cannot find what you are looking for. Please try again with a different keyword.', 'darkwood' ); ?></p>

		<?php endif; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;' ) ); ?></div>
		</div>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /container -->

<?php get_footer(); ?>