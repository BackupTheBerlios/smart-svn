<?php if(!isset($_REQUEST['nodecoration'])): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="<?php echo SF_RELATIVE_PATH; ?>modules/common/media/main.css" rel="stylesheet" type="text/css" />
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
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3399CC">      <table width="100%"  border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="39%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="left" valign="top"><span class="style4"><span class="font10"><?php echo $B->sys['info']['name']; echo ' '.$B->sys['info']['version']; ?></span></span></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="style6">Little Jo <font size="2"><strong>v0.9 - admin interface</strong></font></td>
            </tr>
          </table></td>
          <td width="15%" align="right" valign="top" class="font10"><a href="<?php echo SF_CONTROLLER; ?>">The public page</a></td>
          <td width="39%" align="right" valign="middle">
            <?php if($B->_is_logged == TRUE): ?>
            <form action="index.php" method="post">
                <select name="m" onChange="go('<?php echo SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1'; ?>&m='+this.form.m.options[this.form.m.options.selectedIndex].value)">
                 <option value="">The Modules</option>
                 <?php foreach($B->tpl_mod_list as $h): ?>
                    <option value='<?php echo $h['module']; ?>'><?php echo $h['module']; ?></option>
                 <?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
          </td>
          <td width="7%" align="right" valign="top">
           <?php if($B->_is_logged == TRUE): ?>
              <a href="<?php echo SF_CONTROLLER; ?>?view=logout" class="font14">Logout</a>
           <?php endif; ?> 
          </td>
        </tr>
          </table></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top">
  <?php endif; ?>
        <?php /* ### include the module view (template) ### */ ?>
        <?php
              if(empty($_REQUEST['m']))
              {
                  $_REQUEST['m'] = SF_DEFAULT_MODULE;
              }
              M( MOD_SYSTEM, 'get_view', array('m' => $_REQUEST['m'], 'view' => 'index') );         
        ?>
  <?php if(!isset($_REQUEST['nodecoration'])): ?> 
    </td>
  </tr>
  <tr>
      <td align="left" valign="top" bgcolor="#3399CC"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
          <tr>
              <td><span class="font9 style8">&copy; 2005 Armand Turpel <a href="mailto:smart@open-publisher.net">smart@open-publisher.net</a>. Project site -&gt; <a href="http://smart.open-publisher.net" target="_blank">SMART</a>. Released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU Public License</a></span></td>
          </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php endif; ?>