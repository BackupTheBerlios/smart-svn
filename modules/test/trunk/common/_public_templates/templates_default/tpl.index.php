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
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#66CCFF"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="53%"><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <strong>Test</strong></font></font></td>
        <td width="47%" align="right" valign="middle">
		<font size="2">
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
        <td width="85%" align="left" valign="top">
        <font face="Verdana, Arial, Helvetica, sans-serif">
        <h3>SMART</h3></font>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This package comes with a couple of simple modules to show you how SMART works. </font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">The following modules are installed:</font></p>
        <ul>
          <li><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">common - </font></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">It is always required. It includes the base libraries, which are common to all other modules. E.g. PEAR, Session class, .... The event handler of this module define some base variables, such as, which module takes the authentication part, which module is loaded by default when switching to the admin area. Furthermore this module provide the top admin template. Any admin templates of other modules are included (subtemplates) in this template.</font></li>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>default</strong> - this module is loaded by default. It only provide a welcome screen.</font></li>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>navigation - </strong>It enable you to create and manage simple navigation nodes for a site.</font></li>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>option</strong> - here you can controle some global options</font></li>
          <li><font size="2"><strong>setup</strong> -</font> <font size="2">this module controle the setup process</font></li>
          <li><font size="2"><strong>user</strong> - a simple user management module</font></li>
        </ul>        <p>&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
