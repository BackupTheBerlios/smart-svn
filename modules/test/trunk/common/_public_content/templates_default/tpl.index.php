<?php /*
 ### Default template ### 
     see also /view/class.view_index.php
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
<title><?php echo $B->sys['option']['site_title']; ?></title>
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
.leftcol {
  border-top-width: 0px;
  border-right-width: 2px;
  border-bottom-width: 0px;
  border-left-width: 0px;
  border-top-style: none;
  border-right-style: solid;
  border-bottom-style: none;
  border-left-style: none;
  border-right-color: #66CCFF;
}
-->
</style>
<style type="text/css">
<!--
.para {
  font-size: 12px;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#66CCFF"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="59%"><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <font size="2">test unit</font><strong> &quot;little Jo&quot;</strong></font></font></td>
        <td width="41%" align="right" valign="middle">
          <font size="2">
          <!-- Show user name if a user is logged -->
          <?php if(isset($B->tpl_logged_user)): ?>
              User: <strong><?php echo $B->tpl_logged_user; ?></strong> is logged
          <?php endif; ?>
          </font>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="10%" align="left" valign="top" class="leftcol">
        <?php /* ### include the navigation menu view (template) ### */ ?>
        <?php M( MOD_SYSTEM, 'get_view', array('view' => 'navigation')); ?>
        </td>
        <td width="90%" align="left" valign="top">        <font face="Verdana, Arial, Helvetica, sans-serif">
        <h3>Welcome to &quot;little Jo&quot; ver.:0.2</h3>
        </font><p class="para">Little Jo is a <a href="http://www.php.net" target="_blank">php</a> web application with a few simple modules, which are based on <a href="http://smart.open-publisher.net" target="_blank">SMART</a> (The base framework). Little Jo is a good starting point to learn how SMART internally works and it's a simple tool to maintain a small website. Little Jo works without database support but with flat files, which are stored in the filesystem.  You will find all stored files in the /data folder.</p>
        <p class="para">Installed Modules:</p>
        <ul class="para">
          <li> <strong>Default</strong> - It dose nothing else then printing the welcome page of the admin interface.</li>
          <li> <strong>Navigation</strong> - Here you can add, edit and delete navigation nodes (tree structure).</li>
          <li> <strong>Option</strong> - Here you can edit some base options</li>
          <li><strong>User</strong> - Here you can add, edit or delete users. (Currently there is no build in permission system. All users have admin rights)<br />
          </li>
        </ul>        
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
