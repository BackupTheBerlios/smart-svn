<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="keywords" content="<?php echo $B->sys['option']['site_desc']; ?>">
<title><?php echo $B->sys['option']['site_title']; ?> - <?php echo $B->tpl_node['title'];  ?> ---</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="templates_smart/smart.css" rel="stylesheet" type="text/css">
</head>

<body topmargin="0">
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><img src="templates_smart/smart-banner.png" width="760" height="100"></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            <?php /* ### include the navigation menu view (template) ### */ ?>
            <?php M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); ?>		
		</td>
        <td width="640" align="left" valign="top"><table width="640" border="0" cellspacing="4" cellpadding="2">
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
           <?php endif ?>
           <?php if(!empty($B->tpl_error)): ?>
           <h3><?php echo $B->tpl_error;  ?></h3>
           <?php else: ?>
           <!-- print title and body of a navigation node -->
           <h3><?php echo $B->tpl_node['title'];  ?></h3>
		   <div class="text"><?php echo $B->tpl_node['body'];  ?></div>
           <?php endif; ?>
</td>
          </tr>
        </table>
          </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer"><?php include_once("templates_smart/tpl.footer.php"); ?></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
