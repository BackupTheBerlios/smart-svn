<?php /* ### Index template. It is loaded by default if no template is defined. ### */ ?>

<?php /* A simple event call directed to the test module. It is a trivial example, but
         it show you how the following event call interact with the corresponding
         action class "class.TEST_PRINT.php" of the test module.
         See: /admin/modules/test/actions/class.TEST_PRINT.php
         
         A welcome string is passed to the perform($data) function of this class
         and this function assign the welcome string to the template 
         var $B->tpl_welcome_string. Bellow in this template, the template var is printed out.
      */ ?>
<?php $B->M( MOD_TEST, 
             'PRINT', 
             array('var'    => 'tpl_welcome_string',
                   'string' => 'Welcome to the SMART Framework test page!')); ?> 

<?php /* Event to get the navigation menu entries from the navigation module action class. 
         See: /admin/modules/navigation/actions/class.NAVIGATION_GET.php 
         The result is in the array $B->tpl_nav. */ ?>
<?php $B->M( MOD_NAVIGATION, 
             'GET', 
             array('var' => 'tpl_nav')); ?>
             
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
        <td width="15%" align="left" valign="top">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top">
             <font size="2">
              <?php if(!isset($_REQUEST['tpl'])): ?>
                  <strong>Home</strong>
              <?php else: ?>
                  <?php echo "<a href='index.php'>Home</a>"; ?>
              <?php endif; ?>
             </font>
            </td>  
          </tr>
          <?php /* -----------------------------------------------------------    
                   Print out the navigation menu.                            
                   The navigation items array $B->tpl_nav.
                   is produced by the event call at the top of this template. 
                   -----------------------------------------------------------*/ ?>
          <?php foreach($B->tpl_nav as $key => $val): ?>
          <tr>
            <td align="left" valign="top">
              <font size="2">
                <?php if($_REQUEST['tpl'] == $val): ?>
                   <strong><?php echo $key; ?></strong>
                <?php else: ?>
                   <a href="index.php?tpl=<?php echo $val; ?>"><?php echo $key; ?></a>
                <?php endif; ?>
              </font>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr>
            <td align="left" valign="top">&nbsp;</font></td>
          </tr>     
          <tr>
            <td align="left" valign="top"><font size="2"><a href="admin/index.php">Admin</a></font></td>
          </tr>     
        </table></td>
        <td width="85%" align="left" valign="top">
        <?php /*----------------------------------------------------------------------------------------
                Print out the welcome message defined in the the event call at the top of this template. 
                ----------------------------------------------------------------------------------------*/ ?>
        <font face="Verdana, Arial, Helvetica, sans-serif"><h3><?php echo $B->tpl_welcome_string;  ?></h3></font>
        
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">To understand
            how event calls inside the templates interact with the corresponding
            action classes of each module you have to study the templates. I
            recommand you
            to
            start
            with this
            template 'default_index.tpl.php'. You will find the public templates
            at the root folder of this SMART installation.</font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This page is produced by
              the index template (default_index.tpl.php). </font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">If no
          $_REQUEST 'tpl' var is definded this template is loaded by default.</font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Whats
            new:</strong></font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em>Version
              0.2.2a</em></font></p>
        <ul>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Clean
                  up directory structure</font></li>
          <li><font size="2">Add filter handlers. Those handlers works in the
              same way as the event handlers but independ from those:<br />
              <br />
              $B-&gt;F ( MODULE , FILTER ACTION , ADDITIONAL ACTION DATA )<br />
              <br />
              Each module and the core system can provide such a filter handler.<br />
              Those filter handlers can be called from anywhere of the system.</font></li>
          <li><font size="2">Add prepend and append event calls to the public
              front controller before and after the application logic. Those
              event calls are calling system filter events with some filter actions.<br />
              <br />
              Example of filter actions of the test package:
              <br />
              <strong><br />
              Prepend</strong><br />
              - add headers<br />
              - detecting spam bots<br />
              - do logging<br />
              <br />
              <strong>Append
              <br />
              -</strong> trim spaces of the output<br />
              - obfuscating email adresses
              </font></li>
        </ul></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
