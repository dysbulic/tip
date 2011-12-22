<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */
?>
<?php if ( 'open' == $post->comment_status || $comments ) : ?>
<div id="showcomments"><a href="#comments">&#9654; <?php comments_number( __( 'No Responses', 'depo-masthead' ), __( 'One Response', 'depo-masthead' ), __( '% Responses', 'depo-masthead' ) ); ?></a></div>

<div id="comments">

<script type="text/javascript" charset="utf-8">
/*<![CDATA[ */
// comment hide/show mechanism
	jQuery(document).ready(function() {
		check_location();
		function check_location(trick) {
			if(trick != 'hide' && window.location.href.indexOf('#comment') > 0) {
				jQuery('#comments').show('', change_location());
				jQuery('#showcomments a .closed').css('display', 'none');
				jQuery('#showcomments a .open').css('display', 'inline');
				return true;
			} else {
				jQuery('#comments').hide('');
				jQuery('#showcomments a .closed').css('display', 'inline');
				jQuery('#showcomments a .open').css('display', 'none');
				return false;
			}
		}
		jQuery('#showcomments a').click(function(){
			if(jQuery('#comments').css('display') == 'none') {
				self.location.href = '#comments';
				check_location();
			} else {
				check_location('hide');
			}
		});
		function change_location() {
			self.location.href = '#comments';
		}
	});
/* ]]> */
</script>

<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>
			<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'depo-masthead' ); ?></p></div>
			<?php
			return;
		}
	}

if ( have_comments() ) : ?>

	<ol class="commentlist">
	<?php wp_list_comments( array(
		'callback' => 'depo_masthead_comment',
		'avatar_size' => 48,
	) ); ?>
	</ol>

	<div class="commentnavigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

	<?php if ( 'closed' == $post->comment_status ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'depo-masthead' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>

</div>
<?php endif; ?>