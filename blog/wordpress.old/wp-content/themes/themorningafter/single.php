<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="post_content" class="column full-width">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div class="column primary-content first">
		
			<div class="post_cat"><?php the_category( ', ' ); ?></div>
						
			<h1 class="post_name" id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
						
			<div class="post_meta">			
				<?php _e( 'Posted by','woothemes' );?> <?php the_author_posts_link(); ?> <span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?> <span class="dot">&sdot;</span> <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?>
			</div>

			<div class="post_meta">
				<?php the_tags( '<span class="filedunder"><strong>' . __( 'Filed Under','woothemes' ) . '</strong></span> &nbsp;', ', ', '' ); ?>
			</div>

			<div class="post_text">
				<?php the_content( '<p>'.__( 'Continue reading this post','woothemes' ).'</p>' ); ?>
							
				<div class="clear"></div>
							
				<?php wp_link_pages( array( 'before' => '<div class="page-navigation"><p><strong>'.__( 'Pages','woothemes' ).':</strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				
				<?php edit_post_link( __( 'Edit this entry.', 'woothemes' ),'<p>','</p>' ); ?>
			</div><!-- .post_text -->
			
			<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
			<div id="entry-author-info">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
				</div><!-- #author-avatar -->
				<div id="author-description">
					<h2 id="entry-author-info-heading"><?php esc_html( printf( __( 'About %s', 'woothemes' ), get_the_author() ) ); ?></h2>
					<?php the_author_meta( 'description' ); ?>
					<div id="author-link">
						<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
							<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'woothemes' ), get_the_author() ); ?>
						</a>
					</div><!-- #author-link -->
				</div><!-- #author-description -->
			</div><!-- #entry-author-info -->
			<?php endif; ?>			
						
			<div id="nav-below" class="post-navigation clear-fix">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;', 'Previous post link', 'woothemes' ) . '</span> %title' ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;', 'Next post link', 'woothemes' ) . '</span>' ); ?></div>
			</div>
			
			<?php comments_template( '', true); ?>

		</div><!-- end .primary-content -->
	
	<?php endwhile; else: ?>
				
		<?php
			printf( __( '<p>Lost? Go back to the <a href="%s">home page</a></p>', 'woothemes' ),
				get_home_url()
			);
		?>
	
	<?php endif; ?>
		
	<?php get_sidebar(); ?>

</div><!-- end #post_content -->
		
<?php get_footer(); ?>