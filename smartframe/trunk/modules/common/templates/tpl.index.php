<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<link href="<?php echo SMART_RELATIVE_PATH; ?>modules/common/media/main.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {
    color: #CCCCFF;
    font-weight: bold;
}
.style6 {
    font-size: 12px;
    color: #FFCC00;
}
.style8 {color: #0033CC}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
function go(x){
    if(x != ""){
    window.location.href = x;
    }
}
function subok(s){
    s.value = "... wait";
}
</script>
<title>Admin</title>
<style type="text/css">
<!--
.topselect {
  font-size: 12px;
  color: #660000;
  background-color: #FFFFFF;
}
-->
</style>
</head>

<body>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" bgcolor="#EBEBEB">      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="31%" align="left" valign="top"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="139" align="left" valign="top" class="style6"><img src="./modules/common/media/smart_logo.gif" width="129" height="50"> Ver.: 0.2.0.5a</td>
              </tr>
          </table></td>
          <td width="25%" align="right" valign="top" class="font10"> <br />
      <?php if($tpl['disableMainMenu']!=TRUE): ?>
          <a href="<?php echo $tpl['publicWebController']; ?>">Switch to the  public page</a>
      <?php endif; ?>
      </td>
          <td width="33%" align="right" valign="top" class="font10">
       <br />
            <?php if(!isset($tpl['notLogged'])): ?> 
            <form action="index.php" method="post">
                GoTo &gt;
                <select name="mod" class="topselect" onChange="go('<?php echo $tpl['adminWebController']; ?>?mod='+this.form.mod.options[this.form.mod.options.selectedIndex].value)"<?php if($tpl['disableMainMenu']==TRUE) echo ' disabled="disabled"'; ?>>
                 <?php foreach($tpl['moduleList'] as $key => $val): ?>
          <?php if($val['visibility'] == TRUE): ?>
                    <option value='<?php echo $key; ?>'<?php if($tpl['requestedModule'] == $key) echo " selected='selected'"; ?>><?php echo $val['alias']; ?></option>
          <?php endif; ?>
                 <?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
          </td>
          <td width="11%" align="right" valign="top">
           <br />
              <a href="<?php echo $tpl['adminWebController']; ?>?mod=user&view=adminLogout" class="font12">Logout</a>
          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top">
  <?php endif; ?>
<?php endif; ?>
        <?php /* ### include the module view (template) ### */ ?>
        <?php $viewLoader->{$tpl['moduleRootView']}(); ?>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>    
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
    </td>
  </tr>
  <tr>
      <td align="left" valign="top" bgcolor="#EBEBEB"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
          <tr>
              <td><span class="font9 style8">&copy; 2005 Armand Turpel <a href="mailto:smart@open-publisher.net">smart@open-publisher.net</a>. Project site -&gt; <a href="http://smart.open-publisher.net" target="_blank">SMART3</a>.</span></td>
          </tr>
    </table></td>
  </tr>
</table>
<?php endif; ?> 
<?php endif; ?>
</body>
</html>
