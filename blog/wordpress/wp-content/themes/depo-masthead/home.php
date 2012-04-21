<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */

get_header(); ?>

<script type="text/javascript" charset="utf-8">
/*<![CDATA[ */
<?php if( $_GET['preview'] ) : ?> setTimeout( function() { depo_init(); }, 2000); <?php endif; ?>

jQuery(document).ready(depo_init);

function depo_init() {
	var biggest = 0,
		entry_height = 0;
	jQuery('#content .post').each(function() {
		element = jQuery(this).attr('clientHeight');
		if(element > biggest) {
			biggest = element;
			entry_height = jQuery(this).attr('clientHeight');
		}
	});
	jQuery('#content .post').css('height', entry_height);
	jQuery('#content .post:gt(0)').css('border-left', '1px solid #999');
	jQuery('#content .readmore a').each(function() {
		var item  = jQuery(this);
		var width = (item.width() / 2) + 7;
		item.css('margin-left', -width);
	});
}
/* ]]> */
</script>

<div class="group">

	<?php if ( have_posts() ) : ?>
		<?php query_posts( 'showposts=3' ); ?>
		<?php while ( have_posts() ) : the_post();
			if( $post->ID == $do_not_duplicate or $count > 2 ) continue; update_post_caches( $posts ); $count++; ?>
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<p class="postmetadata"><?php the_tags( '', __( ', ', 'depo-masthead' ), '<br />' ); ?></p>
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(__( 'Permanent Link to %s', 'depo-masthead' ), the_title_attribute( 'echo=0' )); ?>"><?php the_title(); ?></a></h2>
				<small><?php printf( __( 'In %1$s on %2$s at %3$s', 'depo-masthead' ), get_the_category_list(__( ', ', 'depo-masthead' )), '<strong>'.get_the_time(get_option( 'date_format' )).'</strong>', '<strong>'.get_the_time().'</strong>' ); ?></small>

				<div class="entry">
					<?php the_content( ' ' ); ?>
					<?php edit_post_link( __( 'Edit this entry.', 'depo-masthead' ), '<p>', '</p>' ); ?>
				</div>
				<div class="readmore">
				<?php
					$moretag = strpos( $post->post_content, '<!--more' );
					$paged = strpos( $post->post_content, '<!--nextpage' );
					$next= '';

					if ( !$moretag && !$paged )
						$full = true;
					else {
						$full = false;
						if ( !$moretag )
							$next = '2/';
						else
							$next = '#more-'.$id;
					}
					if ( $full == true && $post->comment_status == 'open' ) {
				?>
					<a href="<?php the_permalink(); ?>#comments" title="<?php printf( __( 'Comment on %s', 'depo-masthead' ), the_title_attribute() ); ?>"><?php _e( 'Comment&nbsp;&raquo;', 'depo-masthead' ); ?> </a>
					<?php } elseif( !$full && $post->comment_status == 'open' ) { ?>
					<a href="<?php the_permalink(); echo $next; ?>" title="<?php printf( __( 'Comment and continue reading %s' ), the_title_attribute() ); ?>"><?php _e( 'Read&nbsp;More&nbsp;and&nbsp;Comment&nbsp;&raquo;', 'depo-masthead' ); ?></a>
					<?php } elseif( !$full && $post->comment_status == 'closed' ) { ?>
					<a href="<?php the_permalink(); echo $next; ?>" title="<?php _e( 'Continue reading', 'depo-masthead' ); the_title_attribute(); ?>"><?php _e( 'Read&nbsp;More&raquo;', 'depo-masthead' ); ?></a>
					<?php } else { ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'depo-masthead' ), the_title_attribute()); ?>"><?php _e( 'Permalink', 'depo-masthead' ); ?> </a>
					<?php } ?>
				</div>
			</div>

		<?php endwhile; ?>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'depo-masthead' ); ?></h2>
		<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'depo-masthead' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>