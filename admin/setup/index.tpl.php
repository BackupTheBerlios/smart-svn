<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Smart Frame Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="setup.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.subtitle {
    font-size: 12px;
    color: #990000;
    font-weight: bold;
}
.normal {font-size: 12px}
-->
</style>
</head>
<body>
<?php foreach($B->setup_error as $err) echo $err . '<br />'; ?>
<form name="setup" id="setup" method="post" action="index.php">
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3366CC"><span class="title">Smart Frame Setup </span></td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Sysadmin data </span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Name:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="sysname" type="text" size="50" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Lastname:</span></td>
        <td align="left" valign="top">
          <input name="syslastname" type="text" size="50" maxlength="255" />
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Login:</td>
        <td align="left" valign="top"><input name="syslogin" type="text" id="login" size="50" maxlength="255" /></td>
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