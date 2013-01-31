<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title">
					<span>
					<?php if ( ! is_single() ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'pinktouch' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php else : ?>
						<?php the_title(); ?>
					<?php endif; ?>
					</span>
				</h2>

				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'retro' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', 'retro' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
				</div>
			</div>