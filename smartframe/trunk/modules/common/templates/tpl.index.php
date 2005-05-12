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
    font-size: 24px;
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
</head>

<body>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3399CC">      <table width="100%"  border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="39%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="left" valign="top" class="style6">SMART <font size="2"><strong>2.0a - admin interface</strong></font></td>
            </tr>
          </table></td>
          <td width="15%" align="right" valign="top" class="font10"><a href="<?php echo $tpl['publicWebController']; ?>">The public page</a></td>
          <td width="39%" align="right" valign="middle">
            <?php if(!isset($tpl['notLogged'])): ?>
            <form action="index.php" method="post">
                <select name="m" onChange="go('<?php echo $tpl['adminWebController']; ?>&m='+this.form.m.options[this.form.m.options.selectedIndex].value)">
                 <option value="">The Modules</option>
                 <?php foreach($tpl['moduleList'] as $key => $val): ?>
          <?php if($val['visibility'] == TRUE): ?>
                    <option value='<?php echo $key; ?>'<?php if($tpl['requestedModule'] == $key) echo " selected='selected'"; ?>><?php echo $key; ?></option>
          <?php endif; ?>
                 <?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
          </td>
          <td width="7%" align="right" valign="top">
              <a href="<?php echo $tpl['publicWebController']; ?>?view=logout" class="font14">Logout</a>
          </td>
        </tr>
          </table></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top">
  <?php endif; ?>
        <?php /* ### include the module view (template) ### */ ?>
        <?php $viewLoader->{$tpl['view']}(); ?>
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
    </td>
  </tr>
  <tr>
      <td align="left" valign="top" bgcolor="#3399CC"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
          <tr>
              <td><span class="font9 style8">&copy; 2005 Armand Turpel <a href="mailto:smart@open-publisher.net">smart@open-publisher.net</a>. Project site -&gt; <a href="http://smart.open-publisher.net" target="_blank">SMART</a>. Released under the <a href="http://www.gnu.org/copyleft/lgpl.html" target="_blank">GNU Lesser Public License</a></span></td>
          </tr>
      </table></td>
  </tr>
</table>
<?php endif; ?> 
</body>
</html>