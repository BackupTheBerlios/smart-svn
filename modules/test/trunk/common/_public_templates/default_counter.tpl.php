<?php /* Event to get the navigation menu entries from the navigation module. 
         See: /admin/modules/navigation/event_handler.php 
         and /admin/modules/navigation/class.NAVIGATION_NAVIGATION_GET.php 
     The result is in the array $B->tpl_nav. */ ?>
<?php $B->M( MOD_NAVIGATION, 
             EVT_NAVIGATION_GET, 
             array('var' => 'tpl_nav')); ?> 
<?PHP /* Counter 1,2,3 Event calls. 
     See: /admin/modules/test/event_handler.php 
     and /admin/modules/test/class.TEST_COUNTER.php  */ ?>          
<?php $B->M( MOD_TEST, 
             EVT_TEST_COUNTER, 
             array('var' => 'tpl_counter1', 'start_counter' => 0, 'end_counter' => 200)); ?> 
<?php $B->M( MOD_TEST, 
             EVT_TEST_COUNTER, 
             array('var' => 'tpl_counter2', 'start_counter' => 1000, 'end_counter' => 1200)); ?>
<?php $B->M( MOD_TEST, 
             EVT_TEST_COUNTER, 
             array('var' => 'tpl_counter3', 'start_counter' => 10000, 'end_counter' => 10200)); ?>             
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
      <!-- Build the navigation -->
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
              page is produced by the counter template (default_counter.tpl.php). </font></p>
          <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
              1</strong> - produced by a event call. See: template<br />
      <?php foreach($B->tpl_counter1 as $c_1): ?>
          <?php echo $c_1;  ?>, 
      <?php endforeach; ?>  
      </font></p>
     <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
              2</strong> - produced by a event call. See: template<br />
      <?php foreach($B->tpl_counter2 as $c_2): ?>
          <?php echo $c_2;  ?>, 
      <?php endforeach; ?>  
      </font></p> 
              <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Counter
              3</strong> - produced by a event call. See: template<br />
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
