<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Smart Frame Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="modules/setup/setup.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.subtitle {
    font-size: 12px;
    color: #990000;
    font-weight: bold;
}
.normal {font-size: 12px}
.style1 {color: #990000; font-size: 12px;}
-->
</style>
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
</head>
<body>
<?php foreach($B->setup_error as $err) echo $err . '<br />'; ?>
<form name="setup" id="setup" method="post" action="index.php?dbtype=<?php echo $_REQUEST['dbtype']; ?>">
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3366CC"><span class="title">Smart Frame Setup </span></td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Database Type and Charset </span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Database Type:</span></td>
        <td width="81%" align="left" valign="top">
          <select name="dbtype" id="dbtype"  onChange="go('index.php?dbtype='+this.form.dbtype.options[this.form.dbtype.options.selectedIndex].value)">
            <option value="mysql" <?php if(!isset($_REQUEST['dbtype']) || ($_REQUEST['dbtype']=='mysql')) echo 'selected="selected"'; ?>>MySql</option>
            <option value="sqlite" <?php if($_REQUEST['dbtype'] == 'sqlite') echo 'selected="selected"'; ?>>Sqlite</option>
          </select>
</td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Charset:</span></td>
        <td align="left" valign="top"><select name="charset">
          <option value="iso-8859-1" selected="selected">Western (iso-8859-1)</option>
          <option value="iso-8859-15">Western (iso-8859-15)</option>
          <option value="iso-8859-2">Central European (iso-8859-2)</option>
          <option value="iso-8859-3">South European (iso-8859-3)</option>
          <option value="iso-8859-4">Baltic (iso-8859-4)</option>
          <option value="iso-8859-13">Baltic (iso-8859-13)</option>
          <option value="iso-8859-5">Cyrillic (iso-8859-5)</option>
          <option value="iso-8859-6">Arabic (iso-8859-6)</option>
          <option value="iso-8859-7">Greek (iso-8859-7)</option>
          <option value="iso-8859-8">Hebrew (iso-8859-8)</option>
          <option value="iso-8859-9">Turkish (iso-8859-9)</option>
          <option value="iso-8859-10">Nordic (iso-8859-10)</option>
          <option value="iso-8859-11">Thai (iso-8859-11)</option>
          <option value="iso-8859-14">Celtic (iso-8859-14)</option>
          <option value="iso-8859-16">Romanian (iso-8859-16)</option>
          <option value="utf-8">Unicode (utf-8)</option>
        </select>
        </td>
      </tr>
    </table>
    <?php if(!isset($_REQUEST['dbtype']) || ($_REQUEST['dbtype'] == 'mysql')): ?>    
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Database conect data</span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Host:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="dbhost" type="text" size="50" maxlength="255" value="<?php echo $B->form_host ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">User:</span></td>
        <td align="left" valign="top">
          <input name="dbuser" type="text" size="50" maxlength="255"  value="<?php echo $B->form_user ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Password:</td>
        <td align="left" valign="top"><input name="dbpasswd" type="password" id="login" size="50" maxlength="255" /></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">DB Name :</span></td>
        <td align="left" valign="top">
          <input name="dbname" type="text" size="50" maxlength="255" value="<?php echo $B->form_dbname ?>"/>
           &nbsp;
          <input type="checkbox" name="create_db" value="checkbox" />
          <span class="normal">create DB</span></td>      
      <tr>
        <td align="left" valign="top"><span class="normal">Tables Prefix :</span></td>
        <td align="left" valign="top">
          <input name="dbtablesprefix" type="text" size="50" maxlength="255" value="<?php if(empty($B->form_tableprefix)) echo 'smart_';else echo $B->form_tableprefix; ?>"/>
        </td>
      </tr>
    </table>
    <?php endif; ?>
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Sysadmin data </span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Name:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="sysname" type="text" size="50" maxlength="255" value="<?php echo $B->form_sysname ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Lastname:</span></td>
        <td align="left" valign="top">
          <input name="syslastname" type="text" size="50" maxlength="255" value="<?php echo $B->form_syslastname ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Login:</td>
        <td align="left" valign="top"><input name="syslogin" type="text" id="login" size="50" maxlength="255" value="<?php echo $B->form_syslogin ?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Password:</span></td>
        <td align="left" valign="top">
          <input name="syspassword1" type="password" size="50" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Retype password: </span></td>
        <td align="left" valign="top">
          <input name="syspassword2" type="password" size="50" maxlength="255" />
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top"><p>&nbsp;
      </p>
      <p>
        <input type="submit" name="do_setup" value="Submit" />
      </p></td>
  </tr>
</table>
</form>
</body>
</html>