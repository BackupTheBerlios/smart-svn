<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->charset; ?>" />
<link href="media/<?php echo $B->css_folder; ?>/main.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {
    color: #CCCCFF;
    font-weight: bold;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
function go(x){
    if(x != ""){
    window.location.href = x;
    }
}
</script>
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3399CC">      <table width="100%"  border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="15%" align="left" valign="top"><span class="style4"><span class="font10">PHP Framework</span></span></td>
          <td width="14%" align="right" valign="top" class="font10"><a href="../index.php">The public page</a></td>
          <td width="64%" align="right" valign="middle"><form name="form1" id="form1" method="post" action="">
            <span class="headerdesc">Select a module:</span>
                        <form action="index.php" method="post">
            <select name="m" onChange="go('index.php?m='+this.form.m.options[this.form.m.options.selectedIndex].value)">
                        <option value="">Registered module handlers</option>
                        <?php foreach($B->mod_list as $h): ?>
                            <option value='<?php echo $h['module']; ?>'><?php echo $h['module']; ?></option>
                        <?php endforeach; ?>
            </select>
          </form></td>
          <td width="7%" align="right" valign="top"><a href="index.php?logout=1" class="font14">Logout</a></td>
        </tr>
          </table></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top"><?php include( $B->module ); ?></td>
  </tr>
</table>
</body>
</html>