<?php /*
 ### Default template ### 
     see also /view_default/class.view_node.php
*/ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?>

             
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php /* 
        --------------------------------------------------------------
        Print out system variables defined in the admin options menu. 
        --------------------------------------------------------------*/?>
<title><?php echo $B->sys['option']['site_title']; ?> - <?php echo $B->tpl_title;  ?> ---</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />

<style type="text/css">
<!--
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
}
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
-->
</style>
<style type="text/css">
<!--
.tree {
	font-size: 14px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 10px;
	margin-left: 5px;
	color: #0000CC;
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
<style type="text/css">
<!--
.subnode {
	font-size: 12px;
}
-->
</style>
<style type="text/css">
<!--
.subnodetitle {
	font-size: 12px;
	font-weight: bold;
	color: #000099;
	background-color: #66CCFF;
	text-align: center;
	padding-top: 2px;
	padding-right: 0px;
	padding-bottom: 2px;
	padding-left: 0px;
}
-->
</style>
<style type="text/css">
<!--
-->
</style>
<style type="text/css">
<!--
.subnodetable {
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 2px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: solid;
	border-left-color: #66CCFF;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#66CCFF"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="53%"><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <strong>Test</strong></font></font></td>
        <td width="47%" align="right" valign="middle"> <font size="2">
          <!-- Show user name if a user is logged -->
          <?php if(isset($B->tpl_logged_user)): ?>
      User: <strong><?php echo $B->tpl_logged_user; ?></strong> is logged
      <?php endif; ?>
        </font></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="15%" align="left" valign="top">
        <?php /* ### include the navigation menu view (template) ### */ ?>
        <?php M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); ?>
        </td>
        <td width="85%" align="left" valign="top">        <font face="Verdana, Arial, Helvetica, sans-serif">
	      <div class="tree">
	        <h3>Sitemap
            </h3>
	        <hr />
           <?php foreach($B->tpl_tree as $val):  ?>
             <?php echo str_repeat('-',$val['level'] * 3); ?><a href="<?php echo SF_CONTROLLER; ?>?view=node&node=<?php echo $val['node']; ?>"><?php echo $val['title']; ?></a><br /><br />
           <?php endforeach; ?>		   
		   </div>
		   </font></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
