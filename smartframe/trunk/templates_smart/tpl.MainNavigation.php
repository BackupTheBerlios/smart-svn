<!-- prevent direct all -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<div id="navcontainer">
<ul id="navlist">

  <!-- link to the entry page -->
  <li><a href="<?php echo SMART_CONTROLLER; ?><?php if(isset($tpl['lang'])) echo '?lang='.$tpl['lang']; ?>">Home</a></li>
  
  <!-- output all root navigation nodes -->
  <?php foreach($tpl['rootNodes'] as $node): ?>    
  <li><a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
  <?php endforeach; ?>
  <li>&nbsp;</li>
  
  <!-- show logout links if user is logged -->
  <?php if($tpl['isUserLogged'] == TRUE): ?>
  <li><a href="<?php echo SMART_CONTROLLER; ?>?view=logout">Logout</a></li>
  <?php endif; ?>   
  
  <!-- show admin links if user is logged -->
  <?php if( isset($tpl['loggedUserRole']) ): ?>
    
    <!-- show link to the administration interface -->
    <?php if($tpl['loggedUserRole'] < 100): ?>
      <li><a href="<?php echo $tpl['adminWebController']; ?>">Admin</a></li>
    
    <!-- show link for the "webuser" to modify his account -->
    <?php else: ?>
      <li><a href="<?php echo SMART_CONTROLLER; ?>?view=myAccount">Your Account</a></li>  
    <?php endif; ?>  
  <?php endif; ?>
</ul>
</div>