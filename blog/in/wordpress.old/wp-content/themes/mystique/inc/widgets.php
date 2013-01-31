<?php
/**
 * @package WordPress
 * @subpackage Mystique
 * Description: A widget that presents categories, tags, most commented posts and recent comments in a tabbed interface.
 */

 /**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'mystique_load_widgets' );

/**
 * Register our widget.
 */
function mystique_load_widgets() {
	register_widget( 'Mystique_Widget' );
}

/**
 * Widget class.
 */
class Mystique_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Mystique_Widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mystique', 'description' => __( 'Displays your categories, tags, most commented posts and recent comments in a tabbed interface.', 'mystique' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mystique-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'mystique-widget', __( 'Mystique Tabbed Widget', 'mystique' ), $widget_ops, $control_ops );
	}

	/**
	 * The actual widget display
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Widget settings variables */
		$title = apply_filters('widget_title', $instance['title'] );
		$show_categories = isset( $instance['show_categories'] ) ? $instance['show_categories'] : false;
		$show_tags = isset( $instance['show_tags'] ) ? $instance['show_tags'] : false;
		$show_archives = isset( $instance['show_archives'] ) ? $instance['show_archives'] : false;
		$show_most_commented = isset( $instance['show_most_commented'] ) ? $instance['show_most_commented'] : false;
		$show_recent_comments = isset( $instance['show_recent_comments'] ) ? $instance['show_recent_comments'] : false;
		$show_post_count = isset( $instance['show_post_count'] ) ? $instance['show_post_count'] : false;
		$sort_categories = $instance['sort_categories'];
		$num_most_commented = $instance['num_most_commented'];
		$num_recent_comments = $instance['num_recent_comments'];

		/* Before widget */
		echo $before_widget; ?>
		<div class="tabbed-content sidebar-tabs">

			<ul class="sidebar-tabs">

			<?php if ( $show_recent_comments ) : ?>
				<li class="tab-comments"><a href="#tab-comments" title="<?php esc_attr_e( 'Comments', 'mystique' ); ?>"><?php _e( 'Recent Comments', 'mystique' ); ?></a></li>
			<?php endif; ?>
 			<?php if ( $show_most_commented ) : ?>
 				<li class="tab-popular-posts"><a href="#tab-popular-posts" title="<?php esc_attr_e( 'Popular Posts', 'mystique' ); ?>"><?php _e( 'Popular Posts', 'mystique' ); ?></a></li>
 			<?php endif; ?>
 			<?php if ( $show_archives ) : ?>
				<li class="tab-archives"><a href="#tab-archives" title="<?php esc_attr_e( 'Archives', 'mystique' ); ?>"><?php _e( 'Archives', 'mystique' ); ?></a></li>
			<?php endif; ?>
			<?php if ( $show_tags ) : ?>
 				<li class="tab-tags"><a href="#tab-tags" title="<?php esc_attr_e( 'Tags', 'mystique' ); ?>"><?php _e( 'Tags', 'mystique' ); ?></a></li>
			<?php endif; ?>
			<?php if ( $show_categories ) : ?>
				<li class="tab-categories"><a href="#tab-categories" title="<?php esc_attr_e( 'Categories', 'mystique' ); ?>"><?php _e( 'Categories', 'mystique' ); ?></a></li>
			<?php endif; ?>
			</ul>

			<div class="tab-container">

				<?php if ( $show_recent_comments ) : ?>
				<div id="tab-comments" class="tab-content">
					<ul class="menu-list recent-comments">
					<?php $comments = get_comments( 'number='.$num_recent_comments.'&status=approve' );

					foreach ($comments as $comment ) :

						$comment_link = get_comment_link ( $comment->comment_ID );
						$comment_content = wp_html_excerpt( $comment->comment_content, 100 );
						?>

						<li class="clear-block">
							<a class="tab-link" title="<?php printf (__( 'Posted in: %s', 'mystique' ), get_the_title( $comment->comment_post_ID ) ); ?>" href="<?php echo $comment_link; ?>">
								<span class="comment-entry"><span class="comment-avatar"><?php echo get_avatar( $comment, 32 ); ?></span><span class="comment-author"><?php echo( $comment->comment_author ); ?></span><?php echo $comment_content; ?></span>
							</a>
						</li>

					<?php endforeach; ?>
					</ul>
				</div><!-- #tab-comments -->
				<?php endif; ?>

				<?php if ( $show_most_commented ) : ?>
				<div id="tab-popular-posts" class="tab-content">
					<?php $popular = new WP_Query('orderby=comment_count&caller_get_posts=1&posts_per_page='.$num_most_commented.'');
						if ($popular-> have_posts() ) : ?>
						<ul class="menu-list">
						<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
							<li><a class="tab-link" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span class="entry"><?php the_title(); ?> <span class="details inline"><?php comments_number( __( '(0)', 'mystique' ), __( '(1)', 'mystique' ), __( '(%)', 'mystique' ) ); ?></span></span></a></li>
						<?php endwhile; ?>
						</ul>
						<?php else:
							_e( 'Did not find any posts popular enough :(', 'mystique' );
						endif;
						wp_reset_query();
						?>
				</div><!-- #tab-popular-posts -->
				<?php endif; ?>

				<?php if ( $show_archives ) : ?>
				<div id="tab-archives" class="tab-content">
					<ul class="menu-list">
					<?php if ( $show_post_count ) :
						wp_get_archives( apply_filters( 'widget_archives_args', array( 'type' => 'monthly', 'show_post_count' => '1' ) ) );
	 		 		else:
	 		 			wp_get_archives( apply_filters( 'widget_archives_args', array( 'type' => 'monthly' ) ) );
					endif; ?>
					</ul>
 		 		</div><!-- #tab-archives -->
 		 		<?php endif; ?>

			<?php if ( $show_tags ) : ?>
			<div id="tab-tags" class="tab-content">
				<?php wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array() ) ); ?>
			</div><!-- #tab-tags -->
			<?php endif; ?>

			<?php if ( $show_categories ) : ?>
				<div id="tab-categories" class="tab-content">
					<ul class="menu-list">
					<?php if ( $show_post_count ) :
					 	wp_list_categories('orderby='.$sort_categories.'&show_count=1&title_li=');
 		 			else:
						wp_list_categories('orderby='.$sort_categories.'&title_li=');
					endif; ?>
					</ul>
 		 		</div><!-- #tab-categories -->
 		 	<?php endif; ?>

			</div><!-- .tab-container -->

		</div><!-- .tabbed-content -->

	<?php
		/* After widget */
		echo $after_widget;
	}

	/**
	 * Update and validate the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Escape text inputs before saving user-submitted text, and check that numerical inputs are indeed a number. */
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['num_most_commented'] = (int) $new_instance['num_most_commented'];
		$instance['num_recent_comments'] = (int) $new_instance['num_recent_comments'];

		/* Validate select-list and checkbox options */
	 	if ( in_array( $new_instance['sort_categories'], array( 'name', 'ID', 'slug', 'count' ) ) )
	 		$instance['sort_categories'] = $new_instance['sort_categories'];
		else $instance['sort_categories'] = 'name';
		$instance['show_categories'] = $new_instance['show_categories'];
		$instance['show_tags'] = $new_instance['show_tags'];
		$instance['show_archives'] = $new_instance['show_archives'];
		$instance['show_most_commented'] = $new_instance['show_most_commented'];
		$instance['show_recent_comments'] = $new_instance['show_recent_comments'];
		$instance['show_post_count'] = $new_instance['show_post_count'];

		return $instance;
	}

	/**
	 * Display the widget settings controls.
	 */
	function form( $instance ) {

		/* Default widget settings. */
		$defaults = array( 'title' => '', 'num_most_commented' => '10', 'num_recent_comments' => '8', 'sort_categories' => 'name', 'show_post_count' => true, 'show_categories' => true, 'show_tags' => true, 'show_archives' => true, 'show_most_commented' => true, 'show_recent_comments' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mystique' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" style="width: 90%" />
		</p>

		<!-- Tabs to Show: Checkboxes -->
		<div>
			<h3 style="font-weight: bold"><?php _e( 'Tabs to show', 'mystique' ); ?></h3>
		</div>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_categories'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_categories' ); ?>" name="<?php echo $this->get_field_name( 'show_categories' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_categories' ); ?>"><?php _e( 'Categories', 'mystique' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_tags'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_tags' ); ?>" name="<?php echo $this->get_field_name( 'show_tags' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_tags' ); ?>"><?php _e( 'Tags', 'mystique' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_archives'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_archives' ); ?>" name="<?php echo $this->get_field_name( 'show_archives' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_archives' ); ?>"><?php _e( 'Archives', 'mystique' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_most_commented'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_most_commented' ); ?>" name="<?php echo $this->get_field_name( 'show_most_commented' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_most_commented' ); ?>"><?php _e( 'Popular Posts (Most Commented)', 'mystique' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_recent_comments'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_recent_comments' ); ?>" name="<?php echo $this->get_field_name( 'show_recent_comments' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_recent_comments' ); ?>"><?php _e( 'Recent Comments', 'mystique' ); ?></label>
		</p>

		<!-- Sort Categories: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'sort_categories' ); ?>"><?php _e( 'Sort categories by:', 'mystique' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'sort_categories' ); ?>" name="<?php echo $this->get_field_name( 'sort_categories' ); ?>" class="widefat" style="width: 90%;">
				<option <?php if ( 'name' == $instance['sort_categories'] ) echo 'selected="selected"'; ?>>name</option>
				<option <?php if ( 'ID' == $instance['sort_categories'] ) echo 'selected="selected"'; ?>>ID</option>
				<option <?php if ( 'slug' == $instance['sort_categories'] ) echo 'selected="selected"'; ?>>slug</option>
				<option <?php if ( 'count' == $instance['sort_categories'] ) echo 'selected="selected"'; ?>>count</option>
			</select>
		</p>

		<!-- Show Post Count: Checkbox -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( isset( $instance['show_post_count'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_post_count' ); ?>" name="<?php echo $this->get_field_name( 'show_post_count' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_post_count' ); ?>"><?php _e( 'Show post counts (categories and archives)', 'mystique' ); ?></label>
		</p>

		<!-- Number of Popular Posts to Show: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'num_most_commented' ); ?>"><?php _e( 'How many popular posts to show?', 'mystique' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_most_commented' ); ?>" name="<?php echo $this->get_field_name( 'num_most_commented' ); ?>" value="<?php echo esc_attr( $instance['num_most_commented'] ); ?>" class="widefat" style="width: 90%" />
		</p>

		<!-- Number of Recent Comments to Show: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'num_recent_comments' ); ?>"><?php _e( 'How many recent comments to show?', 'mystique' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'num_recent_comments' ); ?>" name="<?php echo $this->get_field_name( 'num_recent_comments' ); ?>" value="<?php echo esc_attr( $instance['num_recent_comments'] ); ?>" class="widefat" style="width: 90%" />
		</p>
	<?php
	}
}
