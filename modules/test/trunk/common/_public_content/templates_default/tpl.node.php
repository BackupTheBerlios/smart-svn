<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo $B->sys['option']['site_desc']; ?>">
<title><?php echo $B->sys['option']['site_title']; ?> - <?php echo $B->tpl_node['title'];  ?> ---</title>
<link href="<?php echo SF_RELATIVE_PATH; ?>templates_default/smart.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0">

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><img src="<?php echo SF_RELATIVE_PATH; ?>templates_default/smart-banner.png" width="760" height="100"></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            <?php /* ### include the navigation menu view (template) ### */ ?>
            <?php M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); ?>   
    </td>
        <td width="638" align="left" valign="top"><table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td>
        <div class="branch">Top /
         <?php  foreach($B->tpl_branch as $bnode): ?>
             <?php echo '<a href="'.SF_CONTROLLER.'?view=node&node='.$bnode['node'].'">'.$bnode['title'].'</a>'; ?> /
         <?php endforeach; ?>
           <hr class="hr" /></div>
          <?php if(count($B->tpl_child_nodes) > 0): ?>           
          <table width="200"  border="0" align="right" cellpadding="0" cellspacing="0" class="subnodetable">
             <tr>
               <td align="left" valign="top" class="subnodetitle" colspan="2">Subnodes</td>
             </tr>
             <?php $table = TRUE; endif; ?>
             <?php foreach($B->tpl_child_nodes as $node_id => $node): ?>
             <tr>
               <td width="10" class="subnode">&nbsp;-&nbsp;</td>
               <td width="190" class="subnode"><?php echo '<a href="'.SF_CONTROLLER.'?view=node&node='.$node_id.'">'.$node['title'].'</a>'; ?></td>
             </tr>
             <?php endforeach; ?>
             <?php if($table == TRUE):  ?>
           </table>   
           <?php endif; ?>
           <?php if(!empty($B->tpl_error)): ?>
           <h3><?php echo $B->tpl_error;  ?></h3>
           <?php else: ?>
           <!-- print title and body of a navigation node -->
           <h2 class="smart"><?php echo $B->tpl_node['title'];  ?></h2>
           <?php echo $B->tpl_node['body'];  ?>
           <?php endif; ?>
           </td>
          </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer"><?php include_once(SF_RELATIVE_PATH . "templates_default/tpl.footer.php"); ?></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
