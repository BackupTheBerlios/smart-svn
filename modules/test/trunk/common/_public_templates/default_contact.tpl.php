<?php /* ### Contact template. It is loaded by defining the url var tpl=contact ### */ ?>

<?php /* Event to get the navigation menu entries from the navigation module action class. 
         See: /admin/modules/navigation/actions/class.NAVIGATION_GET.php 
         The result is in the array $B->tpl_nav which is printed below as the site navigation menu. */ ?>
<?php $B->M( MOD_NAVIGATION, 
             'GET', 
             array('var' => 'tpl_nav')); ?> 

<?php /* Contact Event call. See: /admin/modules/test/actions/class.TEST_CONTACT.php 
         It assign contact data to the template var $B->tpl_contact which
         is printed out bellow in this template. */ ?>          
<?php $B->M( MOD_TEST, 
             'CONTACT', 
             array('var' => 'tpl_contact')); ?> 

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
        <td><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <strong>Test</strong></font></font></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="15%" align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top"><font size="2">
              <?php if(!isset($_REQUEST['tpl'])): ?>
                 <strong>Home</strong>
              <?php else: ?>
                 <?php echo "<a href='index.php'>Home</a>"; ?>
              <?php endif; ?>
      </font></td>
          </tr>
          <?php /* -----------------------------------------------------------    
                   Print out the navigation menu.                            
                   The navigation items array $B->tpl_nav.
                   is produced by the event call at the top of this template. 
                   -----------------------------------------------------------*/ ?>          
      <?php foreach($B->tpl_nav as $key => $val): ?>
          <tr>
            <td align="left" valign="top"><font size="2">
            <?php if($_REQUEST['tpl'] == $val): ?>
                <strong><?php echo $key; ?></strong>
            <?php else: ?>
                <a href="index.php?tpl=<?php echo $val; ?>"><?php echo $key; ?></a>
            <?php endif; ?>
      </font></td>
          </tr>
      <?php endforeach; ?>
          <tr>
            <td align="left" valign="top">&nbsp;</font></td>
          </tr>     
          <tr>
            <td align="left" valign="top"><font size="2"><a href="admin/index.php">Admin</a></font></td>
          </tr>     
        </table></td>
        <td width="85%" align="left" valign="top"><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This
              page is produced by the counter template (default_contact.tpl.php). </font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contact</strong> -
              produced by a event call.
              See: template<br />
      <?php /* -----------------------------------------------------------
               Print out the contact string defined in the event call at
               the top of this template. 
               -----------------------------------------------------------*/?>              
      <?php echo nl2br($B->tpl_contact);  ?>, 
      </font></p>     
      </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
