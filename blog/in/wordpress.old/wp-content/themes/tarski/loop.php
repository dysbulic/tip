<?php is_tag(); ?>
<?php if ( have_posts() ) : $count_users = count( get_users_of_blog() ); // Should really only look for users that can write posts, or maybe check to see how many authors have written posts ?>

<div id="primary">

<?php if( !is_single() && !is_home() && !is_page() ) { ?>
		<div class="entry archive">
			<div class="post-meta">
		<?php if(is_category()) { ?>
			<h1 class="post-title"><?php _e( 'Category Archive', 'tarski' ); ?></h1>
		</div>
		<div class="post-content">
			<p><?php printf( __( 'You are currently browsing the category archive for the &lsquo;%1$s&rsquo; category.', 'tarski' ), single_cat_title( '', false ) ); ?></p>
		<?php } ?>
		<?php if(is_tag()) { ?>
			<h1 class="post-title"><?php _e( 'Tag Archive', 'tarski' ); ?></h1>
		</div>
		<div class="post-content">
			<p><?php printf( __( 'You are currently browsing the tag archive for the &lsquo;%1$s&rsquo; tag.', 'tarski' ), single_tag_title( '', false ) ); ?></p>
		<?php } ?>
		<?php if(is_author()) { ?>
				<h1 class="post-title"><?php _e( 'Author Archive', 'tarski' ); ?></h1>
			</div>
			<div class="post-content">
				<?php $_current_author = $wp_query->get_queried_object(); ?>
				<p><?php printf( __( 'You are currently browsing %1$s&rsquo;s articles.', 'tarski' ), $_current_author->display_name ); ?></p>
		<?php } ?>
		<?php if(is_day()) { ?>
				<h1 class="post-title"><?php _e( 'Daily Archive', 'tarski' ); ?></h1>
			</div>
			<div class="post-content">
				<p><?php printf( __( 'You are currently browsing the daily archive for %1$s.', 'tarski' ), get_the_date() ); ?></p>
		<?php } ?>
		<?php if(is_month()) { ?>
				<h1 class="post-title"><?php _e( 'Monthly Archive', 'tarski' ); ?></h1>
			</div>
			<div class="post-content">
				<p><?php printf( __( 'You are currently browsing the monthly archive for %1$s.', 'tarski' ), get_the_date( 'F Y' ) ); ?></p>
		<?php } ?>
		<?php if(is_year()) { ?>
				<h1 class="post-title"><?php _e( 'Yearly Archive', 'tarski' ); ?></h1>
			</div>
			<div class="post-content">
				<p><?php printf( __( 'You are currently browsing the yearly archive for %1$s.', 'tarski' ), get_the_date( 'Y' ) ); ?></p>
		<?php } ?>
		<?php if(is_search()) { ?>
				<h1 class="post-title"><?php _e( 'Search Results', 'tarski' ); ?></h1>
			</div>
			<div class="post-content">
				<p><?php printf( __( 'You searched for %1$s.', 'tarski' ), get_search_query() ); ?></p>
		<?php } ?>
			</div>
		</div>
<?php } ?>

<?php if ( is_single() || is_page() ) : while ( have_posts() ) : the_post(); ?>
	<div class="entry<?php if (is_page()) { echo " static"; } ?>">
		<div class="post-meta">
			<h1 class="post-title" id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
<?php	if ( !is_page() ) : ?>
			<p class="post-metadata"><?php
				the_time(get_option('date_format'));
				if ( ! get_option( 'tarski_hide_categories' ) ) :
				?> in <?php
					the_category( ', ' );
					the_tags( __( ' | Tags: ', 'tarski' ), ', ', '' );
				endif;

				/* If there is more than one author, show author's name */
				if ( $count_users > 1 ) :
				?> | by <?php
					the_author_posts_link();
				endif;
				edit_post_link( __( 'Edit', 'tarski' ),' (',')' ); ?>
			</p>
<?php 	endif; ?>
		</div>
		<div class="post-content">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'tarski' ) ); ?>
		</div>
		<?php wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'tarski' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<?php if ( is_page() ) { edit_post_link( __( 'edit page', 'tarski' ), '<p class="post-metadata">(', ')</p>'); } ?>
	</div>
<?php endwhile; else : while (have_posts()) : the_post(); ?>
	<div <?php post_class('entry'); ?>>
		<div class="post-meta">
			<h2 class="post-title" id="post-<?php the_ID(); ?>"><?php if( !is_single() ) { ?><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a><?php } else { the_title(); } ?></h2>
			<p class="post-metadata"><?php
				the_time(get_option('date_format'));
				if ( !get_option('tarski_hide_categories') ) :
				?> in <?php
					the_category(', ');
					the_tags(' | Tags: ', ', ', ''); 
				endif;

				/* If there is more than one author, show author's name */
				if ( $count_users > 1 ) :
				?> | by <?php
					the_author_posts_link();
				endif;
				?> | <?php
				comments_popup_link(__('Leave a comment', 'tarski'), __('1 comment', 'tarski'), __('% comments', 'tarski'),'',__('Comments closed','tarski'));
				edit_post_link('Edit',' (',')'); ?>
			</p>
		</div>

		<div class="post-content">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'tarski' ) ); ?>
		</div>
		<?php wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'tarski' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	</div>
<?php endwhile; endif; // have_posts; is_single || is_page ?>
</div>
<?php else : // have_posts (top of file) ?>
	<div id="primary">
		<div class="entry static">
			<div class="post-meta">
				<h1 class="post-title" id="error-404"><?php _e( 'Error 404', 'tarski' ); ?></h1>
			</div>

			<div class="post-content">
				<p><?php printf( __( 'The page you are looking for does not exist; it may have been moved, or removed altogether. You might want to try the search function. Alternatively, return to the <a href="%1$s">front page</a>.', 'tarski' ), home_url( '/' ) ); ?></p>
			</div>
		</div>
	</div>
<?php endif; // have_posts (top of file) ?>
