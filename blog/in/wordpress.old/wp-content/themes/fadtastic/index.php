<?php get_header(); ?>

		<div id="content_wrapper">
			<div id="content">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); $loopcounter++; ?>
				<?php if ( $loopcounter == 1 || is_sticky() ) { $saved_ids[] = get_the_ID(); ?>
				<h1 id="post-<?php the_ID(); ?>" ><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h1>
				<p class="author fresh" ><?php _e( 'Posted on' ); ?> <em><?php the_time( get_option( 'date_format' ) ); ?></em>. <?php _e( 'Filed under:', 'fadtastic' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | ' ); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></p>

				<?php the_content(); ?>
				<br class="clear" />
				<big>
					<strong><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php _e( 'Read Full Post', 'fadtastic' ); ?></a></strong> |
					<strong>
						<?php if ('open' == $post->comment_status) : ?>
						<a href="<?php the_permalink(); ?>#respond" title="Make a comment"><?php _e( 'Make a Comment' ); ?></a>
						<?php else : ?>
						<?php _e( 'Comments are Closed' ); ?>
						<?php endif;?>
					</strong>
					<small>
						<?php if ('open' == $post->comment_status) : ?>
						 ( <?php comments_popup_link( __( 'None so far' ), __( '<strong>1</strong> so far' ), ( '<strong>%</strong> so far' ) ); ?> )
						<?php endif; ?>
					</small>
				</big>
				<?php } ?>
				<?php endwhile; ?>

				<?php else : ?>

				<h2><?php _e( 'Not Found', 'fadtastic' ); ?></h2>
				<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'fadtastic' ); ?></p>
				<?php get_search_form(); ?>

				<?php endif; ?>

				<!-- Minor posts start here -->

				<h2 class="recently"><?php _e( 'Recently on', 'fadtastic' ); ?> <?php bloginfo('name'); ?>...</h2>
				<?php
				query_posts( array(
				'showposts' => 10,
				'post__not_in' => $saved_ids,
				));
				?>

				<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<?php if( !is_sticky() ) :?>
				<div class="recent_post">
					<h2 id="post-<?php the_ID(); ?>" ><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
					<p class="author" ><?php _e( 'Posted on' ); ?> <em><?php the_time( get_option( 'date_format' ) ); ?></em>. <?php _e( 'Filed under:', 'fadtastic' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | ' ); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></p>
				</div>

				<?php endif; endwhile; ?>

				<?php else : ?>

				<h2><?php _e( 'Not Found', 'fadtastic' ); ?></h2>
				<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'fadtastic' ); ?></p>
				<?php get_search_form(); ?>

				<?php endif; ?>

			</div>
		</div>


	<?php get_sidebar(); ?>

<?php get_footer(); ?>
