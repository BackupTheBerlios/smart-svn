<!-- --- prevent direct all --- -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">

<!-- --- output current navigation node title --- -->
<title>SMART3 - <?php echo $tpl['node']['title'];  ?></title>

<!-- --- use allways the php relative path definition to include media files --- -->
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/typography.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.unnamed1 {
  padding-top: 1px;
  padding-right: 2px;
  padding-bottom: 3px;
  padding-left: 4px;
}
.unnamed2 {
  margin-top: 1px;
  margin-right: 2px;
  margin-bottom: 3px;
  margin-left: 4px;
}
-->
</style>
</head>

<body>

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td>
    
    <!-- --- include header view --- -->
    <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            
            <!-- --- include main navigation menu links view --- -->
            <?php $viewLoader->mainNavigation();?>  
        </td>
        <td width="638" align="left" valign="top">
         <table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td>
           
             <!-- --- print title and body of the current navigation node --- -->
             <h3>
              <?php echo $tpl['node']['title'];  ?>
             </h3>
            <div class="text"><?php echo $tpl['node']['body'];  ?></div>
          </td>
         </tr>
        </table>
       </td>
      </tr>
      <tr valign="middle" bgcolor="#516570">
        <td colspan="2" align="left" class="footer">
        
        <!-- --- Footer text --- -->
        <?php echo $tpl['footer']['body']; ?>        
        </td>
        </tr>
    </table>
   </td>
  </tr>
</table>
</body>
</html>
