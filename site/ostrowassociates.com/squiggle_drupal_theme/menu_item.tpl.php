<?php
$class = ($leaf ? 'leaf' : ($children ? 'expanded' : 'collapsed'));
$item = menu_get_item($mid);
$active_child = (preg_match('/<li[^>]* class="[^"]*active/', $children) > 0);
if($active_child || $item{'access'}) $class .= " active";
/* IE can't handle multiple child combo selectors */
if($class == "expanded active") $class .= " expanded-active";
?>
<li class="<?php print $class ?>">
   <?php //var_dump($children) ?>
   <?php print menu_item_link($mid) . $children ?>
</li>
