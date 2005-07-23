<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>">
<meta name="keywords" content="">
<title>SMART3 - <?php echo $tpl['node']['title'];  ?> -------------------------------------------------</title>
<link href="<?php echo SMART_RELATIVE_PATH; ?>templates_smart/smart.css" rel="stylesheet" type="text/css">
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

<body topmargin="0">

<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="maintab">
  <tr>
    <td><!-- include header view -->
    <?php $viewLoader->header();?></td>
  </tr>
  <tr>
    <td><table width="760" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" align="left" valign="top" bgcolor="#2096DB" class="leftcol">
            <!-- include main navigation menu links view -->
            <?php $viewLoader->mainNavigation();?>  
    </td>
        <td width="638" align="left" valign="top"><table width="638" border="0" cellspacing="4" cellpadding="2">
          <tr>
            <td>
           <!-- print title and body of the contact navigation node -->
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
        <td colspan="2" align="left" class="footer">&copy; 2005 Armand Turpel&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
