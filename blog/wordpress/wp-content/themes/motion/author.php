<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
get_header(); ?>

<div id="main">

	<div id="content">

		<?php
		    if ( isset( $_GET['author_name'] ) ) :
		        $curauth = get_userdatabylogin( $author_name );
		    else :
		        $curauth = get_userdata( intval( $author ) );
		    endif;
		?>

		<h2 id="contentdesc"><?php printf( __( 'Entries by <span>%1$s</span>' ), $curauth->first_name . ' ' . $curauth->last_name ); ?></h2>

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="posttop">
				<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
				<div class="postmetatop">
					<div class="categs"><?php printf( __( 'Filed under: %1$s' ), get_the_category_list(', ') ); ?></div>					
					<div class="date"><span><?php the_time( get_option( 'date_format' ) ); ?></span></div>
				</div>
			</div>
		</div><!-- /post -->

		<?php endwhile; ?>

		<?php else : ?>

		<div class="post">
			<div class="posttop">
				<h2 class="posttitle"><a href="#"><?php _e( 'Oops!' ); ?></a></h2>
			</div>
			<div class="postcontent">
				<p><?php _e( 'What you are looking for doesn&rsquo;t seem to be on this page...' ); ?></p>
			</div>
		</div><!-- /post -->

		<?php endif; ?>

		<div id="navigation">
			<?php if ( function_exists( 'wp_pagenavi' ) ) : ?>
			<?php wp_pagenavi(); ?>
			<?php else : ?>
				<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries' ) ); ?></div>
				<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;' ) ); ?></div>
			<?php endif; ?>
		</div><!-- /navigation -->

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /main -->

<?php get_footer(); ?>