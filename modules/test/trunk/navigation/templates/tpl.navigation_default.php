<style type="text/css">
<!--
.tree {
  font-size: 14px;
  margin-top: 5px;
  margin-right: 5px;
  margin-bottom: 10px;
  margin-left: 5px;
  border-top-width: 0px;
  border-right-width: 0px;
  border-bottom-width: 1px;
  border-left-width: 0px;
  border-top-style: none;
  border-right-style: none;
  border-bottom-style: solid;
  border-left-style: none;
  border-bottom-color: #990033;
}
-->
</style>
<style type="text/css">
<!--
.treenode {
  font-size: 14px;
  font-weight: bold;
  color: #000099;
  padding-top: 4px;
  padding-right: 5px;
  padding-bottom: 6px;
  padding-left: 5px;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <div class="tree"><?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation">Top</a>'; ?> /
  <?php if(isset($B->tpl_branch) && is_array($B->tpl_branch)): ?>
  <?php  foreach($B->tpl_branch as $bnode): ?>
      <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&node='.$bnode['node'].'">'.$bnode['title'].'</a>'; ?> /
  <?php endforeach; ?>
  <?php endif; ?>
    <?php if($B->tpl_node_title != FALSE): ?>
    <br /><br /><span class="treenode"><?php echo $B->tpl_node_title; ?></span>
  <?php endif; ?><br /><br /></div>
    <?php foreach($B->tpl_nodes as $node_id => $node): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="1%" align="left" valign="top" class="itemnormal"><font size="1">
    <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=up&dir_node='.$node_id.'&node='.$_GET['node'].'">up</a>'; ?>
    </font></td>
          <td width="1%" align="left" valign="top" class="itemnormal"><font size="1">
    <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&dir=down&dir_node='.$node_id.'&node='.$_GET['node'].'">down</a>'; ?>
    </font></td>
          <td width="1%" align="left" valign="top" class="itemnormal"><font size="1"><?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&sec=editnode&edit_node='.$node_id.'&node='.$_GET['node'].'"'; ?>>edit</a></font></td>
          <td width="97%" align="left" valign="top" class="itemnormal">
              <?php echo '<a href="'.SF_CONTROLLER.'?admin=1&m=navigation&node='.$node_id.'">'.$node['title'].'</a>'; ?></td>
      </tr>
    </table>
    <?php endforeach; ?>
    </td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=addnode&node=<?php echo $_GET['node']; ?>">add node </a></td>
  </tr>
</table>
