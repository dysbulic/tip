<div class="block block-<?php print $block->module; ?>" id="block-<?php print $block->module; ?>-<?php print $block->delta; ?>">
  <?php if($block->module != 'menu' && $block->subject != ""): // eliminate the "Primary links" from the main site links ?>
  <h2 class="title"><?php print $block->subject; ?></h2>
  <?php endif; ?>
  <div class="content"><?php print $block->content; ?></div>
</div>
