<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>	
					<div class="post-navigation clear-fix">
						<div class="nav-previous">
							<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'choco' ) . '</span> %title' ); ?>
						</div>
						<div class="nav-next">
							<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'choco' ) . '</span>' ); ?>
						</div>
					</div><!-- .post-navigation -->
					
					<div <?php post_class(); ?>>
						<h1 class="post-title"><?php the_title(); ?></h1>
						<div class="date">
							<div class="bg">
								<span class="day"><?php the_time( 'd' ); ?></span>
								<span><?php the_time( 'M' ); ?></span>
							</div>
						</div><!-- .date -->
						
						<div class="entry">
							<?php if( has_post_thumbnail() ){ 
								the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) );
							} ?>
							<?php the_content(); ?>
							<div class="cl">&nbsp;</div>
							
							<?php wp_link_pages( array( 'before' => '<div class="page-navigation"><p><strong>'. __( 'Pages:', 'choco' ) .' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							
							<?php edit_post_link( __( '(Edit)', 'choco' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry -->
						
						<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
						<div id="entry-author-info">
							<div id="author-avatar">
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
							</div><!-- #author-avatar -->
							<div id="author-description">
								<h2 id="entry-author-info-heading"><?php printf( esc_attr__( 'About %s', 'choco' ), get_the_author() ); ?></h2>
								<?php the_author_meta( 'description' ); ?>
								<div id="author-link">
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
										<?php printf( __( 'View all posts by %s <span class="meta-nav">&raquo;</span>', 'choco' ), get_the_author() ); ?>
									</a>
								</div><!-- #author-link -->
							</div><!-- #author-description -->
						</div><!-- #entry-author-info -->
						<?php endif; ?>
						
						<div class="meta">
							<div class="bg">
								<span class="comments-num"><?php comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' ); ?></span>
								<p><?php _e( 'Posted by', 'choco' ); ?> <?php the_author_posts_link(); ?> <?php _e( 'on', 'choco' ); ?> <?php the_date( get_option( 'date_format' ) ); ?> <?php _e( 'in', 'choco' ); ?> <?php the_category( ', ' ); ?></p>
							</div>
							<div class="bot">&nbsp;</div>
						</div><!-- .meta -->
						
						<?php the_tags( '<p class="tags">' . __( 'Tags:', 'choco' ) . ' ', ', ', '</p>' ); ?>
					
					</div><!-- .post -->
					
					<div class="post-navigation clear-fix">
						<div class="nav-previous">
							<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'choco' ) . '</span> %title' ); ?>
						</div>
						<div class="nav-next">
							<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'choco' ) . '</span>' ); ?>
						</div>
					</div><!-- .post-navigation -->
					
					<?php comments_template(); ?>
				
				<?php endwhile; else: ?>
					<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'choco' ); ?></p>
				<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>