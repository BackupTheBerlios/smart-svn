<?php /*
 ### Counter template. It is loaded by defining the url var view=counter ### 
     see also /view/class.view_counter.php
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
        <td><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <strong>Test</strong></font></font></td>
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
        <td width="85%" align="left" valign="top"><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This
              page is produced by the counter template <strong>default_counter.tpl.php</strong>              and the corresponding view class <strong>view/class.view_counter.php</strong>. </font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
            1</strong> -</font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br />
              <?php /* -----------------------------------------------------------
               Print out the counter arrays defined in the event calls at
               the top of this template. 
               -----------------------------------------------------------*/?>
              <?php foreach($B->tpl_counter1 as $c_1): ?>
              <?php echo $c_1;  ?>,
              <?php endforeach; ?>  
            </font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
              2</strong> - <br />
      <?php foreach($B->tpl_counter2 as $c_2): ?>
          <?php echo $c_2;  ?>, 
      <?php endforeach; ?>  
      </font></p> 
              <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
              3</strong> - <br />
      <?php foreach($B->tpl_counter3 as $c_3): ?>
          <?php echo $c_3;  ?>, 
      <?php endforeach; ?>  
      </font></p>     
      </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
