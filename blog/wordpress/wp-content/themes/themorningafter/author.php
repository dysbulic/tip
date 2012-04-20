<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<?php $content_width = 540; // to override $content_width for the narrower column ?>

<?php $morningafter_options = morningafter_get_theme_options(); ?>

<div id="arch_content" class="column span-14">
	
	<?php
		if ( isset( $_GET['author_name'] ) ) :
			$curauth = get_userdatabylogin( $author_name );
		else :
			$curauth = get_userdata( intval( $author ) );
		endif;
	?>

	<?php if ( have_posts() ) : ?>
		<div class="column span-3 first">
			<h1 class="archive_name"><?php echo $curauth->display_name; ?></h1>
			<span class="author_description"><?php echo $curauth->description; ?></span>
			
			<div class="archive_meta">
				<div class="archive_number">
					<?php echo $curauth->display_name; ?> <?php _e( 'has written','woothemes' ); ?> <?php the_author_posts(); ?> <?php _e( 'posts for','woothemes' ); ?> <?php bloginfo( 'name' ); ?>
				</div>
			</div>
		</div><!-- end .span-3 -->
		
		<div class="column span-8">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="archive_post_block clear-fix">
					<h3 class="archive_title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>
					
					<div class="archive_post_meta"><?php _e( 'By','woothemes' );?> <?php the_author_posts_link(); ?> <span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?> <span class="dot">&sdot;</span> <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></div>
				
					<?php if ( $morningafter_options['show_full_archive'] == "1" ) the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); else the_excerpt(); ?>
				</div>
			<?php endwhile; ?>

			<div id="nav-below" class="post-navigation clear-fix">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'woothemes' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); ?></div>
			</div><!-- #nav-below -->					
		
		</div><!-- end .span-8 -->

		<?php get_sidebar(); ?>
		
	<?php else : ?>
		
		<p><?php _e( 'Lost? Go back to the','woothemes' );?> <a href="<?php echo home_url(); ?>/"><?php _e( 'home page','woothemes' );?></a>.</p>
	
	<?php endif; ?>		

</div><!-- end #arch_content -->

<?php get_footer(); ?>