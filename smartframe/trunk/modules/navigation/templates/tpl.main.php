<script language="JavaScript" type="text/JavaScript">
function nodemap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=navigation&view=nodemap','',mm); }
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <div class="font12 indent5">
  <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation">Top</a>
  <?php foreach($tpl['branch'] as $node): ?>
   / <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
  <?php endforeach; ?>
  <?php if($tpl['id_node']!=0): ?>
     <span class="font12bold"> / <?php echo $tpl['node']['title']; ?></span>
  <?php endif; ?>
  </div>
  <?php if(isset($tpl['nodes']) && (count($tpl['nodes'])>0)): ?>
    <?php foreach($tpl['nodes'] as $node): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="21" align="left" valign="top" class="itemnormal">
    <?php if($node['status']==1): ?>
    <img src="./modules/common/media/pics/inactive.png" width="21" height="21">
    <?php elseif($node['status']==2): ?>
    <img src="./modules/common/media/pics/active.png" width="21" height="21">
    <?php elseif($node['status']==3): ?>
    <img src="./modules/common/media/pics/restricted.png" width="21" height="21"> 
    <?php endif; ?>
    </td>
        <td width="21" align="left" valign="top" class="itemnormal"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node_up=<?php echo $node['id_node']; ?>&id_node=<?php echo $node['id_parent']; ?>"><img src="./modules/common/media/pics/up.png" width="21" height="21" border="0"></a></td>
        <td width="21" align="left" valign="top" class="itemnormal"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node_down=<?php echo $node['id_node']; ?>&id_node=<?php echo $node['id_parent']; ?>"><img src="./modules/common/media/pics/down.png" width="21" height="21" border="0"></a></td>
        <td width="1%" align="left" valign="top" class="font9"><?php if($node['lock']==FALSE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=editNode&id_node=<?php echo $node['id_node']; ?>&disableMainMenu=1">edit</a><?php endif; ?>&nbsp;</td>
          <td width="99%" align="left" valign="top" class="itemnormal">
          <?php if($node['lock']==FALSE): ?>
                <?php echo '<a href="'.SMART_CONTROLLER.'?mod=navigation&id_node='.$node['id_node'].'">'.$node['title'].'</a>'; ?>
              <?php elseif($node['lock']==TRUE): ?>
          <?php echo $node['title']; ?> <strong>-lock-</strong>
        <?php endif; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
    <?php else: ?> 
  <br><br><div class="font12bold">There is currently no navigation node available here. You may add some one here.</div><br><br>  
  <?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="font12">
       <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=addNode&id_node=<?php echo $tpl['id_node']; ?>">add node</a>
    <p><a href="javascript:nodemap();">NodesMap</a></p></td>
  </tr>
</table>
