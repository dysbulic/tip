<?php
	if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ( 'Please do not load this page directly. Thanks.' );
?>
			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'notesil' ); ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
?>

<?php // Number of pings and comments
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
?>

<?php if ( $comment_count ) : ?>
	<?php $notes_comments_alt = 0 ?>
	<div id="comments-list" class="comments">
		<h3><?php _e( 'Comments', 'notesil' ); ?></h3>
		<ul>
			<?php wp_list_comments( 'type=comment&callback=notes_comments' ); ?>
		</ul>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link(); ?></div>
			<div class="alignright"><?php next_comments_link(); ?></div>
		</div>
	</div><!-- #comments-list .comments -->
<?php endif; // REFERENCE: if ( $comment_count ); ?>

<?php if ( $ping_count ) : ?>
<?php $notes_comments_alt = 0 ?>
	<div id="trackbacks-list" class="comments">
	<h3><?php _e( 'Trackbacks', 'notesil' ); ?></h3>
	<ul>
		<?php wp_list_comments( 'type=pings&callback=notes_trackbacks' ); ?>
	</ul>
	</div><!-- #trackbacks-list .comments -->

<?php endif // REFERENCE: if ( $ping_count ); ?>
<div class="navigation">
 <?php paginate_comments_links(); ?>
</div>
<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif // REFERENCE: if ( comments_open() ); ?>

			</div><!-- #comments -->