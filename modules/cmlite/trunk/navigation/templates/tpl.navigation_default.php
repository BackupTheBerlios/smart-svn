<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->nodes as $node): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="1%" align="left" valign="top"><?php echo '<a href="index.php?m=NAVIGATION&sec=edit_node&node_id='.$node->id.'">edit</a>'; ?></td>
        <td width="1%" align="left" valign="top"><?php 
        if($node->status == 0)
        $img = 'modules/navigation/media/stat_trash.gif';
      elseif($node->status == 1)
        $img = 'modules/navigation/media/stat_cancel.gif';
      elseif($node->status == 2)
        $img = 'modules/navigation/media/stat_prop.gif';
      elseif($node->status == 3)
        $img = 'modules/navigation/media/stat_edit.gif';
      elseif($node->status == 4)
        $img = 'modules/navigation/media/stat_public.gif';
        else
        $img = 'modules/navigation/media/stat_prop.gif';                      
     ?>
          <img src="<?php echo $img; ?>" width="8" height="8"></td>
        <td width="98%" align="left" valign="top" class="itemnormal">
            <?php echo '<a href="index.php?m=NAVIGATION&node_id='.$node->id.'">'.$node->name.'</a>'; ?></td>
      </tr>
    </table>
        <?php endforeach; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="index.php?m=NAVIGATION&sec=add_node&parent_id=<?php echo $_REQUEST['id_node']; ?>">add node</a></td>
  </tr>
</table>
