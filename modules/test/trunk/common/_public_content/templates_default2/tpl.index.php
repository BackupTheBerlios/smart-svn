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
        <h3>SMART?</h3>
        </font>
              <p class="smart">SMART is a <a href="http://www.php.net">PHP</a> tool, which helps developers to modularize their code and so, keep it clean, well structured and scalable. The base SMART Framework package size (without any module) is small (~40kb). SMART is still alpha stuff!</p>
              <p class="smart">It wasn't the goal to build a framework, which follows the MVC pattern. Nevertheless you can identify this pattern in SMART. The <a href="http://wact.sourceforge.net/index.php/ModelViewController" target="_blank">ModelViewController</a> outline is there.</p>
              <p class="smart">The base framework has no build in high level functionalities.  An application based on this framework becomes alive through modules. &quot;Little Jo&quot;, an example application gives you an impression of what this means. This site is build up on &quot;<a href="?view=node&node=-1028336057">little Jo</a>&quot;. It is also possible to build cli/cgi php applications.</p>
              <p class="smart">SMART has a build in templates support, which was never planned but, which inevitable rose from the internal logic of SMART. You can control the templates presentation behaviour through <em>view</em> classes, which retrieves the demanded data from different modules. The template language is <a href="http://www.php.net/" target="_blank">PHP</a>.</p>
              <p class="smart">If your are not convenient with the build in template engine you can use your prefered engine. This also counts for a database layer. SMART isn't fixed to a specific DB layer. This is the choice of the module designer to include the required layer.</p>
              <p class="smart">Other build in support: error handling.</p>
              <p class="smart">SMART is released under the GNU LESSER GENERAL PUBLIC LICENSE.</p>
              <p class="smart">The SMART source code is hosted on <a href="http://developer.berlios.de/projects/smart/" target="_blank">Berlios</a> under the Subversion control.</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
