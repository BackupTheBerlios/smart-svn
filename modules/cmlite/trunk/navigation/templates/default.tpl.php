<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
    <?php foreach($B->nodes as $node): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
        <td width="5%" align="left" valign="top">edit</td>
        <td width="95%" align="left" valign="top" class="itemnormal">
            <?php echo '<a href="index.php?m=NAVIGATION&node_id='.$node->id.'">'.$node->name.'</a>'; ?></td>
      </tr>
    </table>
        <?php endforeach; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><a href="index.php?m=NAVIGATION&mf=add_nav_node">add node</a></td>
  </tr>
</table>
