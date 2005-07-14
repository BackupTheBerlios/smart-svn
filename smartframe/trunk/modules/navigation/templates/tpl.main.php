<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
	<?php if(isset($tpl['nodes']) && (count($tpl['nodes'])>0)): ?>
    <?php foreach($tpl['nodes'] as $node): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
          <td width="60%" align="left" valign="top" class="itemnormal">
		      <?php if($node['lock']==FALSE): ?>
                <?php echo '<a href="'.SMART_CONTROLLER.'?mod=navigation&view=editnode&id_node='.$node['id_node'].'">'.$node['title'].'</a>'; ?>
              <?php elseif($node['lock']==TRUE): ?>
			    <?php echo $node['title']; ?> <strong>-lock-</strong>
			  <?php endif; ?>
		   </td>
	    </tr>
    </table>
    <?php endforeach; ?>
    <?php else: ?> 
	There is currently no navigation node available. Please add some one.	
	<?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><?php if($tpl['showAddNodeLink']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=addnode&id_node=<?php echo $tpl['id_node']; ?>">add node</a><?php endif; ?></td>
  </tr>
</table>
