	<div id="sidebar">
		<?php if (is_active_sidebar('sidebar-1')) echo '<ul>';?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : endif; ?>
		<?php if (is_active_sidebar('sidebar-1')) echo '</ul>'; ?>
		<?php if (is_active_sidebar('sidebar-2')) echo '<ul class="thin-sidebar spad">';?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-2') ) : endif; ?>
		<?php if (is_active_sidebar('sidebar-2')) echo '</ul>'; ?>
		<?php if (is_active_sidebar('sidebar-3')) echo '<ul class="thin-sidebar">'; ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-3') ) : endif; ?>
		<?php if (is_active_sidebar('sidebar-3')) echo '</ul>' ;?>
	</div><!--end sidebar-->