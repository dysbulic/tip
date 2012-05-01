<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Code modified from Enterprise by StudioPress / WordPress.com
 */
?>
<div id="comments">

	<?php
	/* If password protected */
	if ( post_password_required() ) { ?>
		<div class="nopassword">
			<?php _e( 'This post is password protected. Enter the password to view any comments.', 'zbench' ); ?>
		</div>
	</div><!-- #comments -->
		<?php
		return;
	}


	/* You can start editing here -- including this comment! */
	if ( have_comments() ) : ?>
	<h3 id="comments-title"><?php comments_number(
		sprintf( __( 'No Responses to %s', 'zbench' ), '<em>' . get_the_title() . '</em>' ),
		sprintf( __( 'One Response to %s', 'zbench' ), '<em>' . get_the_title() . '</em>' ),
		sprintf( __( '%% Responses to %s', 'zbench' ), '<em>' . get_the_title() . '</em>' )
		); ?>
	</h3>

<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
	<div class="navigation">
		<div class="nav-previous">
			<?php previous_comments_link( __( '&larr; Older Comments', 'zbench' ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'zbench' ) ); ?>
		</div>
	</div>
<?php endif; // check for comment navigation ?>

	<?php /* The main comments list */ ?>
	<ol class="commentlist">
		<?php
			/* Load comments list - utilizing callback function */
			wp_list_comments(
				array(
					'callback' => 'zbench_comment'
				)
			);
		?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
	<div class="navigation">
		<div class="nav-previous">
			<?php previous_comments_link( __( '&larr; Older Comments', 'zbench' ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'zbench' ) ); ?>
		</div>
	</div>
<?php endif; // check for comment navigation ?>

<?php else : // this is displayed if there are no comments so far ?>

<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

<?php else : // if comments are closed ?>
</div><!-- #comments -->

<?php if ( ! is_page() ) : ?>
	<p class="comments-closed">Comments are closed.</p>
<?php endif; ?>

<?php return; ?>
<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>

</div><!-- #comments -->
