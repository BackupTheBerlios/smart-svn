<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>

<div id="sidebar">
<ul id="submenu">
  <li>&nbsp;</li>
  <li><?php echo "<a href='".SF_CONTROLLER."'>Home</a>"; ?></li>
  <?php foreach($B->tpl_nodes as $node_id => $node): ?>    
  <li><a href="<?php echo SF_CONTROLLER; ?>?view=node&node=<?php echo $node_id; ?>"><?php echo $node['title']; ?></a></li>
  <?php endforeach; ?>
  <li>&nbsp;</li>
  <li><a href="<?php echo SF_CONTROLLER; ?>?view=nodetree">Sitemap</a></li>
  <li>&nbsp;</li>
  <li><a href="<?php echo SF_CONTROLLER; ?>?admin=1">Admin</a></li>
  <?php if(isset($B->tpl_logged_user)): ?>
  <li><a href="<?php echo SF_CONTROLLER; ?>?view=logout">Logout</a></li>
  <?php endif; ?>  
</ul>
</div>