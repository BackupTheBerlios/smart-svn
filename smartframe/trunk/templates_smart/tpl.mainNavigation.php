<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<div id="navcontainer">
<ul id="navlist">
  <li><a href="<?php echo SMART_CONTROLLER; ?>">Home</a></li>
  <?php foreach($tpl['rootNodes'] as $node): ?>    
  <li><a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
  <?php endforeach; ?>
  <li>&nbsp;</li>
  <?php if($tpl['isUserLogged']==TRUE): ?>
  <li><a href="<?php echo SMART_CONTROLLER; ?>?view=logout">Logout</a></li>
  <?php endif; ?>  
</ul>
</div>